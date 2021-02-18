<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserSetting extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'userId', 'inputFontSize', 'inputCellHeight', 'inputFontFamily', 'includeFolderName'
    ];

    protected $table = 'user_settings';

    public $timestamps = false;
}
