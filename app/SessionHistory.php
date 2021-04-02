<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SessionHistory extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'userId', 'ipAddress', 'device', 'created_at', 'last_accessed'
    ];

    protected $table = 'session_history';

    public $timestamps = false;
}
