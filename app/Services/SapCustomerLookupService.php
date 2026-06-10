<?php

namespace App\Services;

use App\Contracts\SapCustomerLookupServiceInterface;
use App\Models\AccMast;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SapCustomerLookupService implements SapCustomerLookupServiceInterface
{
    /**
     * Search SAP customers using the ODBC SAP connection, with local AccMast fallback.
     */
    public function searchCustomers(?string $search, array $customerCodePrefixes = [], ?int $limit = 50): array
    {

        return collect($this->searchCustomerRows($search, $customerCodePrefixes, $limit))
            ->mapWithKeys(function (array $customer): array {
                return [
                    $customer['code'] => $customer['label'],
                ];
            })
            ->toArray();
    }

    /**
     * Search SAP customers and return customer details needed by filtered forms.
     */
    public function searchCustomerRows(?string $search, array $customerCodePrefixes = [], ?int $limit = 50): array
    {
        $search = trim((string) $search);
        $customerCodePrefixes = $this->normalizeCustomerCodePrefixes($customerCodePrefixes);
        $limit = $this->normalizeLimit($limit);

        if ($search !== '' && mb_strlen($search) < 2) {
            return [];
        }

        try {

            return Cache::remember(
                'sap_customers_search_rows_v2_' . md5($search . '|' . implode(',', $customerCodePrefixes) . '|' . ($limit ?? 'all')),
                now()->addMinutes(10),
                function () use ($search, $customerCodePrefixes, $limit) {
                    $results = $this->searchCustomersFromSap($search, $customerCodePrefixes, $limit);

                    if (! empty($results)) {
                        return $results;
                    }

                    return $this->searchCustomersFromLocalAccMast($search, $customerCodePrefixes, $limit);
                }
            );
        } catch (\Throwable $exception) {
            report($exception);

            return [];
        }
    }

    /**
     * Resolve a single SAP customer by code, ODBC first then local AccMast fallback.
     */
    public function findCustomerByCode(?string $customerCode): ?array
    {
        $customerCode = trim((string) $customerCode);

        if ($customerCode === '') {
            return null;
        }

        try {
            return Cache::remember(
                'sap_customer_v2_' . md5($customerCode),
                now()->addMinutes(30),
                function () use ($customerCode) {
                    $customer = $this->findCustomerByCodeFromSap($customerCode);

                    if ($customer !== null) {
                        return $customer;
                    }

                    return $this->findCustomerByCodeFromLocalAccMast($customerCode);
                }
            );
        } catch (\Throwable $exception) {
            report($exception);

            return null;
        }
    }

    protected function searchCustomersFromSap(string $search, array $customerCodePrefixes, ?int $limit): array
    {
        $searchEscaped = str_replace("'", "''", $search);
        $prefixWhere = $this->buildSapCustomerPrefixWhere($customerCodePrefixes);
        $query = 'SELECT T0."CardCode", T0."CardName", T0."SlpCode", T1."SlpName", T0."QryGroup1"'
            . ' FROM AL_YASEEN_AGRI_PLIVE.OCRD T0'
            . ' LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP T1 ON T0."SlpCode" = T1."SlpCode"'
            . ' WHERE T0."CardType" = \'C\''
            . $prefixWhere;

        if ($search !== '') {
            $query .= ' AND (T0."CardCode" LIKE \'%' . $searchEscaped . '%\''
                . ' OR T0."CardName" LIKE \'%' . $searchEscaped . '%\')';
        }

        $query .= ' ORDER BY T0."CardCode"';

        if ($limit !== null) {
            $query .= ' LIMIT ' . $limit;
        }
        $rows = $this->querySapRows($query, $limit);

//        dd([
//            'query' => $query,
//            'rows_count' => count($rows),
//            'first_5_rows' => array_slice($rows, 0, 5),
//        ]);

        return collect($rows)
            ->map(function (array $row): array {
                $code = (string) ($row['CardCode'] ?? '');
                $name = (string) ($row['CardName'] ?? '');

                return [
                    'code' => $code,
                    'name' => $name,
                    'slp_code' => $row['SlpCode'] ?? null,
                    'slp_name' => $row['SlpName'] ?? null,
                    'property_1' => ($row['QryGroup1'] ?? null) === 'Y',
                    'label' => $this->formatLabel($code, $name),
                ];
            })
            ->filter(fn (array $customer): bool => $customer['code'] !== '')
            ->values()
            ->toArray();
         //dd($query);
//        return collect($this->querySapRows($query, $limit))
//            ->map(function (array $row): array {
//                return [
//                    'code' => $row['CardCode'],
//                    'name' => $row['CardName'],
//                    'slp_code' => $row['SlpCode'] ?? null,
//                    'slp_name' => $row['SlpName'] ?? null,
//                    'property_1' => ($row['QryGroup1'] ?? null) === 'Y',
//                    'label' => $this->formatLabel($row['CardCode'], $row['CardName']),
//                ];
//            })
//            ->toArray();
    }

    protected function findCustomerByCodeFromSap(string $customerCode): ?array
    {
        $customerCodeEscaped = str_replace("'", "''", $customerCode);
        $query = 'SELECT T0."CardCode", T0."CardName", T0."SlpCode", T1."SlpName", T0."QryGroup1"'
            . ' FROM AL_YASEEN_AGRI_PLIVE.OCRD T0'
            . ' LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP T1 ON T0."SlpCode" = T1."SlpCode"'
            . ' WHERE T0."CardType" = \'C\''
            . ' AND T0."CardCode" = \'' . $customerCodeEscaped . '\'';

        $rows = $this->querySapRows($query);

        if (empty($rows)) {
            return null;
        }

        $row = $rows[0];

        return [
            'code' => $row['CardCode'],
            'name' => $row['CardName'] ?? null,
            'slp_code' => $row['SlpCode'] ?? null,
            'slp_name' => $row['SlpName'] ?? null,
            'property_1' => ($row['QryGroup1'] ?? null) === 'Y',
            'label' => $this->formatLabel($row['CardCode'], $row['CardName']),
        ];
    }

    protected function querySapRows(string $sql, ?int $maxRows = null): array
    {
        $conn = $this->getOdbcConnection();

        if (! $conn) {
            return [];
        }

        try {
            $result = odbc_exec($conn, $sql);

            if (! $result) {
                return [];
            }

            $rows = [];
            while ($row = odbc_fetch_array($result)) {
                $rows[] = $row;

                if ($maxRows !== null && count($rows) >= $maxRows) {
                    break;
                }
            }

            return $rows;
        } finally {
            @odbc_close($conn);
        }
    }

    protected function querySapAsMap(string $sql): array
    {
        $rows = $this->querySapRows($sql);

        return array_reduce($rows, function ($carry, $row) {
            $carry[$row['CardCode']] = $this->formatLabel($row['CardCode'], $row['CardName']);
            return $carry;
        }, []);
    }

    protected function getOdbcConnection()
    {
        if (! extension_loaded('odbc')) {
            return null;
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = @odbc_connect(
            "Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;",
            $username,
            $password,
            SQL_CUR_USE_ODBC
        );

        return $conn ?: null;
    }

    protected function searchCustomersFromLocalAccMast(string $search, array $customerCodePrefixes, ?int $limit): array
    {
        if ($search === '') {
            $query = AccMast::query()->where('Type', '10');
            $this->applyLocalCustomerPrefixFilter($query, $customerCodePrefixes);

            return $query
                ->orderBy('Code')
                ->when($limit !== null, fn ($query) => $query->limit($limit))
                ->get(['Code', 'Name', 'Arabic_Name'])
                ->map(fn ($customer): array => $this->localCustomerToRow($customer))
                ->values()
                ->all();
        }

        $codeQuery = AccMast::query()->where('Type', '10');
        $this->applyLocalCustomerPrefixFilter($codeQuery, $customerCodePrefixes);

        $codeMatches = $codeQuery
            ->where('Code', 'like', '%' . $search . '%')
            ->orderBy('Code')
            ->when($limit !== null, fn ($query) => $query->limit($limit))
            ->get(['Code', 'Name', 'Arabic_Name'])
            ->map(fn ($customer): array => $this->localCustomerToRow($customer));

        if ($limit !== null && $codeMatches->count() >= $limit) {
            return $codeMatches->take($limit)->values()->all();
        }

        $nameQuery = AccMast::query()->where('Type', '10');
        $this->applyLocalCustomerPrefixFilter($nameQuery, $customerCodePrefixes);

        $nameMatches = $nameQuery
            ->when($codeMatches->isNotEmpty(), fn ($query) => $query->whereNotIn('Code', $codeMatches->pluck('code')->all()))
            ->orderBy('Code')
            ->limit(2000)
            ->get(['Code', 'Name', 'Arabic_Name'])
            ->filter(fn ($customer): bool => $this->matchesLocalCustomer($customer, $search))
            ->map(fn ($customer): array => $this->localCustomerToRow($customer))
            ->values();

        return $codeMatches
            ->merge($nameMatches)
            ->when($limit !== null, fn ($rows) => $rows->take($limit))
            ->values()
            ->all();
    }

    protected function findCustomerByCodeFromLocalAccMast(string $customerCode): ?array
    {
        $customer = AccMast::query()
            ->where('Type', '10')
            ->where('Code', $customerCode)
            ->first(['Code', 'Name', 'Arabic_Name']);

        if (! $customer) {
            return null;
        }

        $customerName = $this->resolveLocalCustomerName($customer);

        return [
            'code' => $customer->Code,
            'name' => $customerName,
            'property_1' => false,
            'label' => $this->formatLabel($customer->Code, $customerName),
        ];
    }

    /**
     * Build the display label shown in the Filament select.
     */
    protected function formatLabel(string $code, string $name): string
    {
        return trim($code . ' - ' . $name);
    }

    protected function normalizeCustomerCodePrefixes(array $customerCodePrefixes): array
    {
        return array_values(array_unique(array_filter(array_map(
            fn ($prefix): string => trim((string) $prefix),
            $customerCodePrefixes
        ))));
    }

    protected function normalizeLimit(?int $limit): ?int
    {
        if ($limit === null) {
            return null;
        }

        return max(1, min($limit, 1000));
    }

    protected function buildSapCustomerPrefixWhere(array $customerCodePrefixes): string
    {
        if (! count($customerCodePrefixes)) {
            return '';
        }

        $conditions = array_map(function (string $prefix): string {
            $prefixEscaped = str_replace("'", "''", $prefix);

            return 'T0."CardCode" LIKE \'' . $prefixEscaped . '%\'';
        }, $customerCodePrefixes);

        return ' AND (' . implode(' OR ', $conditions) . ')';
    }

    protected function applyLocalCustomerPrefixFilter($query, array $customerCodePrefixes): void
    {
        if (! count($customerCodePrefixes)) {
            return;
        }

        $query->where(function ($query) use ($customerCodePrefixes) {
            foreach ($customerCodePrefixes as $prefix) {
                $query->orWhere('Code', 'like', $prefix . '%');
            }
        });
    }

    protected function localCustomerToRow($customer): array
    {
        $customerName = $this->resolveLocalCustomerName($customer);

        return [
            'code' => $customer->Code,
            'name' => $customerName,
            'slp_code' => null,
            'slp_name' => null,
            'property_1' => false,
            'label' => $this->formatLabel($customer->Code, $customerName),
        ];
    }

    protected function matchesLocalCustomer($customer, string $search): bool
    {
        $search = $this->normalizeSearchText($search);

        if ($search === '') {
            return true;
        }

        return mb_strpos($this->normalizeSearchText((string) $customer->Code), $search) !== false
            || mb_strpos($this->normalizeSearchText($this->resolveLocalCustomerName($customer)), $search) !== false;
    }

    protected function resolveLocalCustomerName($customer): string
    {
        return (string) (
            $this->decryptLocalValue($customer->Arabic_Name ?? null)
            ?: $this->decryptLocalValue($customer->Name ?? null)
            ?: ''
        );
    }

    protected function decryptLocalValue($value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        try {
            return (string) Crypt::decryptString($value);
        } catch (DecryptException $exception) {
            return $value;
        }
    }

    protected function normalizeSearchText(string $value): string
    {
        return trim(mb_strtolower($value));
    }
}
