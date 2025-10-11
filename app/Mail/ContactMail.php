<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $branch;
    protected $city;
    protected $requests;
    protected $address;
    protected $company_name;
    protected $phone;
    protected $emails =[];
    protected $card_front_pic;
    protected $card_back_pic;
    protected $note;
    protected $contact;



    /**
     * Create a new message instance.
     *
     * @return void
     */
//    public function __construct($name, $phone, $city, $company_name, $address, $note, $requests, $email , $card_front_pic, $card_back_pic)
    public function __construct($contact, $branch, $dep)
    {
        //
        $this->contact = $contact;
        $this->branch = $branch;
        $this->dep = $dep;
//        $this->contact = Contact::find($contact);
//        $this->name = $name;
//        $this->phone = $phone;
//        $this->city = $city;
//        $this->company_name= $company_name;
//        $this->address = $address;
//        $this->requests = $requests;
//        $this->note = $note;
//        $this->email = $email;
//        $this->card_front_pic = $card_front_pic;
//        $this->card_back_pic = $card_back_pic;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->emails= [];
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

        $subject_txt = "جهة اتصال جديدة";

//        return $this->subject('ملخص مبيعات ' . $branch_name)
        return $this->subject($subject_txt)
            ->view('mails.contacts-mail')
            ->with(['contact' => $this->contact]);

//        return $this->view('view.name');
    }
}
