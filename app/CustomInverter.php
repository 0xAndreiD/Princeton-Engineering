<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomInverter extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'mfr', 'model', 'rating', 'MPPT_Channels', 'kW_MPPT_max', 'Sys_Vol_max', 'Oper_DC_Vol_min', 'Oper_DC_Vol_max', 'Imp_max', 'Input_MPPT_max', 'Isc_max', 'Isc_MPPT_max', 'DC_Input_max', 'DC_Input_MPPT', 'DC_Wire_max', 'BiPolar', 'Rated_Out_Power', 'AC_Power_max', 'Rated_Out_Volt', 'AC_Low_Vol', 'AC_High_Vol', 'Out_Calc_max', 'Out_max', 'Inverter_Phasing', 'AC_Phases', 'AC_Wires', 'Neut_Ref_Vol', 'AC_max_Wires', 'AC_Wire_Size_max', 'Efficiency_max', 'CEC_Efficiency', 'Power_Factor_Lead', 'Power_Factor_Lag', 'Breaker_min', 'Breaker_max', 'Wire_Ins_Vol_min', 'Lug_Temp', 'xForm_VA_Multiplier', 'AC_Volt_Drop_max', 'Oper_Temp_min', 'Oper_Temp_max', 'Available_Fault', 'favorite', 'client_no'
    ];

    protected $table = 'custom_inverter';

    public $timestamps = false;
}
