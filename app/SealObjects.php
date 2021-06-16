<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SealObjects extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'client_id', 'jurisdiction_abbrev', 'location', 'Execute_Command', 'text', 'OnTop', 'OnScreen', 'FixedPrint', 'OnPrint', 'Opacity', 'Rotation', 'FontSize', 'ImageScale', 'HorizAlign', 'VertAlign', 'TextAlign', 'Date', 'Page_X', 'Page_Y', 'Top_Lx_rel', 'Top_Ly_rel', 'Bot_Rx_rel', 'Bot_Ry_rel', 'dwg_template'
    ];

    protected $table = 'seal_objects';

    public $timestamps = false;
}
