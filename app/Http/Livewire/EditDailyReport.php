<?php

namespace App\Http\Livewire;

use App\Models\DailyReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditDailyReport extends Component
{
    public $record;

    public $report_note;
    public $location1;
    public $location2;
    public $customer_list = [];
    public $report_date;
    public $report_type;
    public $customer_name;
    public $companion;

    protected $listeners = ['update-report' => 'updateReport'];

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

    public function mount($id) {
        try {
            $this->record = DailyReport::findOrFail($id);

            $this->report_note = $this->record->report_note;
            $this->location1 = $this->record->location1;
            $this->location2 = $this->record->location2;
            $this->report_date = $this->record->report_date;
            $this->report_type = $this->record->report_type;
            $this->customer_name = $this->record->customer_name;
            $this->companion = $this->record->companion;

            $this->getCustomers();

        } catch (ModelNotFoundException $exception) {
            session()->flash('message', 'هذا المستخدم غير موجود');
            return redirect()->route('list.users');
        }

    }

    public function render()
    {
        return view('livewire.edit-daily-report')
            ->layout('layouts.dashboard');
    }

    public function updateReport($report_type, $report_date, $customer_name, $location1, $location2, $companion, $report_note) {


        $rpt_date = Carbon::parse($report_date);

        $record = DailyReport::find($this->record->id);

        $record->location1 = $location1;
        $record->report_date = $report_date;
        $record->report_type = $report_type;
        $record->location2 = $location2;
        $record->customer_name =  $report_type == 'visit' ? $customer_name : null;
        $record->companion = $report_type == 'visit' ? $companion : null;
        $record->report_note = $report_note;
        $record->start_of_week = $rpt_date->startOfWeek(Carbon::FRIDAY)->format('Y-m-d');
        $record->end_of_week = $rpt_date->endOfWeek(Carbon::THURSDAY)->format('Y-m-d');
        //'added_by' => Auth::id(),

        if($record->save()) {
            session()->flash('success', 'تم تحديث التقرير بنجاح');
            return redirect()->route('list.daily-reports');
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند تحديث التقرير');
            return redirect()->route('list.daily-reports');
        }
    }

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
