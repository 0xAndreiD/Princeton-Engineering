<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BillingInfo extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'clientId', 'billing_name', 'billing_mail', 'billing_address', 'billing_city', 'billing_state', 'billing_zip', 'billing_same_info', 'shipping_name', 'shipping_mail', 'shipping_address', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_same_info', 'card_name', 'card_number', 'expiration_date', 'security_code', 'billing_type', 'expected_jobs', 'base_fee', 'extra_fee', 'send_invoice', 'block_on_fail'
    ];

    protected $table = 'billing_info';

    public $timestamps = false;
}
