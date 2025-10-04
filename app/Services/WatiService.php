<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WatiService
{
    public function sendTemplateMessages($template, array $messages)
    {
        $response = Http::withToken(env('WATI_API_KEY'))
            ->post(env('WATI_API_URL') . '/sendTemplateMessages', [
                'template_name' => $template,
                'messages' => $messages,
            ]);

        return $response->json();
    }

}
