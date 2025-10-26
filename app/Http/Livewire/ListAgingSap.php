<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ListAgingSap extends Component
{
    use WithPagination;

    public $area_id = -1;
    public $selected_date;
    public $last_date;
    public $aging_records = [];

    /**
     * This is a mandatory Livewire method that renders the component's user interface.
     * It returns the specified Blade view file (`list-aging-sap.blade.php`), which contains the HTML
     * for the report. It also embeds this view within the application's main `layouts.dashboard`
     * template to ensure a consistent look and feel.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.list-aging-sap')
            ->layout('layouts.dashboard');
    }

    /**
     * This is the primary action method for generating the aging report, typically triggered by a
     * user clicking a "Generate" button on the frontend. It sets a longer script execution time limit,
     * calculates the last day of the selected month to ensure the query runs against a consistent period,
     * and then calls the main data-fetching function `getCustomersBalanceDue`. It also emits events to the
     * frontend to manage the UI state (e.g., showing/hiding loading indicators and results).
     */
    public function generateReport()
    {
        set_time_limit(2000);
//        $this->validate();
        $this->emit('show-container');

        $this->last_date = date('Y-m-t', strtotime($this->selected_date));

        $this->aging_records = [];
        $this->getCustomersBalanceDue($this->last_date);

        $this->emit('show-data');

    }

    /**
     * This function is the core of the report. It connects to the SAP HANA database via ODBC and executes
     * a single, efficient query to retrieve the raw, un-bucketed customer aging data. It leverages a powerful,
     * pre-built SAP analytical view (`CustomerReceivableAgingQuery`) for high performance. The query fetches each
     * outstanding invoice, joins it with customer and salesperson data for context, filters by the selected
     * branch/area, and orders the results logically.
     *
     * @param string $end_date The date (typically the end of a month) to calculate the aging against.
     */
    public function getCustomersBalanceDue($end_date) {

        if (! extension_loaded('odbc'))
        {
            die('ODBC extension not enabled / loaded');
        }

        $driver = env('DB_CONNECTION_FOURTH');

        $host = env('DB_HOST_FOURTH');

        $db_name = env('DB_DATABASE_FOURTH');
        $username = env('DB_USERNAME_FOURTH');
        $password = env('DB_PASSWORD_FOURTH');

        $conn = odbc_connect("Driver=$driver;ServerNode=$host;Database=$db_name;char_as_utf8=true;", $username, $password, SQL_CUR_USE_ODBC);

        if (!$conn)
        {
            echo "Connection failed.\n";
            echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();

        }
        else
        {
            $sql_aging = 'SELECT
    DAYS_BETWEEN("PostingDate", \''.$end_date.'\') AS "days",
    "OCRD"."SlpCode", "OSLP"."SlpName", "OSLP"."Memo","BusinessPartnerCode" as "Business Partner Code",
    "BusinessPartnerName" as "Business Partner Name", "BaseDocumentNumber" as "Document Number", "PostingDate" as "Posting Date",
    "AgingBalanceDueLC" as "Debit (LC)"--,*
FROM "_SYS_BIC"."sap.alyaseenagriplive.ar.case/CustomerReceivableAgingQuery"
    (\'PLACEHOLDER\' = (\'$$P_AgingDate$$\', \''.$end_date.'\'))

    LEFT JOIN "AL_YASEEN_AGRI_PLIVE"."OCRD" ON "BusinessPartnerCode" = "CardCode"
    LEFT JOIN "AL_YASEEN_AGRI_PLIVE"."OSLP" ON "OCRD"."SlpCode" = "OSLP"."SlpCode"
WHERE "OCRD"."CardType" = \'C\'
AND "OCRD"."CardCode" LIKE \''.$this->area_id.'%\'
ORDER BY "OCRD"."SlpCode", "BusinessPartnerCode", DAYS_BETWEEN("PostingDate", \''.$end_date.'\') DESC';

//                dd($sql_aging);

            $result_aging = odbc_exec($conn, $sql_aging);
            if (!$result_aging)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {
                while ($row = odbc_fetch_array($result_aging)) {
//                        dd($row);
                    array_push($this->aging_records, $row);
//                        array_push($this->customer_code, $row["CardCode"]);
//                        $aging_balance += $row["Aging"];
                }

            }

            odbc_close($conn);
        }

//        dd($this->aging_records);

    }

}
