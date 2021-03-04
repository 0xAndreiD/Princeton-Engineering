@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1) ? 'clientadmin.layout' : 'user.layout'))


@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Custom PV Modules
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Manage your modules here
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Module List</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    onclick="showAddModule()">
                    <i class="fa fa-plus"></i> Add Module
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="equipments" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        @if(Auth::user()->userrole == 2)
                        <tr>
                            <th class="text-center" style="width: 5%;">ID</th>
                            <th class="text-center" style="width: 15%;">Company</th>
                            <th style="width:12%">Manufacturer</th>
                            <th style="width:12%;">Model</th>
                            <th style="width:8%;">Rating</th>
                            <th style="width:8%;">Length</th>
                            <th style="width:8%;">Width</th>
                            <th style="width:8%;">Depth</th>
                            <th style="width:10%;">Mtg Hole dist(1)</th>
                            <th style="width:10%;">Url</th>
                            <th style="min-width: 150px;">Actions</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="searchHead">
                                <select placeholder="Search Company" class="searchBox" id="companyFilter">
                                    <option value="">All</option>
                                    @foreach($companyList as $company)
                                        <option value="{{ $company['company_name'] }}">{{ $company['company_name'] }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        @else
                        <tr>
                            <th class="text-center" style="width: 5%;">ID</th>
                            <th style="width:15%">Manufacturer</th>
                            <th style="width:15%;">Model</th>
                            <th style="width:8%;">Rating</th>
                            <th style="width:8%;">Length</th>
                            <th style="width:8%;">Width</th>
                            <th style="width:8%;">Depth</th>
                            <th style="width:12%;">Mtg Hole dist(1)</th>
                            <th style="width:12%;">Url</th>
                            <th style="min-width: 150px;">Actions</th>
                        </tr>
                        @endif
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
                    <a class="text-white font-w600" href="#" id="sidebar-title">Edit Module</a>
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
                    <label for="mfr">Solar Module Manufacturer</label>
                    <input type="text" class="form-control" id="mfr" value="">
                </div>
                <div class="form-group">
                    <label for="module">Module</label>
                    <input type="text" class="form-control" id="model" value="">
                </div>
                <div class="form-group">
                    <label for="rating">Power Rating(Watts)</label>
                    <input type="text" class="form-control" id="rating" value="">
                </div>
                <div class="form-group">
                    <label for="Voc">Voc per Module(Vdc)</label>
                    <input type="text" class="form-control" id="Voc" value="">
                </div>
                <div class="form-group">
                    <label for="Voc">Vmp per Module(Vdc)</label>
                    <input type="text" class="form-control" id="Vmp" value="">
                </div>
                <div class="form-group">
                    <label for="Isc">Isc per Module(Adc)</label>
                    <input type="text" class="form-control" id="Isc" value="">
                </div>
                <div class="form-group">
                    <label for="Imp">Imp per Module(Adc)</label>
                    <input type="text" class="form-control" id="Imp" value="">
                </div>
                <div class="form-group">
                    <label for="length">Length(Inch)</label>
                    <input type="text" class="form-control" id="length" value="">
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
                    <label for="weight">Weight(lb)</label>
                    <input type="text" class="form-control" id="weight" value="">
                </div>
                <div class="form-group">
                    <label for="Mtg_Hole_1">Mtg Hole dist from end (1)(Inch)</label>
                    <input type="text" class="form-control" id="Mtg_Hole_1" value="">
                </div>
                <div class="form-group">
                    <label for="Mtg_Hole_2">Mtg Hole dist from end (2)(Inch)</label>
                    <input type="text" class="form-control" id="Mtg_Hole_2" value="">
                </div>
                <div class="form-group">
                    <label for="lead_len">Lead Length(Inch)</label>
                    <input type="text" class="form-control" id="lead_len" value="">
                </div>
                <div class="form-group">
                    <label for="lead_guage">Lead Gauge</label>
                    <input type="text" class="form-control" id="lead_guage" value="">
                </div>
                <div class="form-group">
                    <label for="Vdc_max">Maximum System Voltage(Vdc)</label>
                    <input type="text" class="form-control" id="Vdc_max" value="">
                </div>
                <div class="form-group">
                    <label for="Tmp_Factor_Pmax">Pmax Temp Factor(%/°C)</label>
                    <input type="text" class="form-control" id="Tmp_Factor_Pmax" value="">
                </div>
                <div class="form-group">
                    <label for="Tmp_Factor_Voc">Voc Temp Factor(%/°C)</label>
                    <input type="text" class="form-control" id="Tmp_Factor_Voc" value="">
                </div>
                <div class="form-group">
                    <label for="Tmp_Factor_Isc">Isc Temp Factor(%/°C)</label>
                    <input type="text" class="form-control" id="Tmp_Factor_Isc" value="">
                </div>
                <div class="form-group">
                    <label for="Fuse_Size_max">Max Fuse Size(Adc)</label>
                    <input type="text" class="form-control" id="Fuse_Size_max" value="">
                </div>
                <div class="form-group">
                    <label for="efficiency">Module Efficiency(%)</label>
                    <input type="text" class="form-control" id="efficiency" value="">
                </div>
                <div class="form-group">
                    <label for="rev_date">Revision Date</label>
                    <input type="date" class="form-control" id="rev_date" value="" >
                </div>
                <div class="form-group">
                    <label for="product_literature">Product Literature</label>
                    <input type="text" class="form-control" id="product_literature" value="">
                </div>
                <div class="form-group">
                    <label for="url">Url</label>
                    <input type="text" class="form-control" id="url" value="">
                </div>
                <div class="block-content row justify-content-center border-top">
                    <div class="col-9">
                        <button type="btn" class="btn btn-block btn-hero-primary" onclick="submitModule()">
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
                    "url": "{{ url('getCustomModules') }}",
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
                { "data": "length" },
                { "data": "width" },
                { "data": "depth" },
                { "data": "Mtg_Hole_1" },
                { "data": "url" },
                { "data": "actions", "orderable": false }
            ]	 
        });

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        @if(Auth::user()->userrole == 2)
        $("#companyFilter").on('change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });
        @endif
    });
</script>

@include('equipment.module.script')
@endsection