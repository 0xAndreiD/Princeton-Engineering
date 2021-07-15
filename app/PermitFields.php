<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PermitFields extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename', 'pdffield', 'idx', 'pdfcheck', 'defaultvalue', 'htmlfield', 'htmlcheck', 'label', 'dbinfo', 'type', 'options'
    ];

    protected $table = 'permit_fields';

    public $timestamps = false;
}
