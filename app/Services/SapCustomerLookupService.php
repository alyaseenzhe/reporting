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
    public function searchCustomers(?string $search): array
    {
        $search = trim((string) $search);

        if ($search === '' || mb_strlen($search) < 2) {
            return [];
        }

        try {
            return Cache::remember(
                'sap_customers_search_' . md5($search),
                now()->addMinutes(10),
                function () use ($search) {
                    $results = $this->searchCustomersFromSap($search);

                    if (! empty($results)) {
                        return $results;
                    }

                    return $this->searchCustomersFromLocalAccMast($search);
                }
            );
        } catch (\Throwable $exception) {
            report($exception);

            return [];
        }
    }

    /**
     * Resolve a single SAP customer by code.
     */
    public function findCustomerByCode(?string $customerCode): ?array
    {
        $customerCode = trim((string) $customerCode);

        if ($customerCode === '') {
            return null;
        }

        try {
            return Cache::remember(
                'sap_customer_' . md5($customerCode),
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

    protected function searchCustomersFromSap(string $search): array
    {
        $searchEscaped = str_replace("'", "''", $search);
        $query = 'SELECT T0."CardCode", T0."CardName"'
            . ' FROM AL_YASEEN_AGRI_PLIVE.OCRD T0'
            . ' WHERE T0."CardType" = \'C\''
            . ' AND (T0."CardCode" LIKE \'%' . $searchEscaped . '%\''
            . ' OR T0."CardName" LIKE \'%' . $searchEscaped . '%\')'
            . ' ORDER BY T0."CardCode"';

        return $this->querySapAsMap($query);
    }

    protected function findCustomerByCodeFromSap(string $customerCode): ?array
    {
        $customerCodeEscaped = str_replace("'", "''", $customerCode);
        $query = 'SELECT T0."CardCode", T0."CardName"'
            . ' FROM AL_YASEEN_AGRI_PLIVE.OCRD T0'
            . ' WHERE T0."CardType" = \'C\''
            . ' AND T0."CardCode" = \\' . $customerCodeEscaped . '\'';

        $rows = $this->querySapRows($query);

        if (empty($rows)) {
            return null;
        }

        $row = $rows[0];

        return [
            'code' => $row['CardCode'],
            'name' => $row['CardName'],
            'label' => $this->formatLabel($row['CardCode'], $row['CardName']),
        ];
    }

    protected function querySapRows(string $sql): array
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

    protected function searchCustomersFromLocalAccMast(string $search): array
    {
        return AccMast::query()
            ->where('Type', '10')
            ->where(function ($query) use ($search) {
                $query->where('Arabic_Name', 'like', '%' . $search . '%')
                    ->orWhere('Code', 'like', '%' . $search . '%');
            })
            ->orderBy('Arabic_Name')
            ->limit(50)
            ->get(['Code', 'Arabic_Name'])
            ->mapWithKeys(function ($customer) {
                return [
                    $customer->Code => $this->formatLabel(
                        $customer->Code,
                        $customer->Arabic_Name
                    ),
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

    protected function formatLabel(string $code, string $name): string
    {
        return trim($code . ' - ' . $name);
    }
}
