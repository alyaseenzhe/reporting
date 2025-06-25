<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Visit;
use App\Models\VisitEmp;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowVisit extends Component
{
    public $record;
    public $visit_id;
    public $can_recipient_approve;
    public $can_close_visit;
    public $can_rate;
    public $reviews_done;
    public $emps;

    protected $listeners = ['approveVisit' => 'approveVisit', 'rejectVisit' => 'rejectVisit', 'closeVisit' => 'closeVisit', 'review' => 'review', 'updateVisit' => 'updateVisit', 'deleteVisit' => 'deleteVisit'];

    public function mount($id) {
        try {

            $this->record = Visit::findOrFail($id);
            $this->visit_id = $id;

//            $this->is_recipient = $this->record->is_requester();
////                ->where('user_id', Auth::id())
////                ->exists();
//
//            dd($this->is_recipient);

            if ($this->record->is_requester() && $this->record->is_recipient() && Auth::user()->role != 'a') {
                session()->flash('message', 'هذه الزيارة غير موجودة');
                return redirect()->route('list.daily-reports');
            }

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
            return redirect()->route('list.daily-reports');
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

        if($visit->save()) {
            session()->flash('success', 'تمت الموافقة على الزيارة');
            return redirect()->route('show.visit', ['id' => $this->visit_id]);
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
//            $visit->end = Carbon::parse($event['end'])->format('Y-m-d H:i:s');
            $visit->reason =  $event['reason'];
            $visit->goals = $event['goals'];
            $visit->branch =  $event['branch'];
//            $visit->recipient_id =  $recipient->id;

//            $visit->save();

            if ($visit->save()) {

                VisitEmp::where('visit_id', $event['id'])->delete();

                $records = collect($event['employees'])->map(fn($user_id) => ['visit_id' => $event['id'], 'type' => 'recipient', 'user_id' => $user_id ])->toArray();
                $requester_record = ['visit_id' => $event['id'], 'type' => 'requester', 'user_id' => Auth::id() ];
                array_push($records, $requester_record);

                DB::table('visit_emps')->insert($records);

                session()->flash('success', 'تم تحديث الزيارة بنجاح');
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
            $visit->save();

            session()->flash('success', 'تم حذف الزيارة بنجاح');
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
            return redirect()->route('show.visit', ['id' => $this->visit_id]);
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند إغلاق الزيارة');
            return redirect()->route('show.visit', ['id' => $this->visit_id]);
        }
    }

    public function review($data) {

        $visit = VisitEmp::where('visit_id', $this->visit_id)
            ->where('user_id', Auth::id())
            ->where(function($query) {
                $query->whereNull('reviews')
                    ->orWhere('reviews', '');
            })
            ->first();

        if ($visit) {

            $editRecord = VisitEmp::findOrFail($visit->id);

            $editRecord->reviews = json_encode($data);

            if ($editRecord->save()) {

                $checkReviews = VisitEmp::where('visit_id', $this->visit_id)
                    ->where(function($query) {
                        $query->whereNull('reviews')
                            ->orWhere('reviews', '');
                    })
                    ->count();

                if ($checkReviews == 0) {
                    // send email and whatsapp to tell user that the rating has been finished by all the recipients
                    dd("تم الانتهاء من جميع التعليقات");

                }

                session()->flash('success', 'تم تقييم الزيارة');
                return redirect()->route('show.visit', ['id' => $this->visit_id]);

            }
            else {
                session()->flash('error-message', 'حدث خطأ ما عند تقييم الزيارة');
                return redirect()->route('show.visit', ['id' => $this->visit_id]);
            }
        }

    }

    public function can_approve() {

        return $this->can_recipient_approve = Visit::where('visits.id', $this->visit_id)
            ->leftJoin('visit_emps', 'visits.id', 'visit_emps.visit_id')
            ->leftJoin('users', 'users.id', 'visit_emps.user_id')
            ->where('visit_emps.type', 'recipient')
            ->where('visit_emps.user_id', Auth::id())
            ->where('users.group', '8')
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

        return $this->can_rate = Visit::where('visits.id', $this->visit_id)
            ->leftJoin('visit_emps', 'visits.id', 'visit_emps.visit_id')
            ->where('visit_emps.user_id', Auth::id())
            ->where('visits.status', '3')
            ->where('visits.is_deleted', '0')
            ->where(function($query) {
                $query->whereNull('reviews')
                    ->orWhere('reviews', '');
            })
            ->exists();
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
}
