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
        'id', 'companyName', 'companyId', 'userId', 'clientProjectName', 'clientProjectNumber', 'requestFile', 'planStatus', 'projectState', 'analysisType', 'createdTime', 'submittedTime', 'timesDownloaded', 'timesEmailed', 'timesComputed', 'state', 'planCheck', 'chatIcon', 'asBuilt', 'asBuiltDate', 'eSeal', 'eSeal_asbuilt', 'reviewerId', 'lastReviewTime', 'creator', 'reviewer_co_id', 'reviewer_asb_id', 'reviewer_asb_co_id'
    ];

    protected $table = 'job_request';

    public $timestamps = false;
}
