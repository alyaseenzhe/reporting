<?php


namespace App\Http\Livewire\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Visit;
use Carbon\Carbon;
trait MsGraphAuthTrait
{

    public function connect()
    {
        $tenantId = config('msgraph.urlAuthorize');

        $query = http_build_query([
            'client_id' => config('msgraph.clientId'),
            'response_type' => 'code',
            'redirect_uri' => config('services.microsoft.redirect'),
            'response_mode' => 'query',
            'scope' => 'openid profile offline_access user.read calendars.readwrite',
            'state' => csrf_token(),
        ]);


        return redirect()->away($tenantId.'?' . $query);
//        return redirect()->away('https://login.microsoftonline.com/common/oauth2/v2.0/authorize?' . $query);
    }

    public function callback()
    {
        $visit = Visit::findOrFail($this->visit_id);
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



        // 2️⃣ Create test event in Outlook calendar
        $eventResponse = Http::withToken($accessToken)->post('https://graph.microsoft.com/v1.0/me/events', [
            'subject' => 'title',
            'body' => [
                'contentType' => 'HTML',
                'content' => 'reason',
            ],
            'start' => [
                'dateTime' => now()->addHour()->toIso8601String(),
//                'dateTime' => Carbon::parse($visit->start)->toIso8601String(),
                'timeZone' => 'Asia/Riyadh',
            ],
            'end' => [
                'dateTime' => now()->addHours(2)->toIso8601String(),
//                'dateTime' => Carbon::parse($visit->end)->toIso8601String(),
                'timeZone' => 'Asia/Riyadh',
            ],
            'location' => [
                'displayName' => 'Office',
            ],
//                'attendees' => $attendees,
        ]);

        if ($eventResponse->failed()) {
            return response()->json([
                'error' => 'Failed to create event',
                'details' => $eventResponse->json(),
            ], 400);
        }

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
