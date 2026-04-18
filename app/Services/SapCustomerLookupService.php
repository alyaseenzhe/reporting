<?php

namespace App\Services;

use App\Contracts\SapCustomerLookupServiceInterface;
use App\Models\AccMast;
use Illuminate\Support\Facades\Cache;

class SapCustomerLookupService implements SapCustomerLookupServiceInterface
{
    /**
     * Search SAP customers using the ODBC SAP connection, with local AccMast fallback.
     */
    public function searchCustomers(?string $search, array $customerCodePrefixes = [], int $limit = 50): array
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
    public function searchCustomerRows(?string $search, array $customerCodePrefixes = [], int $limit = 50): array
    {
        $search = trim((string) $search);
        $customerCodePrefixes = $this->normalizeCustomerCodePrefixes($customerCodePrefixes);
        $limit = max(1, min($limit, 100));

        if ($search === '' || mb_strlen($search) < 2) {
            return [];
        }

        try {
            return Cache::remember(
                'sap_customers_search_rows_' . md5($search . '|' . implode(',', $customerCodePrefixes) . '|' . $limit),
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

    protected function searchCustomersFromSap(string $search, array $customerCodePrefixes, int $limit): array
    {
        $searchEscaped = str_replace("'", "''", $search);
        $prefixWhere = $this->buildSapCustomerPrefixWhere($customerCodePrefixes);
        $query = 'SELECT T0."CardCode", T0."CardName", T0."SlpCode", T1."SlpName"'
            . ' FROM AL_YASEEN_AGRI_PLIVE.OCRD T0'
            . ' LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP T1 ON T0."SlpCode" = T1."SlpCode"'
            . ' WHERE T0."CardType" = \'C\''
            . $prefixWhere
            . ' AND (T0."CardCode" LIKE \'%' . $searchEscaped . '%\''
            . ' OR T0."CardName" LIKE \'%' . $searchEscaped . '%\')'
            . ' ORDER BY T0."CardCode"'
            . ' LIMIT ' . $limit;

        return collect($this->querySapRows($query, $limit))
            ->map(function (array $row): array {
                return [
                    'code' => $row['CardCode'],
                    'name' => $row['CardName'],
                    'slp_code' => $row['SlpCode'] ?? null,
                    'slp_name' => $row['SlpName'] ?? null,
                    'label' => $this->formatLabel($row['CardCode'], $row['CardName']),
                ];
            })
            ->toArray();
    }

    protected function findCustomerByCodeFromSap(string $customerCode): ?array
    {
        $customerCodeEscaped = str_replace("'", "''", $customerCode);
        $query = 'SELECT T0."CardCode", T0."CardName", T0."SlpCode", T1."SlpName"'
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
            'name' => $row['CardName'],
            'slp_code' => $row['SlpCode'] ?? null,
            'slp_name' => $row['SlpName'] ?? null,
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

    protected function searchCustomersFromLocalAccMast(string $search, array $customerCodePrefixes, int $limit): array
    {
        return AccMast::query()
            ->where('Type', '10')
            ->when(count($customerCodePrefixes), function ($query) use ($customerCodePrefixes) {
                $query->where(function ($query) use ($customerCodePrefixes) {
                    foreach ($customerCodePrefixes as $prefix) {
                        $query->orWhere('Code', 'like', $prefix . '%');
                    }
                });
            })
            ->where(function ($query) use ($search) {
                $query->where('Arabic_Name', 'like', '%' . $search . '%')
                    ->orWhere('Code', 'like', '%' . $search . '%');
            })
            ->orderBy('Arabic_Name')
            ->limit($limit)
            ->get(['Code', 'Arabic_Name'])
            ->map(function ($customer): array {
                return [
                    'code' => $customer->Code,
                    'name' => $customer->Arabic_Name,
                    'slp_code' => null,
                    'slp_name' => null,
                    'label' => $this->formatLabel($customer->Code, $customer->Arabic_Name),
                ];
            })
            ->toArray();
    }

    protected function findCustomerByCodeFromLocalAccMast(string $customerCode): ?array
    {
        $customer = AccMast::query()
            ->where('Type', '10')
            ->where('Code', $customerCode)
            ->first(['Code', 'Arabic_Name']);

        if (! $customer) {
            return null;
        }

        return [
            'code' => $customer->Code,
            'name' => $customer->Arabic_Name,
            'label' => $this->formatLabel($customer->Code, $customer->Arabic_Name),
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
}
