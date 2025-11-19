<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $visit;
    protected $branch_manger_name;
    protected $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($visit, $branch_manger_name, $type)
    {
        //
        $this->visit = $visit;
        $this->branch_manger_name = $branch_manger_name;
        $this->type = $type;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if ($this->type == 'add') {
            $subject_txt = "طلب زيارة جديد";
        }
        elseif ($this->type == 'update') {
            $subject_txt = "تم تعديل الزيارة";
        }
        elseif ($this->type == 'cancel') {
            $subject_txt = "تم إلغاء الزيارة";
        }
        elseif ($this->type == 'approve') {
            $subject_txt = "تمت الموافقة على الزيارة";
        }
        elseif ($this->type == 'reject') {
            $subject_txt = "تم رفض الزيارة";
        }
        elseif ($this->type == 'review') {
            $subject_txt = "ملاحظاتكم تهمنا - تقييم تجربتكم";
        }
        elseif ($this->type == 'reviews-done') {
            $subject_txt = "التقييم جاهز! يمكنك الآن الإطلاع على تقييم الزيارة";
        }


        elseif ($this->type == 'reminder') {
            $subject_txt = "تذكير بالزيارة";
        }
        elseif ($this->type == 'AcceptReminder') {
            $subject_txt = "تذكير بقبول الزيارة";
        }

        return $this->subject($subject_txt)
            ->view('mails.visit-created')
            ->with(['visit' => $this->visit, 'branch_manger_name' => $this->branch_manger_name, 'type' => $this->type]);
    }
}
