<?php

namespace App\Http\Livewire;

use App\Models\AccMast;
use App\Models\DailyReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewDailyReport extends Component
{
    public $report_note;
    public $location1 = "المركز الرئيسي - الاحساء";
    public $location2;
    public $customer_list = [];
    public $report_date;
    public $report_type = 'work';
    public $customer_name;
    public $companion;

    protected $listeners = ['create-report' => 'createReport'];

    protected $rules = [
        'location1' => 'required',
        'location2' => 'required',
        'companion' => 'required',
        'report_date' => 'required',
        'report_note' => 'required',
        'customer_name' => 'required|not_in:-1',
    ];

    protected $messages = [
        'location1.required' => 'مطلوب',
        'location2.required' => 'مطلوب',
        'companion.required' => 'مطلوب',
        'report_date.required' => 'مطلوب',
        'report_note.required' => 'مطلوب',
        'customer_name.required' => 'مطلوب',
        'customer_name.not_in' => 'مطلوب',
    ];

    /**
     * This is a Livewire lifecycle hook that runs once when the component is initialized.
     * Its purpose is to prepare the form by pre-loading the list of customers from the
     * SAP database so the customer dropdown is ready for the user.
     */
    public function mount() {
        $this->getCustomers();
    }

    /**
     * The standard Livewire method that renders the component's Blade view and sets the master
     * dashboard layout for a consistent UI.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
//        $customers = AccMast::where('Type', '10')
//            ->select('Code', 'Arabic_Name')
//            ->get();
//        $this->getCustomers();

        return view('livewire.new-daily-report')
            ->layout('layouts.dashboard');
    }

    /**
     * This is the primary action method for creating and saving a new daily report. It is triggered
     * by an event from the frontend. It processes the submitted form data, calculates the start and
     * end of the week for reporting purposes, saves the new record to the database, and then handles
     * the response based on which save button the user clicked ("Save" vs. "Save and New").
     *
     * @param string $report_type Type of the report ('work' or 'visit').
     * @param string $report_date The date the report is for.
     * @param string $customer_name The name of the customer visited (if applicable).
     * @param string $location1 The primary location.
     * @param string $location2 The secondary location.
     * @param string $companion Name of the companion on the visit (if applicable).
     * @param string $report_note The main text/notes for the report.
     * @param string $btn The identifier for the button clicked ('saveOnly' or 'saveAndNew').
     */
    public function createReport($report_type, $report_date, $customer_name, $location1, $location2, $companion, $report_note, $btn) {

//        dd($report_type.'||'. $report_date.'||'. $customer_name.'||'. $location1.'||'. $location2.'||'. $companion.'||'. $report_note);
//        $this->validate();

        $rpt_date = Carbon::parse($report_date);
//        dd($rpt_date->startOfWeek(Carbon::FRIDAY)->format('Y-m-d'));
//        dd($x->startOfWeek(Carbon::FRIDAY)->format('Y-m-d'));
//        dd($x->endOfWeek(Carbon::THURSDAY)->format('Y-m-d'));
//        $rpt_date->startOfWeek(Carbon::FRIDAY)->format('Y-m-d')

        $record = DailyReport::create([
            'location1' => $location1,
            'report_date' => $report_date,
            'report_type' => $report_type,
            'location2' => $location2,
            'customer_name' =>  $report_type == 'visit' ? $customer_name : null,
            'companion' => $report_type == 'visit' ? $companion : null,
            'report_note' => $report_note,
            'start_of_week' => $rpt_date->startOfWeek(Carbon::FRIDAY)->format('Y-m-d'),
            'end_of_week' => $rpt_date->endOfWeek(Carbon::THURSDAY)->format('Y-m-d'),
            'added_by' => Auth::id(),
        ]);

        if($record) {

            if ($btn == 'saveOnly') {
                session()->flash('success', 'تم إنشاء التقرير بنجاح');
                return redirect()->route('list.daily-reports');
            }
            elseif ($btn == 'saveAndNew') {
                session()->flash('success', 'تم إنشاء التقرير بنجاح');
                $this->emit('finished');
//                return redirect()->route('create.daily-report');
            }
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند إنشاء التقرير');
            return redirect()->route('list.daily-reports');
        }
    }

    /**
     * This helper method is responsible for fetching a complete list of customers from the SAP HANA
     * database via an ODBC connection. The retrieved list is then used to populate the customer
     * selection dropdown in the component's view.
     */
    public function getCustomers()
    {

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
            $customerQuery = 'SELECT T0."CardCode", T0."CardName" FROM AL_YASEEN_AGRI_PLIVE.OCRD T0 WHERE T0."CardType" = \'C\'';

            $result = odbc_exec($conn, $customerQuery);
            if (!$result)
            {
                echo "Error while sending SQL statement to the database server.\n";
                echo "ODBC error code: " . odbc_error() . ". Message: " . odbc_errormsg();
            }
            else
            {
                while ($row = odbc_fetch_array($result)) {
                    array_push($this->customer_list, $row);
                }
            }
            odbc_close($conn);
        }

    }
}
