<?php

namespace App\Http\Livewire;
use Illuminate\Support\Facades\Http;
use App\Mail\VisitCreated;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitEmp;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use App\Http\Livewire\Traits\MsGraphAuthTrait;

class ShowVisit extends Component
{
    use MsGraphAuthTrait;

    public $record;
    public $visit_id;
    public $can_recipient_approve;
    public $can_close_visit;
    public $can_rate;
    public $reviews_done;
    public $emps;
    public  $isReviewWritten;
    public  $isMyReview;
    public  $allReviewsDone;
    public  $parsedReview;
    public  $reviews; //
    public  $branches;
    public $parsedReviewRecipient;


    protected $listeners = ['approveVisit' => 'approveVisit', 'rejectVisit' => 'rejectVisit', 'closeVisit' => 'closeVisit', 'review' => 'review', 'updateVisit' => 'updateVisit', 'deleteVisit' => 'deleteVisit'];

    public function mount($id) {
        try {

            $this->record = Visit::findOrFail($id);
            $this->visit_id = $id;
            $this->branches =[
                "0101"=> "فرع الاحساء",
            "0102"=> "فرع جدة",
            "0103"=> "فرع الرياض",
            "0104"=> "فرع وادي الدواسر",
            "0105"=> "فرع الجوف",
            "0106"=> "فرع الدمام",
            "0107"=> "فرع الخرج",
            "0108"=> "فرع نجران",
            "0109"=> "فرع حائل",
            "0110"=> "فرع تبوك",
            "0111"=> "فرع القصيم",
            "0112"=> "فرع ساجر",
            "0201"=> "مزرعة الدالوة",
            "0202"=> "مزرعة الفضول",
            "0203"=> "مزرعة الدلم"
            ];


//            $this->is_recipient = $this->record->is_requester();
////                ->where('user_id', Auth::id())
////                ->exists();
//
//            dd($this->is_recipient);
            $this->reviews = collect(json_decode($this->record->requester_reviews, true));

            $this->isReviewWritten = $this->record->requester_reviews && $this->record->recipient_reviews;
//          $this->isMyReview = $rec_record->user_id == auth()->id();
            $this->allReviewsDone = $this->reviews_done();
//            $this->parsedReview = $this->isReviewWritten ? json_decode($this->record->requester_reviews , true) : null  ;
//            $this->parsedReviewRecipient = $this->isReviewWritten ? json_decode($this->record->recipient_reviews , true) : null  ;

            $this->parsedReview = json_decode($this->record->requester_reviews , true)   ;
            $this->parsedReviewRecipient = json_decode($this->record->recipient_reviews , true)  ;

//            dd($this->parsedReviewRecipient);

            //todo should find out why this condition was exist
//            if ($this->record->is_requester() && $this->record->is_recipient() && Auth::user()->role != 'a') {
//                session()->flash('message', 'هذه الزيارة غير موجودة');
//                return redirect()->route('visit-calender');
//            }


            $this->can_approve();
            $this->can_close();
            $this->can_rate();
            $this->reviews_done();
            $this->loadEmps();

//            $this->is_recipient = $this->record->emps_recipients()
//                ->where('user_id', Auth::id())
//                ->where(function($query) {
//                    $query->whereNull('reviews')
//                        ->orWhere('reviews', '');
//                })
//                ->whereHas('user', function ($query) {
//                    $query->where('group', 8);
//                })
//                ->exists();

//            dd($this->is_recipient);

        } catch (ModelNotFoundException $exception) {

            session()->flash('message', 'هذه الزيارة غير موجودة');
            return redirect()->route('visit.calendar');
        }

    }

    public function loadEmps() {
        $this->emps = User::where('sales_dept_code', '!=', '')
            ->where(function($query) {
                $query->where('group', 7)
                    ->orWhere('group', 8);
            })
            ->select('id', 'sales_dept_code', 'name', 'group')
            ->orderBy('sales_dept_code', 'asc')
            ->get()
            ->groupBy('sales_dept_code')
            ->map(function ($employees) {
                return $employees->map(fn($e) => [
                    'id' => $e->id,
                    'name' => $e->name,
                    'group' => $e->group,
                    'type' => $e->group == 7? "employee" : "manager",
                ])->values(); // ensures zero-based index
            });

    }

    public function render()
    {
        return view('livewire.show-visit')
            ->layout('layouts.dashboard');
    }

    public function approveVisit($visit_record)
    {

        $visit = Visit::findOrFail($this->visit_id);

//        $rec = $visit->emps_recipients()
//            ->where('user_id', Auth::id())
//            ->whereHas('user', function ($query) {
//                $query->where('group', 8);
//            })
//            ->exists();


        if (!$this->can_recipient_approve) {
            abort(403);
        }

        $visit->status = '1';
        $visit->status_notice = $visit_record["status_notice"];
         $visit->approved_by = auth()->user()->id;

        if($visit->save()) {
           $this->connect($visit->id);

            $emails = $visit->emps->pluck('user.email')->filter()->values()->toArray();
            $this->visitMail($visit, $emails, 'approve');

            session()->flash('success', 'تمت الموافقة على الزيارة');
//            return redirect()->route('show.visit', ['id' => $this->visit_id]);
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند الموافقة على الزيارة');
            return redirect()->route('show.visit', ['id' => $this->visit_id]);
        }
    }

    public function rejectVisit($visit_record)
    {
        $visit = Visit::findOrFail($this->visit_id);

        if (!$this->can_recipient_approve) {
            abort(403);
        }

        $visit->status = '2';
        $visit->status_notice = $visit_record["status_notice"];

        if($visit->save()) {

            $emails = $visit->emps_requester->pluck('user.email')->filter()->values()->toArray();
            $this->visitMail($visit, $emails, 'reject');

            session()->flash('success', 'تم رفض الزيارة');
            return redirect()->route('show.visit', ['id' => $this->visit_id]);
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند رفض الزيارة');
            return redirect()->route('show.visit', ['id' => $this->visit_id]);
        }
    }

    public function updateVisit($event) {
//        dd($event);

        $visit = Visit::find($event['id']);

        if ($visit) {

//            $recipient = User::where('sales_dept_code', $event['branch'])
//                ->where('group', 8) // branch manger group
//                ->select('id')->first();

            $visit->title = $event['title'];
            $visit->start = Carbon::parse($event['start'])->format('Y-m-d H:i:s');
            $visit->end = Carbon::parse($event['end'])->format('Y-m-d H:i:s');
//            $visit->end = Carbon::parse($event['end'])->format('Y-m-d H:i:s');
            $visit->reason =  $event['reason'];
            $visit->goals = $event['goals'];
            $visit->branch =  $event['branch'];
            $visit->attendants =  $event['attendants'];
            $visit->status = 0;
//            $visit->recipient_id =  $recipient->id;

//            $visit->save();

            if ($visit->save()) {

                VisitEmp::where('visit_id', $event['id'])->delete();
//
                $records = collect($event['employees'])->map(fn($user_id) => ['visit_id' => $event['id'], 'type' => 'recipient', 'user_id' => $user_id ])->toArray();
                $requester_record = ['visit_id' => $event['id'], 'type' => 'requester', 'user_id' => Auth::id() ];
                array_push($records, $requester_record);
//                array_push( $requester_record);

                DB::table('visit_emps')->insert($records);

                session()->flash('success', 'تم تحديث الزيارة بنجاح');
              //  $branch_manger = $this->branchMangerByVisitId($visit->id);

                $this->visitMail($this->oneVisit($visit->id), null, 'update');
                return redirect()->route('show.visit', ['id' => $event['id']]);

//                $this->loadVisits();

//                $this->emit("visitsLoaded", $this->visits);
            }
        }

//        $this->loadVisits();
//
//        $this->emit("visitsLoaded", $this->visits);
    }

    public function deleteVisit($event) {

        $visit = Visit::find($event['id']);

        if ($visit) {
            $visit->delete_reason =  $event['delete_reason'];
            $visit->is_deleted =  1;
            $visit->status ='4';

            $visit->save();
            $this->visitMail($visit, null, 'cancel');

            session()->flash('success', 'تم الغاء الزيارة بنجاح');

//            $branch_manger = $this->branchMangerByVisitId($visit->id);
            return redirect()->route('visit-calendar');
        } else {
            // Optional: handle the case if event not found
            session()->flash('error', 'Visit not found.');
        }

    }

    public function closeVisit()
    {
        $visit = Visit::findOrFail($this->visit_id);

        if (!$visit->is_requester()) {
            abort(403);
        }

        $visit->status = '3';

        if($visit->save()) {

            session()->flash('success', 'تم إغلاق الزيارة');
            $this->visitMail($visit, null, 'review');

            return redirect()->route('show.visit', ['id' => $this->visit_id]);
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند إغلاق الزيارة');
            return redirect()->route('show.visit', ['id' => $this->visit_id]);
        }
    }

    public function review($data) {


        $visit = Visit::find($this->visit_id);

        $is_requester = $visit->where('requester_id', Auth::id())->count();
//        dd($is_recipient);

            if( $is_requester && $visit->requester_reviews == Null) {
                $visit->requester_reviews = json_encode($data);


            }

            elseif($visit->recipient_reviews == Null){
                $visit->recipient_reviews = json_encode($data);

            }
                if($visit->save())
                {

//            }

//        $visit = VisitEmp::where('visit_id', $this->visit_id)
//            ->where('user_id', Auth::id())
//            ->where(function($query) {
//                $query->whereNull('reviews')
//                    ->orWhere('reviews', '');
//            })
//            ->first();
//
//        if ($visit) {
//
//            $editRecord = VisitEmp::findOrFail($visit->id);
//
//            $editRecord->reviews = json_encode($data);
//
//            if ($visit->recipient_reviews ->save()) {
//
//                $checkReviews = VisitEmp::where('visit_id', $this->visit_id)
//                    ->where(function($query) {
//                        $query->whereNull('reviews')
//                            ->orWhere('reviews', '');
//                    })
//                    ->count();
//
//                if ($checkReviews == 0) {
//                    // send email and whatsapp to tell user that the rating has been finished by all the recipients
////                    dd("تم الانتهاء من جميع التعليقات");
//
////                    $this->visitMail($visit, null, 'reviews-done');
//                    $this->visitMail($this->oneVisit($this->visit_id), null, 'reviews-done');
//
//                }

                session()->flash('success', 'تم تقييم الزيارة');
                return redirect()->route('show.visit', ['id' => $this->visit_id]);

            }
            else {
                session()->flash('error-message', 'حدث خطأ ما عند تقييم الزيارة');
                return redirect()->route('show.visit', ['id' => $this->visit_id]);
            }


    }

    public function can_approve() {

        return $this->can_recipient_approve = Visit::where('visits.id', $this->visit_id)
            ->leftJoin('visit_emps', 'visits.id', 'visit_emps.visit_id')
            ->leftJoin('users', 'users.id', 'visit_emps.user_id')
            ->where('visit_emps.type', 'recipient')
            ->where('visit_emps.user_id', Auth::id())
//            ->where('users.group', '8')
            ->where('visits.status', '0')
            ->where('visits.is_deleted', '0')
            ->exists();

    }

    public function can_close() {

        return $this->can_close_visit = Visit::where('visits.id', $this->visit_id)
            ->leftJoin('visit_emps', 'visits.id', 'visit_emps.visit_id')
            ->where('visit_emps.type', 'requester')
            ->where('visit_emps.user_id', Auth::id())
            ->where('visits.status', '1')
            ->where('visits.is_deleted', '0')
            ->exists();

    }

    public function can_rate() {

        return  $this->can_rate = Visit::where('visits.id', $this->visit_id)
            ->leftJoin('visit_emps', 'visits.id', 'visit_emps.visit_id')
            ->where('visit_emps.user_id', Auth::id())
            ->where('visits.status', '3')
            ->where('visits.is_deleted', '0')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('visit_emps.type', 'requester')
                        ->whereNull('requester_reviews');
                })
                    ->orWhere(function ($q) {
                        $q->where('visit_emps.type', 'recipient')
                            ->whereNull('recipient_reviews');
                    });
            })
            ->exists();
//        return $this->can_rate = Visit::where('visits.id', $this->visit_id)
//            ->leftJoin('visit_emps', 'visits.id', 'visit_emps.visit_id')
//            ->where('visit_emps.user_id', Auth::id())
//            ->where('visits.status', '3')
//            ->where('visits.is_deleted', '0')
//            ->where(function($query) {
//                $query->whereNull('reviews')
//                    ->orWhere('reviews', '');
//            })
//            ->exists();
    }

    public function reviews_done() {
        $check = VisitEmp::where('visit_id', $this->visit_id)
            ->where(function($query) {
                $query->whereNull('reviews')
                    ->orWhere('reviews', '');
            })
            ->count();

        $this->reviews_done = $check == 0;

        return $this->reviews_done;
    }

    public function visitMail($visit_record, $branch_manger, $type)
    {
        $res_email = VisitEmp::join('users', 'visit_emps.user_id', 'users.id')
            ->where('visit_emps.visit_id', $visit_record->id)
            ->select('users.email', 'users.name','users.id')

            ->pluck('users.email')->toArray();

        if (empty($res_email)) {
            return back()->with('error', 'لم يتم العثور على إيميلات مناسبة');
        }

        $recipientEmail = $visit_record->emps
            ->where('type', 'requester')
            ->pluck('user.email')
            ->unique()
            ->first();

        $allEmails = array_merge($res_email, [$recipientEmail]);

        Mail::to($allEmails)->queue(new VisitCreated($visit_record, null, $type));
    }

    public function oneVisit($id) {

        $record = Visit::find($id);

        return $record;
    }


}
