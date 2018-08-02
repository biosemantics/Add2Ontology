<?php

namespace App\Http\Controllers\Api\V1;

use App\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Auth;

class HomeController extends Controller
{
    public function saveActivityLog(Request $request) {
        $activityLog = ActivityLog::create($request->all());

        return $activityLog;
    }
}
