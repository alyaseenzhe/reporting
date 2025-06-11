<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Visit;
use App\Models\VisitEmp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attribute\On;
use Livewire\WithPagination;

class VisitCalendar extends Component
{

    public $visits;
    public $emps;

    protected $listeners = ['addVisit' => 'addVisit', 'updateVisit' => 'updateVisit', 'deleteVisit' => 'deleteVisit', 'approveVisit' => 'approveVisit', 'rejectVisit' => 'rejectVisit'];

    public function mount() {
        $this->loadVisits();
        $this->loadEmps();

//        dd($this->visits->emps_requester);
//        dd($this->visits);
    }

    public function loadVisits() {

//        $this->visits = Visit::select(
//            'visits.id',
//            'visits.title',
//            'visits.requester_id',
//            'visits.start',
//            'visits.end',
//            'visits.reason',
//            'visits.goals',
//            'visits.branch',
//            'visits.recipient_id',
//            'visits.status',
//            'visits.is_deleted',
////            'requester.name as requester_name',
////            'recipient.name as recipient_name'
//        )
//            ->leftJoin('visit_emps', 'visits.id', '=', 'visit_emps.visit_id')
////            ->leftJoin('users as requester', 'visits.requester_id', '=', 'requester.id')
////            ->leftJoin('users as recipient', 'visits.recipient_id', '=', 'recipient.id')
//            ->where('visits.is_deleted', 0)
//            ->where('visit_emps.user_id', Auth::id())
//            ->orderByRaw('CASE WHEN visits.status = 3 THEN 1 ELSE 0 END') // put status 3 at bottom
//            ->orderBy('visits.start', 'asc') // soonest start date first
//            ->get()
//            ->toArray();

        $this->visits = Visit::with(['emps_requester.user', 'emps_recipients.user']) // or any other relationship
        ->whereHas('emps', function ($q) {
            $q->where('user_id', Auth::id());
        })
            ->where('is_deleted', 0)
            ->orderByRaw('CASE WHEN status = 3 THEN 1 ELSE 0 END')
            ->orderBy('start', 'asc')
            ->get([
                'id', 'title', 'requester_id', 'start', 'end',
                'reason', 'goals', 'branch', 'recipient_id',
                'status', 'is_deleted'
            ])
            ->toArray(); // Now it's an array of visits


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
        return view('livewire.visit-calendar')
            ->layout('layouts.dashboard');
    }

    public function addVisit($data) {
//        dd($data);
////        dd($data['start']);
//        $recipient = User::where('sales_dept_code', $data['branch'])
//            ->where('group', 8) // branch manger group
//            ->select('id')->first();

        $visit_data = Visit::create([
            'title' => $data['title'],
//            'requester_id' => Auth::id(),
            'start' => Carbon::parse($data['start'])->format('Y-m-d H:i:s'),
            'end' => Carbon::parse($data['end'])->format('Y-m-d H:i:s'),
            'reason' => $data['reason'],
            'goals' => $data['goals'],
            'branch' => $data['branch'],
//            'recipient_id' => $recipient->id,
            'status' => '0', // pending
        ]);

        if ($visit_data) {
            $records = collect($data['employees'])->map(fn($user_id) => ['visit_id' => $visit_data->id, 'type' => 'recipient', 'user_id' => $user_id ])->toArray();
            $requester_record = ['visit_id' => $visit_data->id, 'type' => 'requester', 'user_id' => Auth::id() ];
            array_push($records, $requester_record);

            DB::table('visit_emps')->insert($records);

            $this->loadVisits();

            $this->emit("visitsLoaded", $this->visits);
        }
    }

    public function updateVisit($event) {
//        dd($event);

        $visit = Visit::find($event['id']);

        if ($visit) {

            $recipient = User::where('sales_dept_code', $event['branch'])
                ->where('group', 8) // branch manger group
                ->select('id')->first();

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

                $this->loadVisits();

                $this->emit("visitsLoaded", $this->visits);
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
            // Emit event to refresh FullCalendar events
            $this->loadVisits();

            $this->emit("visitsLoaded", $this->visits);
        } else {
            // Optional: handle the case if event not found
            session()->flash('error', 'Visit not found.');
        }


    }

    public function approveVisit($visit_record)
    {

        $visit = Visit::findOrFail($visit_record['id']);
        if (Auth::id() !== $visit->recipient_id) {
            abort(403);
        }

        $visit->status = '1';
        $visit->status_notice = $visit_record["status_notice"];
        $visit->save();

        $this->loadVisits();

        $this->emit("visitsLoaded", $this->visits);
    }

    public function rejectVisit($visit_record)
    {

        $visit = Visit::findOrFail($visit_record['id']);
        if (Auth::id() !== $visit->recipient_id) {
            abort(403);
        }

        $visit->status = '2';
        $visit->status_notice = $visit_record["status_notice"];
        $visit->save();

        $this->loadVisits();

        $this->emit("visitsLoaded", $this->visits);
    }
}
