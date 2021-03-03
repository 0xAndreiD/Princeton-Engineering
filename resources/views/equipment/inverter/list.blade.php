@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1) ? 'clientadmin.layout' : 'user.layout'))


@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Custom PV Inverters
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Manage your inverters here
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Inverter List</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    onclick="showAddInverter()">
                    <i class="fa fa-plus"></i> Add Inverter
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="equipments" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            @if(Auth::user()->userrole == 2)
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th class="text-center" style="width: 20%;">Company</th>
                            <th style="width:18%">Manufacturer</th>
                            <th style="width:18%;">Model</th>
                            <th style="width:18%;">Rating</th>
                            <th style="min-width: 150px;">Actions</th>
                            @else
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th style="width:25%">Manufacturer</th>
                            <th style="width:25%;">Model</th>
                            <th style="width:25%;">Rating</th>
                            <th style="min-width: 150px;">Actions</th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<aside id="side-overlay">
    <!-- Side Header -->
    <div class="bg-image">
        <div class="bg-primary-op">
            <div class="content-header">
                <!-- User Info -->
                <div class="ml-2">
                    <a class="text-white font-w600" href="#" id="sidebar-title">Edit Inverter</a>
                    <div class="text-white-75 font-size-sm">Input your data here</div>
                </div>
                <!-- END User Info -->

                <!-- Close Side Overlay -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <a class="ml-auto text-white" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
                    <i class="fa fa-times-circle"></i>
                </a>
                <!-- END Close Side Overlay -->
            </div>
        </div>
    </div>
    <!-- END Side Header -->

    <!-- Side Content -->
    <div class="content-side">
        <!-- Side Overlay Tabs -->
        <div class="block block-transparent pull-x pull-t">
            <div class="block-content tab-content overflow-hidden side-view">
                <div class="form-group">
                    <label for="mfr">Inverter Manufacturer</label>
                    <input type="text" class="form-control" id="mfr" value="">
                </div>
                <div class="form-group">
                    <label for="model">Model</label>
                    <input type="text" class="form-control" id="model" value="">
                </div>
                <div class="form-group">
                    <label for="rating">Rating(Watts)</label>
                    <input type="text" class="form-control" id="rating" value="">
                </div>
                <div class="form-group">
                    <label for="MPPT_Channels"># MPPT Channels</label>
                    <input type="text" class="form-control" id="MPPT_Channels" value="">
                </div>
                <div class="form-group">
                    <label for="kW_MPPT_max">Input kW / MPPT Max(kWdc)</label>
                    <input type="text" class="form-control" id="kW_MPPT_max" value="">
                </div>
                <div class="form-group">
                    <label for="Sys_Vol_max">System Voltage Max(Vdc)</label>
                    <input type="text" class="form-control" id="Sys_Vol_max" value="">
                </div>
                <div class="form-group">
                    <label for="Oper_DC_Vol_min">Operating DC Input Voltage Min(Vdc)</label>
                    <input type="text" class="form-control" id="Oper_DC_Vol_min" value="">
                </div>
                <div class="form-group">
                    <label for="Oper_DC_Vol_max">Operating DC Input Voltage Max(Vdc)</label>
                    <input type="text" class="form-control" id="Oper_DC_Vol_max" value="">
                </div>
                <div class="form-group">
                    <label for="Imp_max">Input Current (Imp) Max(Adc)</label>
                    <input type="text" class="form-control" id="Imp_max" value="">
                </div>
                <div class="form-group">
                    <label for="Input_MPPT_max">Max Input Current / MPPT(Adc)</label>
                    <input type="text" class="form-control" id="Input_MPPT_max" value="">
                </div>
                <div class="form-group">
                    <label for="Isc_max">Short Circuit Current (Isc) Max(Adc)</label>
                    <input type="text" class="form-control" id="Isc_max" value="">
                </div>
                <div class="form-group">
                    <label for="Isc_MPPT_max">Short Circuit Current (Isc) / MPPT Max(Adc)</label>
                    <input type="text" class="form-control" id="Isc_MPPT_max" value="">
                </div>
                <div class="form-group">
                    <label for="DC_Input_max"># DC Inputs Max</label>
                    <input type="text" class="form-control" id="DC_Input_max" value="">
                </div>
                <div class="form-group">
                    <label for="DC_Input_MPPT"># DC Inputs / MPPT</label>
                    <input type="text" class="form-control" id="DC_Input_MPPT" value="">
                </div>
                <div class="form-group">
                    <label for="DC_Wire_max">DC Max Wire Size</label>
                    <input type="text" class="form-control" id="DC_Wire_max" value="">
                </div>
                <div class="form-group">
                    <label for="BiPolar">BiPolar</label>
                    <input type="text" class="form-control" id="BiPolar" value="">
                </div>
                <div class="form-group">
                    <label for="Rated_Out_Power">Rated Output Power(kW)</label>
                    <input type="text" class="form-control" id="Rated_Out_Power" value="">
                </div>
                <div class="form-group">
                    <label for="AC_Power_max">Maximum AC Apparent Power(kVA)</label>
                    <input type="text" class="form-control" id="AC_Power_max" value="">
                </div>
                <div class="form-group">
                    <label for="Rated_Out_Volt">Rated Output Voltage(Vac)</label>
                    <input type="text" class="form-control" id="Rated_Out_Volt" value="">
                </div>
                <div class="form-group">
                    <label for="AC_Low_Vol">AC Low Voltage(Vac)</label>
                    <input type="text" class="form-control" id="AC_Low_Vol" value="">
                </div>
                <div class="form-group">
                    <label for="AC_High_Vol">AC High Voltage(Vac)</label>
                    <input type="text" class="form-control" id="AC_High_Vol" value="">
                </div>
                <div class="form-group">
                    <label for="Out_Calc_max">Max Output Current Calculated(Aac)</label>
                    <input type="text" class="form-control" id="Out_Calc_max" value="" >
                </div>
                <div class="form-group">
                    <label for="Out_max">Max Output Current(Aac)</label>
                    <input type="text" class="form-control" id="Out_max" value="">
                </div>
                <div class="form-group">
                    <label for="Inverter_Phasing">Inverter Phasing</label>
                    <input type="text" class="form-control" id="Inverter_Phasing" value="">
                </div>
                <div class="form-group">
                    <label for="AC_Phases"># AC Phases</label>
                    <input type="text" class="form-control" id="AC_Phases" value="">
                </div>
                <div class="form-group">
                    <label for="AC_Wires"># AC Wires</label>
                    <input type="text" class="form-control" id="AC_Wires" value="">
                </div>
                <div class="form-group">
                    <label for="Neut_Ref_Vol">Neutral for Reference Voltage Only</label>
                    <input type="text" class="form-control" id="Neut_Ref_Vol" value="">
                </div>
                <div class="form-group">
                    <label for="AC_max_Wires">AC Max # Wires</label>
                    <input type="text" class="form-control" id="AC_max_Wires" value="">
                </div>
                <div class="form-group">
                    <label for="AC_Wire_Size_max">AC Max Wire Size</label>
                    <input type="text" class="form-control" id="AC_Wire_Size_max" value="">
                </div>
                <div class="form-group">
                    <label for="Efficiency_max">Max Efficiency(%)</label>
                    <input type="text" class="form-control" id="Efficiency_max" value="">
                </div>
                <div class="form-group">
                    <label for="CEC_Efficiency">CEC Efficiency(%)</label>
                    <input type="text" class="form-control" id="CEC_Efficiency" value="">
                </div>
                <div class="form-group">
                    <label for="Power_Factor_Lead">Power Factor Lead</label>
                    <input type="text" class="form-control" id="Power_Factor_Lead" value="">
                </div>
                <div class="form-group">
                    <label for="Power_Factor_Lag">Power Factor Lag</label>
                    <input type="text" class="form-control" id="Power_Factor_Lag" value="">
                </div>
                <div class="form-group">
                    <label for="Breaker_min">Breaker Size - Min(Aac)</label>
                    <input type="text" class="form-control" id="Breaker_min" value="">
                </div>
                <div class="form-group">
                    <label for="Breaker_max">Breaker Size - Max(Aac)</label>
                    <input type="text" class="form-control" id="Breaker_max" value="">
                </div>
                <div class="form-group">
                    <label for="Wire_Ins_Vol_min">Min Wire Insulation Volts(V)</label>
                    <input type="text" class="form-control" id="Wire_Ins_Vol_min" value="">
                </div>
                <div class="form-group">
                    <label for="Lug_Temp">Lug Temperature(deg C)</label>
                    <input type="text" class="form-control" id="Lug_Temp" value="">
                </div>
                <div class="form-group">
                    <label for="xForm_VA_Multiplier">xForm VA rating Multiplier</label>
                    <input type="text" class="form-control" id="xForm_VA_Multiplier" value="">
                </div>
                <div class="form-group">
                    <label for="AC_Volt_Drop_max">Maximum Allowable AC Voltage Drop(VAC)</label>
                    <input type="text" class="form-control" id="AC_Volt_Drop_max" value="">
                </div>
                <div class="form-group">
                    <label for="Oper_Temp_min">Operating Temperature - Min(deg C)</label>
                    <input type="text" class="form-control" id="Oper_Temp_min" value="">
                </div>
                <div class="form-group">
                    <label for="Oper_Temp_max">Operating Temperature - Max(deg C)</label>
                    <input type="text" class="form-control" id="Oper_Temp_max" value="">
                </div>
                <div class="form-group">
                    <label for="Available_Fault">Available Fault Current(Aac)</label>
                    <input type="text" class="form-control" id="Available_Fault" value="">
                </div>
                <div class="form-group">
                    <label for="Install_Angle_Horiz_min">Installation Angle from Horiz Min(deg)</label>
                    <input type="text" class="form-control" id="Install_Angle_Horiz_min" value="">
                </div>
                <div class="form-group">
                    <label for="height">Height(Inch)</label>
                    <input type="text" class="form-control" id="height" value="">
                </div>
                <div class="form-group">
                    <label for="width">Width(Inch)</label>
                    <input type="text" class="form-control" id="width" value="">
                </div>
                <div class="form-group">
                    <label for="depth">Depth(Inch)</label>
                    <input type="text" class="form-control" id="depth" value="">
                </div>
                <div class="form-group">
                    <label for="weight">Weight(Inch)</label>
                    <input type="text" class="form-control" id="weight" value="">
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" class="form-control" id="url" value="">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" class="form-control" id="status" value="">
                </div>
                <div class="form-group">
                    <label for="product_literature">Product Literature</label>
                    <input type="text" class="form-control" id="product_literature" value="">
                </div>
                <div class="form-group">
                    <label for="cost">Cost</label>
                    <input type="text" class="form-control" id="cost" value="">
                </div>
                <div class="form-group">
                    <label for="rev_date">Revision Date</label>
                    <input type="date" class="form-control" id="rev_date" value="" >
                </div>
                <div class="form-group">
                    <label for="DC_Start_Vol">DC Start Voltage(Vdc)</label>
                    <input type="text" class="form-control" id="DC_Start_Vol" value="">
                </div>
                <div class="form-group">
                    <label for="MPPT2_Input_max">MPPT2 Max Input Current(Adc)</label>
                    <input type="text" class="form-control" id="MPPT2_Input_max" value="">
                </div>
                <div class="form-group">
                    <label for="MPPT2_Short_Circuit_max">MPPT2 Max Short Circuit Current(Adc)</label>
                    <input type="text" class="form-control" id="MPPT2_Short_Circuit_max" value="">
                </div>
                <div class="form-group">
                    <label for="Input_kW_min">Input kW Min(Adc)</label>
                    <input type="text" class="form-control" id="Input_kW_min" value="">
                </div>
                <div class="form-group">
                    <label for="MPP_Vol_Low">MPP Voltage Low(Vdc)</label>
                    <input type="text" class="form-control" id="MPP_Vol_Low" value="">
                </div>
                <div class="form-group">
                    <label for="MPP_Vol_High">MPP Voltage High(Vdc)</label>
                    <input type="text" class="form-control" id="MPP_Vol_High" value="">
                </div>
                <div class="block-content row justify-content-center border-top">
                    <div class="col-9">
                        <button type="btn" class="btn btn-block btn-hero-primary" onclick="submitInverter()">
                            <i class="fa fa-fw fa-save mr-1"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Side Overlay Tabs -->
    </div>
    <!-- END Side Content -->
</aside>

<script src="{{ asset('js/pages/common.js') }}"></script>

<script>
    $(document).ready(function () {
        var table = $('#equipments').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "orderCellsTop": true,
            "pageLength" : 50,
            "ajax":{
                    "url": "{{ url('getCustomInverters') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
            "columns": [
                { "data": "id" },
                @if(Auth::user()->userrole == 2)
                { "data": "companyname" },
                @endif
                { "data": "mfr" },
                { "data": "model" },
                { "data": "rating" },
                { "data": "actions", "orderable": false }
            ]	 
        });

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

    });
</script>

@include('equipment.inverter.script')
@endsection