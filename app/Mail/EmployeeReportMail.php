<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeReportMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject_txt = "تقرير اعمال " . $this->data[0]->user->name ."({$this->data[0]->start_of_week} إلى {$this->data[0]->end_of_week})";

        return $this->subject($subject_txt)
            ->view('mails.employee-report-email')
            ->with(['data' => $this->data, 'employee' => $this->data[0]->user->name, 'start_date' => $this->data[0]->start_of_week, 'end_date' => $this->data[0]->end_of_week]);

//        return $this->view('view.name');
    }
}
