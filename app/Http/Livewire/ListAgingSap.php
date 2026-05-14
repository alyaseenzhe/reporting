<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ListAgingSap extends Component
{
    use WithPagination;

    public $area_id = -1;
    public $selected_date;
    public $last_date;
    public $aging_records = [];
    public $branch_reports = [];

    public function render()
    {
        return view('livewire.list-aging-sap')
            ->layout('layouts.dashboard');
    }

    public function generateReport()
    {
        set_time_limit(2000);
        $this->emit('show-container');

        $this->last_date = date('Y-m-t', strtotime($this->selected_date));
        $this->aging_records = [];
        $this->branch_reports = [];

        $conn = $this->connectToSap();

        if (! $conn) {
            return;
        }

        try {
            $targetAreaIds = $this->area_id === 'all'
                ? array_keys($this->getAreaOptions())
                : [(string) $this->area_id];

            foreach ($targetAreaIds as $targetAreaId) {
                $records = $this->getCustomersBalanceDue($conn, $this->last_date, $targetAreaId);

                if (! count($records)) {
                    continue;
                }

                $this->branch_reports[] = [
                    'branch_code' => $targetAreaId,
                    'branch_name' => $this->getAreaOptions()[$targetAreaId] ?? $targetAreaId,
                    'aging_records' => $records,
                ];
            }

            if ($this->area_id === 'all') {
                $this->aging_records = collect($this->branch_reports)
                    ->pluck('aging_records')
                    ->flatten(1)
                    ->values()
                    ->all();
            } elseif (count($this->branch_reports)) {
                $this->aging_records = $this->branch_reports[0]['aging_records'];
            }
        } finally {
            odbc_close($conn);
        }

        $this->emit('show-data');
    }

    protected function getCustomersBalanceDue($conn, string $endDate, string $areaId): array
    {
        $sql = '
SELECT
    AG."BusinessPartnerCode" AS "Business Partner Code",
    AG."BusinessPartnerName" AS "Business Partner Name",
    OC."SlpCode" AS "SlpCode",
    OS."Memo" AS "Memo",
    OS."SlpName" AS "SlpName",
    AG."PostingDate" AS "Posting Date",
    AG."DocumentDate" AS "Document Date",
    \'\' AS "Document Number",
    AG."AgingBalanceDueLC" AS "Debit (LC)",
    DAYS_BETWEEN(AG."PostingDate", \'' . $endDate . '\') AS "Days"
FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/CustomerReceivableAgingQuery" (\'PLACEHOLDER\' = (\'$$P_AgingDate$$\', \'' . $endDate . '\')) AG
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OCRD OC ON AG."BusinessPartnerCode" = OC."CardCode"
LEFT JOIN AL_YASEEN_AGRI_PLIVE.OSLP OS ON OC."SlpCode" = OS."SlpCode"
WHERE AG."AgingBalanceDueLC" > 0
AND AG."BusinessPartnerCode" LIKE \'' . $areaId . '%\'
ORDER BY
    OS."Memo" ASC,
    OS."SlpName" ASC,
    AG."BusinessPartnerCode" ASC,
    AG."PostingDate" ASC,
    AG."DocumentDate" ASC';

        $result = odbc_exec($conn, $sql);

        if (! $result) {
            echo "Error while sending SQL statement to the database server.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();

            return [];
        }

        $records = [];

        while ($row = odbc_fetch_array($result)) {
            $records[] = $row;
        }

        return $records;
    }

    protected function connectToSap()
    {
        if (! extension_loaded('odbc')) {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');
        $host = env('DB_HOST_FOURTH');
        $dbName = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect(
            "Driver=$driver;ServerNode=$host;Database=$dbName;char_as_utf8=true;",
            $username,
            $password,
            SQL_CUR_USE_ODBC
        );

        if (! $conn) {
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
        }

        return $conn;
    }

    protected function getAreaOptions(): array
    {
        return [
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
            '05' => '05',
            '06' => '06',
            '07' => '07',
            '08' => '08',
            '09' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
        ];
    }
}
