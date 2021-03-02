<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomModule extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'mfr', 'model', 'rating', 'length', 'width', 'depth', 'weight', 'Voc', 'Vmp', 'Isc', 'Imp', 'Mtg_Hole_1', 'Mtg_Hole_2', 'lead_len', 'lead_guage', 'Vdc_max', 'Tmp_Factor_Pmax', 'Tmp_Factor_Voc', 'Tmp_Factor_Isc', 'Fuse_Size_max', 'efficiency', 'rev_date', 'product_literature', 'url', 'client_no', 'favorite', 'crc32'
    ];

    protected $table = 'custom_module';

    public $timestamps = false;
}
