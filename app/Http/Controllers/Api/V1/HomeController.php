<?php

namespace App\Http\Controllers\Api\V1;

use App\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Auth;

class HomeController extends Controller
{
    public function saveActivityLog(Request $request, $term) {

        #var_dump($request);
        #exit;
        
        $activityLog = ActivityLog::create([
            'user_email' => $request->input('user_email'),
            'action' => $request->input('action'),
            'action_details' => $request->input('action_details') . ' ' . $term,
            'abnormal_system_response' => $request->input('abnormal_system_response'),
            'type' => $request->input('type'),
        ]);

        return $activityLog;
    }
}
