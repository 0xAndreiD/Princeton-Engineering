<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomStanchion extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'mfr', 'model', 'pullout', 'Z_moment_max', 'Lateral_Pullout', 'Plate_X', 'Plate_Y', 'Height_z', 'Bolt_Holes_Total', 'X1_Bolts', 'X1_Dist_Edge', 'X2_Bolts', 'X2_Dist_Edge', 'X3_Bolts', 'X3_Dist_Edge', 'X4_Bolts', 'X4_Dist_Edge', 'Y1_Bolts', 'Y1_Dist_Edge', 'Y2_Bolts', 'Y2_Dist_Edge', 'Y3_Bolts', 'Y3_Dist_Edge', 'Y4_Bolts', 'Y4_Dist_Edge', 'material', 'weight', 'url', 'favorite', 'client_no'
    ];

    protected $table = 'custom_stanchions';

    public $timestamps = false;
}
