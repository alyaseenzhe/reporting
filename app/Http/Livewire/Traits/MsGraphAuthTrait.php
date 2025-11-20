<?php


namespace App\Http\Livewire\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Visit;
use Carbon\Carbon;
trait MsGraphAuthTrait
{
    public $visit_id;
    public $visit;


    public function mount()
    {
        $this->visit_id = request()->get('state');  // <<< IMPORTANT
    }


    public function connect($id)
    {
        $this->visit_id = $id;

//        dd($this->visit_id);
//        $this->visit_id = request('visit_id');
        $tenantId = config('msgraph.urlAuthorize');

        $query = http_build_query([
            'client_id' => config('msgraph.clientId'),
            'response_type' => 'code',
            'redirect_uri' => config('services.microsoft.redirect'),
            'response_mode' => 'query',
            'scope' => 'openid profile offline_access user.read calendars.readwrite',
            'state' =>  $this->visit_id,
//            'state' => csrf_token(),
        ]);


        return redirect()->away($tenantId.'?' . $query);
//        return redirect()->away('https://login.microsoftonline.com/common/oauth2/v2.0/authorize?' . $query);
    }

    public function callback()
    {
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

//        dd(request()->get('state'));
        // Get visit_id directly from the URL
        $this->visit_id = request()->get('state');

        if (!$this->visit_id) {
            return "No visit id received in state.";
        }
        $this->visit = Visit::findOrFail($this->visit_id);
//        dd($visit);
        $tenantId = config('msgraph.urlAccessToken');
        $code = request('code');

        if (!$code) {
            return "Authorization code not found.";
        }
//        dd($tenantId);

        // Exchange code for access token
//        $response = Http::asForm()->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
        $response = Http::asForm()->post($tenantId, [
            'client_id' => config('msgraph.clientId'),
            'client_secret' => config('msgraph.clientSecret'),
            'redirect_uri' => config('msgraph.redirectUri'),
            'grant_type' => 'authorization_code',
            'code' => $code,

        ]);


        $token = $response->json();

        // Save to session or database
        session(['ms_access_token' => $token['access_token'] ?? null]);


        $accessToken = $token['access_token'] ?? null;

        if (!$accessToken) {
            return response()->json([
                'error' => 'No access token received',
                'details' => $token,
            ], 400);
        }
        // Save token for future use
        session(['ms_access_token' => $accessToken]);


        $start = Carbon::parse($this->visit->start)
            ->setTimezone('Asia/Riyadh')
            ->format('Y-m-d\TH:i:s');

        $end = Carbon::parse($this->visit->end ?? $this->visit->start)
            ->setTimezone('Asia/Riyadh')
            ->format('Y-m-d\TH:i:s');

        $visitUrl = url("/show-visit/{$this->visit->id}");
        // 2️⃣ Create test event in Outlook calendar
        $eventResponse = Http::withToken($accessToken)->post('https://graph.microsoft.com/v1.0/me/events', [
            'subject' => $this->visit->title,
            'body' => [
                'contentType' => 'HTML',
                'content' => '
                <p>مرحبا</p>
                <p>الرجاء الدخول على لعرض تفاصيل الزيارة</p>
                <a href="' . $visitUrl . '"
                   style="
                       display:inline-block;
                       padding:10px 20px;
                       background-color:#0078D4;
                       color:white;
                       text-decoration:none;
                       border-radius:5px;
                       font-weight:bold;
                   ">
                   عرض الزيارة
                </a>
                <p>شكرا لكم!</p>
            ',
            ],
            'start' => [
//                'dateTime' => now()->addHour()->toIso8601String(),
                'dateTime' => $start,
                'timeZone' => 'Asia/Riyadh',
            ],
            'end' => [
//                'dateTime' => now()->addHours(2)->toIso8601String(),
                'dateTime' => $end,
                'timeZone' => 'Asia/Riyadh',
            ],
            'location' => [
                'displayName' => $this->branches[$this->visit->branch],
            ],
//                'attendees' => $attendees,
        ]);

        if ($eventResponse->failed()) {
            return response()->json([
                'error' => 'Failed to create event',
                'details' => $eventResponse->json(),
            ], 400);
        }
//             return redirect('visit-calendar')->with('success', 'تمت الموافقة بنجاح');

        return response()->json([
            'message' => 'Successfully connected and created test event!',
            'event' => $eventResponse->json(),
        ]);

        //     return redirect()->route('dashboard')->with('success', 'Microsoft Calendar Connected!');
    }

    public function msGraphToken()
    {
        return session('ms_access_token');
    }
}
