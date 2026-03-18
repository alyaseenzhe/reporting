<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Traits\MsGraphAuthTrait;
use App\Mail\VisitCreated;
use App\Mail\WeeklyReport;
use App\Models\MsToken;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitEmp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attribute\On;
use Livewire\WithPagination;
use Dcblogdev\MsGraph\Facades\MsGraph;

class VisitCalendar extends Component
{
    use MsGraphAuthTrait;
    public $visits;
    public $emps;

    public $can_approve;
    public $showAllVisits;
    public $branch;
    public $status;
    public $start;
    public $end;
    public $user;
    public $uniqueRequesters;
    public $activePanel = 'calendar';
    public $calendarVisit;
    public $canViewAll;
    public $can_close_visit;
    public $can_recipient_approve;
    public $can_rate;
    public  $branches;
    public $visit_id;
    public $reviews_done;
    public  $isReviewWritten;
    public  $isMyReview;
    public  $allReviewsDone;
    public  $parsedReview;
    public  $reviews; //

//    protected $wati;


    protected $listeners = ['addVisit' => 'addVisit', 'updateVisit' => 'updateVisit', 'deleteVisit' => 'deleteVisit', 'approveVisit' => 'approveVisit', 'rejectVisit' => 'rejectVisit', 'closeVisit' => 'closeVisit', 'review' => 'review'];

    public function mount()
    {

        $this->loadVisits();
        $this->loadEmps();
        $this->approve();
//        $this->uniqueRequesters = collect($this->visits)

        $this->can_approve($this->visit_id);
        $this->can_close();
        $this->can_rate();
        $this->reviews_done();

        $user = Auth::user();
        $this->canViewAll = (
            ($user->user_group->visits
                && in_array('view-all-visits', json_decode($user->user_group->visits)))
//                && $user->user->g)
            || $user->role == 'a'
        );


//        if($this->canViewAll) {
//            $this->uniqueRequesters = collect($this->calendarVisit)
//                ->flatMap(function ($visit) {
//                    return $visit['emps_requester'];
//                })
//                ->unique(fn($r) => $r['user']['id']);
//        }
        if($this->canViewAll) {
            $this->uniqueRequesters = Visit::with([
                'requester',
                'emps',
                'emps_recipients.user',
                'emps_requester.user',
            ])
                ->get()
                ->flatMap(function ($visit) {
                    return $visit['emps_requester'];
                })
                ->unique(fn($r) => $r['user']['id'])
                ->values();
        }
            else{
                $this->uniqueRequesters = collect($this->visits)
                    ->flatMap(function ($visit) {
                        return $visit['emps_requester'];
                    })
                    ->unique(fn($r) => $r['user']['id']);

        }

        // If the logged-in user is one of the requesters → select them
        $authId = auth()->user()->id;

        // Check if the authenticated user exists in the requester list
        if (collect($this->uniqueRequesters)->pluck('user.id')->contains($authId) && !(Auth::user()->user_group->visits
                && in_array('view-all-visits', json_decode(Auth::user()->user_group->visits)) || Auth::user()->role == 'a') ) {

            $this->user = $authId;
        } else {
            $this->user = 'all';   // fallback
        }

//        dd($this->visits->emps_requester);
//        dd($this->visits);

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



    }

    public function booted() {


        if (Auth::user()->is_active == '0'){
            return redirect()->route('non-active-user');
        }

        if ((Auth::user()->user_group->visits && in_array('enter-visit', json_decode(Auth::user()->user_group->visits))) || Auth::user()->role == 'a'){
            return;
        } else {
            return redirect()->route('dashboard');
        }
    }
    public function loadVisits($ExcludeCanceledVisit = Null)
    {
        $this->activePanel = 'calendar';
        $this->dispatchBrowserEvent('activePanel');
        $user = Auth::user();
//        $canViewAll = (
//            ($user->user_group->visits
//                && in_array('view-all-visits', json_decode($user->user_group->visits)))
////                && $user->user->g)
//            || $user->role == 'a'
//        );


//        $this->showAllVisits = Visit::with('requester')->with('emps')->where('status', '0')->orWhere('status', '1')->get();
        $this->calendarVisit = Visit::with([
            'requester',
            'emps',
            'emps_recipients.user',
            'emps_requester.user',
        ])
            ->where(function ($q) {
                $q->where('status', 0)
                    ->orWhere('status', 1);
            })
            ->when( auth()->user()->group == 7 ||auth()->user()->group == 8||auth()->user()->group == 12, function ($query) use ($user) {
                $query->whereHas('emps', fn ($q) => $q->where('user_id', $user->id));
            })
            ->get();

//            $this->visits = Visit::with(  [ 'requester',
        $query = Visit::with(  [ 'requester',
            'emps',
            'emps_recipients.user',
            'emps_requester.user', ])
//                ['emps_requester.user', 'emps_recipients.user']) // or any other relationship
//            ->with('requester')
//                ->with('emps')
            ->when(!$this->canViewAll, function ($query) use ($user) {
                // Limit to only visits that belong to this user
                $query->whereHas('emps', fn($q) => $q->where('user_id', $user->id));
            })
//                ->whereHas('emps', function ($q) {
//                    $q->where('user_id', Auth::id());
//                })
//            ->where('is_deleted', 0)


            ->orderByRaw('CASE WHEN status = 3 THEN 1 ELSE 0 END')
            ->orderBy('created_at', 'desc')
            ->get([
                'id', 'title', 'requester_id', 'start', 'end',
//                    'reason', 'goals', 'extra_services', 'branch', 'recipient_id',
                'reason', 'goals', 'branch', 'recipient_id',
                'status', 'is_deleted', 'created_at'
            ]);
//->lazy()
//                ->toArray(); // Now it's an array of visits
//
//        if($this->activePanel == 'calendar'){
//
//            $query = $query->where('status' != '4');
//            dd($query);
//        }

        $this->visits = $query->lazy()->toArray();


//        dd($this->visits);
    }

    public function loadEmps()
    {
        $this->emps = User::where('sales_dept_code', '!=', '')
            ->where(function ($query) {
                $query->where('group', 7)
                    ->orWhere('group', 8)
                    ->orWhere('group', 12);
            })
            ->where('is_active', 1)
            ->select('id', 'sales_dept_code', 'name', 'group')
            ->orderBy('sales_dept_code', 'asc')
            ->get()
            ->groupBy('sales_dept_code')
            ->map(function ($employees) {
                return $employees->map(fn($e) => [
                    'id' => $e->id,
                    'name' => $e->name,
                    'group' => $e->group,
                    'type' => $e->group == 7 ? "employee" : "manager",
                ])->values(); // ensures zero-based index
            });

    }

    public function render()
    {
        return view('livewire.visit-calendar')
            ->layout('layouts.dashboard');
    }

    public function addVisit($data)
    {
//        dd($data);
////        dd($data['start']);
//        $recipient = User::where('sales_dept_code', $data['branch'])
//            ->where('group', 8) // branch manger group
//            ->select('id')->first();

//        $employees = User::where('group', 8)->orWhere('group', 4)
//        ->whereJsonContains('branches', $data['branch'])->pluck('id','name')->toArray();
//        $employees = User::where('sales_dept_code', $data['branch'])
//            ->where('group', 8)->orWhere('group', 7)
//        ->pluck('id','name')->toArray();
        $employees = User::where('sales_dept_code', $data['branch'])
            ->where(function ($q) {
                $q->where('group', 8)
                    ->orWhere('group', 7)
                    ->orWhere('group', 12);
            })
            ->pluck('id', 'name')
            ->toArray();

        //
//        $employees = [
//            '0101' => ['49', '50', '51','48'], //alahsaa branch
//            '0102' => ['28', '33'], // jeddah
//            '0103' => ['20', '54', '52'], //riyadh
//            '0104' => ['55'], // wadi adwasir
//            '0105' => ['34', '27', '41'], //jouf
//            '0106' => ['58', '29'], //dammam
//            '0107' => ['36', '35'],   //kharj
//            '0108' => ['40', '60', '26'], //najran
//            '0109' => ['61', '62'],   //hail
//            '0110' => ['30', '63', '44'], //tabouk
//            '0111' => ['57', '22', '86'], //qaseem
//            '0112' => ['47', '46'], //sajer
//        ];
//        dd($employees[$data['branch']]?? []);


        $start = Carbon::parse($data['start'])->format('Y-m-d H:i:s');
        $end = (Carbon::parse($data['end']?? null))->format('Y-m-d H:i:s');
//        $extra_services = json_encode($data['extra_services']);

        $visit_data = Visit::create([
            'title' => $data['title'],
            'requester_id' => Auth::id(),

            'start' => $start,
            'end' => $end,
            'reason' => $data['reason'],
            'goals' => $data['goals'],
//            'extra_services' => $extra_services,
            'branch' => $data['branch'],
            'attendants' => $data['attendants'],
//            'recipient_id' => $recipient->id,
            'status' => '0', // pending
        ]);

        if ($visit_data) {
            if($data['employees'][0] == 'all'){
//                            $records = collect($employees[$visit_data->branch])
                $records = collect($employees)
                    ->flatMap(fn($user_ids) => collect($user_ids)->map(fn($id) => [
                        'visit_id' => $visit_data->id,
                        'type' => 'recipient',
                        'user_id' => $id,
                    ])
                    )
                    ->values()
                    ->toArray();
            }
            else {
                $records = collect($data['employees'])->map(fn($user_id) => ['visit_id' => $visit_data->id, 'type' => 'recipient', 'user_id' => $user_id])->toArray();
            }
            $requester_record = ['visit_id' => $visit_data->id, 'type' => 'requester', 'user_id' => Auth::id()];
            array_push($records, $requester_record);

            DB::table('visit_emps')->insert($records);

            $this->activePanel ='calendar';
            $this->loadVisits();


            $this->emit("visitsLoaded", $this->calendarVisit);

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


    public function checkDuplicate($branch, $date)
    {
        return \App\Models\Visit::where('branch', $branch)
            ->whereDate('start', $date)
            ->whereIn('status', ['0', '1'])
            ->exists();
    }



    public function visitMail($visit_record, $branch_manger, $type)
    {
        $res_email = VisitEmp::join('users', 'visit_emps.user_id', 'users.id')
//            ->where('users.group', '8') // branch manager
            ->where('visit_emps.visit_id', $visit_record->id)
            ->select('users.email', 'users.name','users.id')
            //  ->first();
            ->pluck('users.email')->toArray();
        // dd($this->branchMangerByVisitId($visit_record->id));


        //  $emails = config('emails');
        //  dd($emails);

        //   $branchEmail = $emails['branches_employees'][$visit_record->branch] ?? null;


//        if (empty($branchEmail)) {
        if (empty($res_email)) {
            return back()->with('error', 'لم يتم العثور على إيميلات مناسبة');
        }

        $recipientEmail = $visit_record->emps
            ->where('type', 'requester')
            ->pluck('user.email')
            ->unique()
            ->first();

        $allEmails = array_merge($res_email, [$recipientEmail]);

        // the email must be this $branch_manger->email
//        Mail::to([$branchEmail, $recipientEmail])->queue(new VisitCreated($visit_record, null, $type));
        Mail::to($allEmails)->queue(new VisitCreated($visit_record, null, $type));
    }

    public function oneVisit($id)
    {

        $record = Visit::find($id);

        return $record;
    }

    public function branchMangerByVisitId($visitId)
    {
        $branch_manger = VisitEmp::join('users', 'visit_emps.user_id', '=', 'users.id')
            ->where('users.group', 8) // or ->where('users.`group`', 8) if error occurs
            ->where('visit_emps.visit_id', $visitId)
            ->select('users.email', 'users.name')
            ->first();

        return $branch_manger;
    }

//    public function can_approve($visit_id)
//    {
//
//        $x = Visit::where('visits.id', $visit_id)
//            ->leftJoin('visit_emps', 'visits.id', 'visit_emps.visit_id')
//            ->leftJoin('users', 'users.id', 'visit_emps.user_id')
//            ->where('visit_emps.type', 'recipient')
//            ->where('visit_emps.user_id', Auth::id())
////            ->where('users.group', '8')
//            ->where('visits.status', '0')
//            ->where('visits.is_deleted', '0')
//            ->exists();
//
//        return $x;
//
//    }

    public function approve()
    {
        $this->can_approve = auth()->user()->group == 8 || 7;
        return $this->can_approve;
    }

    public function wati()
    {


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
    public function search() {

        $user = auth()->user();
        $this->visits = Visit::orderBy('created_at', 'DESC')->get();


        $query = Visit::with(['emps_requester.user', 'emps_recipients.user'])->when(!$this->canViewAll, function ($query) use ($user) {
            $query->whereHas('emps', fn ($q) => $q->where('user_id', $user->id));
        });

        if (auth()->check()){
            $query->when(
                VisitEmp::where('user_id', auth()->id())
                    ->where('type', 'requester') // adjust if you have type column
                    ->exists(),
                function ($q) {
                    $q->whereHas('emps_requester', fn($sub) => $sub->where('user_id', auth()->id())
                    );
                }
            )


                ->orderBy('created_at', 'DESC');
        }

        if($this->user !== 'all') {

            $query->whereHas('emps_requester.user', function ($q) {
                $q->where('id', 'LIKE', $this->user);
            });
        }
        if($this->branch !== 'all') {
            $query->where("branch", "LIKE", $this->branch);

        }

        if($this->status !== 'all') {
            $query->where("status", "LIKE", $this->status);
        }
        // Filter by date range if both start and end exist
        if ($this->start && $this->end) {
            $query->whereBetween('start', [$this->start, $this->end]);
        }

        // Execute query
        $this->visits = $query->get();


//

        $this->activePanel = 'list';
        $this->dispatchBrowserEvent('activePanel');



//        $this->emit('finished');

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

//        return  $this->can_rate = Visit::where('visits.id', $this->visit_id)
//            ->leftJoin('visit_emps', 'visits.id', 'visit_emps.visit_id')
//            ->where('visit_emps.user_id', Auth::id())
//            ->where('visits.status', '3')
//            ->where('visits.is_deleted', '0')
//            ->where(function ($query) {
//                $query->where(function ($q) {
//                    $q->where('visit_emps.type', 'requester')
//                        ->whereNull('requester_reviews');
//                })
//                    ->orWhere(function ($q) {
//                        $q->where('visit_emps.type', 'recipient')
//                            ->whereNull('recipient_reviews');
//                    });
//            })
//            ->exists();
        return $this->can_rate = Visit::where('visits.id', $this->visit_id)
            ->leftJoin('visit_emps', 'visits.id', 'visit_emps.visit_id')
            ->where('visit_emps.user_id', Auth::id())
            ->where(function($query) {
                $query->where('visits.status', '3')
                    ->orWhere('visits.status','5');
            })

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

    public function updateVisit($event) {
//
        $visit = Visit::find($event['id']);
        if(isset($visit->ms_event_id)){
            $this->deleteEvent($visit->ms_event_id, $visit->id);
        }
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

            $visit->save();

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

    public function deleteEvent($event, $visitId)
    {
        $token = MsToken::where('visit_id', $visitId)->value('access_token');
//        $token = session('ms_access_token');
//        dd($token);

        if (!$token) {
            logger()->error('MS Delete Failed: Token missing');
            return;
        }
//        if ($this->visit->ms_event_id) {

        if ($event) {
            Http::withToken($token)->delete(
                "https://graph.microsoft.com/v1.0/me/events/{$event}"
            );
        }
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

            if(isset($visit->ms_event_id)){
                $this->deleteEvent($visit->ms_event_id, $visit->id);
            }
//            $branch_manger = $this->branchMangerByVisitId($visit->id);
            return redirect()->route('show.visit', ['id' => $visit->id]);
        } else {
            // Optional: handle the case if event not found
            session()->flash('error', 'Visit not found.');
        }

    }

    public function approveVisit($visit_record)
    {

        $visitId = $visit_record['id'] ?? $this->visit_id;
        $visit = Visit::findOrFail($visitId);
        $this->visit_id = $visit->id;
        $this->can_approve($visit->id);

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
            return redirect()->route('show.visit', ['id' => $visit->id]);
        }
    }

    public function rejectVisit($visit_record)
    {
        $visitId = $visit_record['id'] ?? $this->visit_id;
        $visit = Visit::findOrFail($visitId);
        $this->visit_id = $visit->id;
        $this->can_approve($visit->id);

        if (!$this->can_recipient_approve) {
            abort(403);
        }

        $visit->status = '2';
        $visit->status_notice = $visit_record["status_notice"];

        $visit->approved_by = auth()->user()->id;
        if($visit->save()) {

            $emails = $visit->emps_requester->pluck('user.email')->filter()->values()->toArray();
            $this->visitMail($visit, $emails, 'reject');

            session()->flash('success', 'تم رفض الزيارة');
            return redirect()->route('show.visit', ['id' => $visit->id]);
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند رفض الزيارة');
            return redirect()->route('show.visit', ['id' => $visit->id]);
        }
    }
    public function can_approve($visit_id) {


        return $this->can_recipient_approve = Visit::where('visits.id', $visit_id)
            ->leftJoin('visit_emps', 'visits.id', 'visit_emps.visit_id')
            ->leftJoin('users', 'users.id', 'visit_emps.user_id')
            ->where('visit_emps.type', 'recipient')
            ->where('visit_emps.user_id', Auth::id())
//            ->where('users.group', '8')
            ->where('visits.status', '0')
            ->where('visits.is_deleted', '0')
            ->exists();

    }

    public function closeVisit($payload = [])
    {
        $visitId = $payload['id'] ?? $this->visit_id;
        $visit = Visit::findOrFail($visitId);
        $this->visit_id = $visit->id;

        if (!$visit->is_requester()) {
            abort(403);
        }

        $visit->status = '3';

        if($visit->save()) {

//            dd($visit->ms_event_id);
            if(isset($visit->ms_event_id)){
                $this->deleteEvent($visit->ms_event_id, $visit->id);
            }

            session()->flash('success', 'تم إنجاز الزيارة');
            $this->visitMail($visit, null, 'review');

            return redirect()->route('visit-calendar');
        }
        else {
            session()->flash('error-message', 'حدث خطأ ما عند إنجاز الزيارة');
            return redirect()->route('visit-calendar');
        }
    }

    public function review($data)
    {
        $visitId = $data['id'] ?? $this->visit_id;
        $visit = Visit::find($visitId);

        if (!$visit) {
            session()->flash('error-message', 'خطأ في الزيارة');
            return redirect()->route('visit-calendar');
        }

        $this->visit_id = $visit->id;
        $reviewerType = $data['reviewer_type'] ?? null;
        $reviewPayload = $data;
        unset($reviewPayload['id'], $reviewPayload['reviewer_type']);

        $visitEmps = VisitEmp::where('visit_id', $visit->id)
            ->where('user_id', Auth::id())
            ->when($reviewerType, function ($query) use ($reviewerType) {
                $query->where('type', $reviewerType);
            })
            ->where(function($query) {
                $query->whereNull('reviews')
                    ->orWhere('reviews', '');
            })
            ->first();

        if ($visitEmps) {
            $editRecord = VisitEmp::findOrFail($visitEmps->id);
            $editRecord->reviews = json_encode($reviewPayload);

            if($editRecord->save()) {
                $requester_emails = $visit->emps_requester->pluck('user.email');
                $recipent_emails = $visit->emps_recipients->pluck('user.email');

                if($editRecord->type == 'recipient') {
                    Mail::to($requester_emails)->queue(new VisitCreated($visit, $editRecord->user()->first()->name, 'reviews-done'));
                } elseif($editRecord->type == 'requester') {
                    Mail::to($recipent_emails)->queue(new VisitCreated($visit, $editRecord->user()->first()->name, 'reviews-done'));
                }

                $hasRecipientReview = $visit->emps_recipients()
                    ->whereNotNull('reviews')
                    ->exists();

                $hasRequesterReview = $visit->emps_requester()
                    ->whereNotNull('reviews')
                    ->exists();

                if ($hasRecipientReview && $hasRequesterReview) {
                    $visit->update(['status' => 5]);
                    Mail::to(['sadekr@alyaseenagri.com','mohammedsr@alyaseenagri.com'])
                        ->bcc('zahra@alyaseenagri.com')
                        ->queue(new VisitCreated($visit, $editRecord->user()->first()->name, 'reviews-done'));
                }

                session()->flash('success', 'تم التقييم بنجاح');
                return redirect()->route('visit-calendar');
            }

            session()->flash('error-message', 'حدث خطأ اثناء التقييم');
            return redirect()->route('visit-calendar');
        }

        session()->flash('error-message', 'حدث خطأ');
        return redirect()->route('visit-calendar');
    }





}
