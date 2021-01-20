<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DataCheck extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'jobId', 'exposureUnit', 'exposureContent', 'exposureContent', 'occupancyUnit', 'occupancyContent', 'IBC', 'ASCE', 'NEC', 'stateCode', 'windLoadingValue', 'windLoadingContent', 'snowLoadingValue', 'snowLoadingContent', 'DCWatts', 'InverterAmperage', 'OCPDRating', 'RecommendedOCPD', 'MinCu', 'collarHeights'
    ];

    protected $table = 'data_check';

    public $timestamps = false;
}
