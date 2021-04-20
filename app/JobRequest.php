<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class JobRequest extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'companyName', 'companyId', 'userId', 'clientProjectName', 'clientProjectNumber', 'requestFile', 'planStatus', 'projectState', 'analysisType', 'createdTime', 'submittedTime', 'timesDownloaded', 'timesEmailed', 'timesComputed', 'state', 'planCheck', 'chatCompleted'
    ];

    protected $table = 'job_request';

    public $timestamps = false;
}
