<?php

namespace App\Http\Livewire;

use App\Mail\ContactMail;
use Livewire\Component;
use App\Models\Contact;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Mail;


class Contacts extends Component
{
    use WithFileUploads;
//    protected $listeners = ['savePhoto'];

    public function render()
    {
        return view('livewire.site.contacts')
            ->layout('layouts.test');
    }

    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $interests = [];
    public $city ;
    public $filename;
    public $address;
    public $company_name;
    public $card_front_pic;
    public $card_back_pic;
    public $requests;
    public $note;
//    public $branch;


    protected $rules = [
        'phone' => 'required',
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'nullable',
        'company_name' => 'nullable',
        'city' => 'required',
        'address' => 'nullable',
        'card_front_pic' => 'nullable',
        'card_back_pic' => 'nullable',
        'requests' => 'required',
        'note' => 'nullable',
        'interests' => 'nullable',


    ];

    protected $messages = [
        'first_name.required' => 'حقل الاسم مطلوب',
        'last_name.required' => 'حقل الاسم مطلوب',
        'phone.required' => 'حقل رقم الجوال مطلوب',
//        'email.unique' => 'هذا البريد الإلكتروني موجود مسبقاً',
        'city.required' => 'حقل المدينة مطلوب',
        'requests.required' => 'حقل المتطلبات مطلوب',


    ];

    public function create()
    {


        $this->validate();

        if (isset($this->card_front_pic)) {
            $path1 = $this->card_front_pic->store('uploads', 'public');
        }
        if (isset($this->card_back_pic)) {
            $path2 = $this->card_back_pic->store('uploads', 'public');
        }

        $contact = Contact::create([
            'name' => $this->first_name.' '.$this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'interests' => json_encode($this->interests),
            'city' => $this->city,
            'address' => $this->address,
            'company_name' => $this->company_name,

            'card_front_pic' => isset($path1) ? $path1 : null,
            'card_back_pic' => isset($path2) ? $path2 : null,
            'requests' => $this->requests,
            'note' => $this->note,
//            'branch' => $this->branch,
        ]);

        $interests = json_decode($contact->interests);

        $emails = config('emails');

        //get all branches emails
        $branchEmail = $emails['branches'][$contact['city']] ?? null;

        // get all departments emails
        $departmentEmails = [];
        foreach ($interests as $dep) {
            if (isset($emails['departments'][$dep])) {
                $departmentEmails[] = $emails['departments'][$dep];
            }
        }

        // merge emails
        $recipients = array_filter(array_merge([$branchEmail], $departmentEmails));

        if (empty($recipients)) {
            return back()->with('error', 'لم يتم العثور على إيميلات مناسبة');
        }

        // send one email to all


        if ($contact) {
            session()->flash('success', 'تم إنشاء جهة اتصال بنجاح');
          //  Mail::to('basil.alrashed@alyaseenagri.com')->queue(new ContactMail($contact, $this->city, $this->interests));
            Mail::to($recipients)->queue(new ContactMail($contact, $contact['branch'], $contact['interests']));

            return redirect('thanks')->with('success', 'تم الإرسال بنجاح');


        } else {
            session()->flash('error-message', 'حدث خطأ ما عند إنشاء المستخدم');
            return redirect()->back();
        }

    }


//    public function savePhoto($imageData)
//    {
//        // Remove the base64 prefix
//        $image = str_replace('data:image/png;base64,', '', $imageData);
//        $image = base64_decode($image);
//
//        // Save to storage
//        $this->filename = 'photo_' . time() . '.png';
//        Storage::disk('public')->put('photos/' . $this->filename, $image);
//
//        session()->flash('message', 'Photo saved successfully!');
//    }


}
