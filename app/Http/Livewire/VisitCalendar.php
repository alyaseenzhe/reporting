<?php

namespace App\Http\Livewire;

use App\Mail\VisitCreated;
use App\Mail\WeeklyReport;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitEmp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attribute\On;
use Livewire\WithPagination;

class VisitCalendar extends Component
{

    public $visits;
    public $emps;

    public $can_approve;
    public $showAllVisits;

//    protected $wati;


    protected $listeners = ['addVisit' => 'addVisit', 'updateVisit' => 'updateVisit', 'deleteVisit' => 'deleteVisit', 'approveVisit' => 'approveVisit', 'rejectVisit' => 'rejectVisit'];

    public function mount() {
        $this->loadVisits();
        $this->loadEmps();
        $this->approve();

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

        $this->showAllVisits = Visit::with('requester')->with('emps')->where('status' ,'!=', '4')->get();

        $this->visits = Visit::with(['emps_requester.user', 'emps_recipients.user']) // or any other relationship
        ->whereHas('emps', function ($q) {
            $q->where('user_id', Auth::id());
        })
//            ->where('is_deleted', 0)
            ->orderByRaw('CASE WHEN status = 3 THEN 1 ELSE 0 END')
            ->orderBy('start', 'desc')
            ->get([
                'id', 'title', 'requester_id', 'start', 'end',
                'reason', 'goals', 'extra_services', 'branch', 'recipient_id',
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
//        $employees = User::where('group', 8)->orWhere('group', 4)
//        ->whereJsonContains('branches', $data['branch'])->pluck('id','name')->toArray();

        $employees = [
            '0101'=> ['49','50','51'], //alahsaa branch
            '0102' => ['28','33'], // jeddah
            '0103' => ['20','54', '52'], //riyadh
            '0104'=> ['55'], // wadi adwasir
            '0105' => ['34','27','41'], //jouf
            '0106' => ['58','29'], //dammam
            '0107'=> ['36', '35'],   //kharj
            '0108' => ['40','60','26'], //najran
            '0109' => ['61','62'],   //hail
            '0110' => ['30','63','44'], //tabouk
            '0111' => ['57','22','86'], //qaseem
            '0112' => ['47','46'], //sajer
        ];
//        dd($employees[$data['branch']]?? []);


        $visit_data = Visit::create([
            'title' => $data['title'],
            'requester_id' => Auth::id(),
            'start' => Carbon::parse($data['start'])->format('Y-m-d H:i:s'),
            'end' => Carbon::parse($data['end'])->format('Y-m-d H:i:s'),
            'reason' => $data['reason'],
            'goals' => $data['goals'],
            'extra_services' => json_encode($data['extra_services']),
            'branch' => $data['branch'],
            'attendants' => $data['attendants'],
//            'recipient_id' => $recipient->id,
            'status' => '0', // pending
        ]);

        if ($visit_data) {
//            $records = collect($data['employees'])->map(fn($user_id) => ['visit_id' => $visit_data->id, 'type' => 'recipient', 'user_id' => $user_id ])->toArray();
            $records = collect($employees[$visit_data->branch])
                ->flatMap(fn($user_ids) =>
                collect($user_ids)->map(fn($id) => [
                    'visit_id' => $visit_data->id,
                    'type' => 'recipient',
                    'user_id' => $id,
                ])
                )
                ->values()
                ->toArray();
            $requester_record = ['visit_id' => $visit_data->id, 'type' => 'requester', 'user_id' => Auth::id() ];
            array_push($records, $requester_record);

            DB::table('visit_emps')->insert($records);

            $this->loadVisits();

            $this->emit("visitsLoaded", $this->visits);

            $branch_manger = $this->branchMangerByVisitId($visit_data->id);
/*
            $wati = new \App\Services\WatiService();
            $d = $wati->sendTemplateMessages('visit_add_it', [
                ['phone_number' => '966567133644', 'parameters' => ['Basil']],
            ]);
            dd($d);
*/
//            $this->wati();
            $this->visitMail($this->oneVisit($visit_data->id), $branch_manger, 'add');
        }
    }
//
//    public function updateVisit($event) {
////        dd($event);
//
//        $visit = Visit::find($event['id']);
//
//        if ($visit) {
//
//            $recipient = User::where('sales_dept_code', $event['branch'])
//                ->where('group', 8) // branch manger group
//                ->select('id')->first();
//
//            $visit->title = $event['title'];
//            $visit->start = Carbon::parse($event['start'])->format('Y-m-d H:i:s');
////            $visit->end = Carbon::parse($event['end'])->format('Y-m-d H:i:s');
//            $visit->reason =  $event['reason'];
//            $visit->goals = $event['goals'];
//            $visit->extra_services = json_encode($event['extra_services']);
////            $visit->branch =  $event['branch']; // no need to change the branch
//
////            $visit->recipient_id =  $recipient->id;
//
////            $visit->save();
//
//            if ($visit->save()) {
//
//                VisitEmp::where('visit_id', $event['id'])->delete();
//
//                $records = collect($event['employees'])->map(fn($user_id) => ['visit_id' => $event['id'], 'type' => 'recipient', 'user_id' => $user_id ])->toArray();
//                $requester_record = ['visit_id' => $event['id'], 'type' => 'requester', 'user_id' => Auth::id() ];
//                array_push($records, $requester_record);
//
//                DB::table('visit_emps')->insert($records);
//
//                $this->loadVisits();
//
//                $this->emit("visitsLoaded", $this->visits);
//            }
//        }
//
////        $this->loadVisits();
////
////        $this->emit("visitsLoaded", $this->visits);
//    }
//
//    public function deleteVisit($event) {
//
//        $visit = Visit::find($event['id']);
//
//        if ($visit) {
//            $visit->delete_reason =  $event['delete_reason'];
//            $visit->is_deleted =  1;
////            $visit->status = 4;
//
//            $visit->save();
//            // Emit event to refresh FullCalendar events
//            $this->loadVisits();
//
//            $this->emit("visitsLoaded", $this->visits);
//
//            if ($visit->status == 1) {
//                $this->visitMail($visit, $visit->emps(), 'delete');
//            }
//            else if($visit->status == 0) {
//
//                $branch_manger = $this->branchMangerByVisitId($visit->id);
//                $this->visitMail($this->oneVisit($visit->id), $branch_manger, 'delete');
//            }
//
//        } else {
//            // Optional: handle the case if event not found
//            session()->flash('error', 'Visit not found.');
//        }
//
//
//    }

    public function approveVisit($visit_record)
    {

        $visit = Visit::findOrFail($visit_record['id']);
        if (!$this->can_approve($visit_record['id'])) {
            abort(403);
        }

        $visit->status = '1';
        $visit->status_notice = $visit_record["status_notice"];
        $visit->save();

        $this->loadVisits();

        $this->emit("visitsLoaded", $this->visits);


        $this->visitMail($visit, $visit->emps(), 'approve');
    }

    public function rejectVisit($visit_record)
    {


        $visit = Visit::findOrFail($visit_record['id']);

        if (!$this->can_approve($visit_record['id'])) {
            abort(403);
        }

        $visit->status = '2';
        $visit->status_notice = $visit_record["status_notice"];

        if($visit->save()) {
            $emails = $visit->emps_requester->pluck('user.email')->filter()->values()->toArray();
            $this->visitMail($visit, $emails, 'reject');
        }

        $this->loadVisits();

        $this->emit("visitsLoaded", $this->visits);
    }

    public function visitMail($visit_record, $branch_manger, $type) {
//        $res_email = VisitEmp::join('users', 'visit_emps.user_id', 'users.id')
//            ->where('users.group', '8') // branch manager
//            ->where('visit_emps.visit_id', $visit_record->id)
//            ->select('users.email', 'users.name')
//            ->first();
//        dd($this->branchMangerByVisitId($visit_record->id));
//        dd($res_email);

        $emails = config('emails');

        $branchEmail = $emails['branches_employees'][$visit_record->branch] ?? null;
      //dd($visit_record->branch);

        if (empty(   $branchEmail)) {
            return back()->with('error', 'لم يتم العثور على إيميلات مناسبة');
        }

        $recipientEmail = $visit_record->emps
            ->where('type', 'requester')
            ->pluck('user.email')
            ->unique()
            ->first();


        // the email must be this $branch_manger->email
        Mail::to([$branchEmail,$recipientEmail])->queue(new VisitCreated($visit_record, null, $type));
    }

    public function oneVisit($id) {

        $record = Visit::find($id);

        return $record;
    }

    public function branchMangerByVisitId($visitId) {
        $branch_manger = VisitEmp::join('users', 'visit_emps.user_id', '=', 'users.id')
            ->where('users.group', 8) // or ->where('users.`group`', 8) if error occurs
            ->where('visit_emps.visit_id', $visitId)
            ->select('users.email', 'users.name')
            ->first();

        return $branch_manger;
    }

    public function can_approve($visit_id) {

        $x = Visit::where('visits.id', $visit_id)
            ->leftJoin('visit_emps', 'visits.id', 'visit_emps.visit_id')
            ->leftJoin('users', 'users.id', 'visit_emps.user_id')
            ->where('visit_emps.type', 'recipient')
            ->where('visit_emps.user_id', Auth::id())
//            ->where('users.group', '8')
            ->where('visits.status', '0')
            ->where('visits.is_deleted', '0')
            ->exists();

        return $x;

    }

     public function approve(){
         $this->can_approve = auth()->user()->group == 8 || 7;
         return $this->can_approve;
     }

    public function wati() {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://live-mt-server.wati.io/356035/api/v2/sendTemplateMessage?whatsappNumber=+966567133644',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
    "template_name": "visit_add",
    "broadcast_name": "visit_add",
    "parameters": []
}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJjNjVjOWVjZC1lZjE4LTQ2NDEtODNmNS05ZDQ4NDM1MTk1ZGUiLCJ1bmlxdWVfbmFtZSI6ImJhc2lsLmFscmFzaGVkQGFseWFzZWVuYWdyaS5jb20iLCJuYW1laWQiOiJiYXNpbC5hbHJhc2hlZEBhbHlhc2VlbmFncmkuY29tIiwiZW1haWwiOiJiYXNpbC5hbHJhc2hlZEBhbHlhc2VlbmFncmkuY29tIiwiYXV0aF90aW1lIjoiMDYvMjIvMjAyNSAwNTozNjowNSIsInRlbmFudF9pZCI6IjM1NjAzNSIsImRiX25hbWUiOiJtdC1wcm9kLVRlbmFudHMiLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.RS4DOw7ha8fNhHUExL6pFknR7b7CLQQnVobNpAMhnjY',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

}
