<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyReport extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $branch;
    protected $start_date;
    protected $end_date;
    protected $emp_codes;
    protected $customer_purchased;
    protected $visits;
    protected $category_qty;
    protected $category_qty_total;
    protected $overFiftyThousand;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $branch, $start_date, $end_date, $visits)
    {
        //
        $this->data = $data;
        $this->branch = $branch;
        $this->start_date = $start_date;
        $this->end_date= $end_date;
        $this->visits = $visits;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $branch_name = '';
        if ($this->branch == '01') {
            $branch_name = 'فرع الاحساء';
        }
        elseif ($this->branch == '02') {
            $branch_name = 'فرع جدة';
        }
        elseif ($this->branch == '03') {
            $branch_name = 'فرع الرياض';
        }
        elseif ($this->branch == '04') {
            $branch_name = 'فرع وادي الدواسر';
        }
        elseif ($this->branch == '05') {
            $branch_name = 'فرع الجوف';
        }
        elseif ($this->branch == '06') {
            $branch_name = 'فرع الدمام';
        }
        elseif ($this->branch == '07') {
            $branch_name = 'فرع الخرج';
        }
        elseif ($this->branch == '08') {
            $branch_name = 'فرع نجران';
        }
        elseif ($this->branch == '09') {
            $branch_name = 'فرع حائل';
        }
        elseif ($this->branch == '10') {
            $branch_name = 'فرع تبوك';
        }
        elseif ($this->branch == '11') {
            $branch_name = 'فرع القصيم';
        }
        elseif ($this->branch == '12') {
            $branch_name = 'فرع ساجر';
        }

        $subject_txt = "تقرير ملخص مبيعات " . $branch_name ."({$this->start_date} إلى {$this->end_date})";

//        return $this->subject('ملخص مبيعات ' . $branch_name)
        return $this->subject($subject_txt)
            ->view('mails.weekly-report-email')
            ->with(['data' => $this->data, 'branch' => $branch_name, 'start_date' => $this->start_date, 'end_date' => $this->end_date, 'visits' => $this->visits]);

//        return $this->view('view.name');
    }
}
