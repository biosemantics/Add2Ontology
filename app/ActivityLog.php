<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    //
    protected $fillable = ['user_email', 'action', 'action_details', 'abnormal_system_response', 'type'];
}
