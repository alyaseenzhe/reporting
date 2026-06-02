<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experiment;
class OfflineExperimentController extends Controller
{
    public function sync(Request $request)
    {
        $records = $request->input('records', []);

        $saved = [];

        foreach ($records as $record) {
            $experiment = Experiment::updateOrCreate(
                [
                    'offline_uuid' => $record['offline_uuid'],
                ],
                [
                    'name' => $record['name'],
                    'note' => $record['note'] ?? null,
                ]
            );

            $saved[] = $experiment->offline_uuid;
        }

        return response()->json([
            'status' => 'success',
            'saved' => $saved,
        ]);
    }
}
