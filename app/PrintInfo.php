<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PrintInfo extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'job_id', 'client_id', 'copies', 'plan_sheets', 'report_sheets', 'seal_type', 'signature', 'user_id', 'address_id', 'delivery_method', '3rd_party_fedex', 'printed', 'sent', 'tracking', 'user_notes', 'printer_notes', 'selected_files'
    ];

    protected $table = 'printing';

    public $timestamps = false;
}
