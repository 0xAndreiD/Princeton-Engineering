@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1) ? 'clientadmin.layout' : 'user.layout'))


@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Custom Solar Rackings
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Manage your solar rackings here
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Solar Racking List</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    onclick="showAddRacking()">
                    <i class="fa fa-plus"></i> Add Solar Racking
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="equipments" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 7%;">ID</th>
                            <th style="width:10%">Manufacturer</th>
                            <th style="width:10%;">Model</th>
                            <th style="width:7%;">Rack Style</th>
                            <th style="width:7%;">Tilt Angle</th>
                            <th style="width:7%;">Rack Weight per Module</th>
                            <th style="width:7%;">Rack Width</th>
                            <th style="width:7%;">Rack Depth</th>
                            <th style="width:7%;">Rack Lowest Height</th>
                            <th style="width:7%;">EW Module Spacing</th>
                            <th style="width:7%;">NS Module Spacing</th>
                            <th style="width:10%;">URL</th>
                            <th style="min-width: 150px;">Actions</th>
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
                    <a class="text-white font-w600" href="#" id="sidebar-title">Edit Solar Racking</a>
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
                    <label for="mfr">Manufacturer</label>
                    <input type="text" class="form-control" id="mfr" value="">
                </div>
                <div class="form-group">
                    <label for="model">Model</label>
                    <input type="text" class="form-control" id="model" value="">
                </div>
                <div class="form-group">
                    <label for="style">Rack Style</label>
                    <input type="text" class="form-control" id="style" value="">
                </div>
                <div class="form-group">
                    <label for="angle">Tilt Angle</label>
                    <input type="text" class="form-control" id="angle" value="">
                </div>
                <div class="form-group">
                    <label for="rack_weight">Rack Weight per Module</label>
                    <input type="text" class="form-control" id="rack_weight" value="">
                </div>
                <div class="form-group">
                    <label for="width">Rack Width</label>
                    <input type="text" class="form-control" id="width" value="">
                </div>
                <div class="form-group">
                    <label for="depth">Rack Depth</label>
                    <input type="text" class="form-control" id="depth" value="">
                </div>
                <div class="form-group">
                    <label for="lowest_height">Rack Lowest Height</label>
                    <input type="text" class="form-control" id="lowest_height" value="">
                </div>
                <div class="form-group">
                    <label for="module_spacing_EW">EW Module Spacing</label>
                    <input type="text" class="form-control" id="module_spacing_EW" value="">
                </div>
                <div class="form-group">
                    <label for="module_spacing_NS">NS Module Spacing</label>
                    <input type="text" class="form-control" id="module_spacing_NS" value="">
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" class="form-control" id="url" value="">
                </div>
                <div class="block-content row justify-content-center border-top">
                    <div class="col-9">
                        <button type="btn" class="btn btn-block btn-hero-primary" onclick="submitRacking()">
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
                    "url": "{{ url('getCustomRacking') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
            "columns": [
                { "data": "id" },
                { "data": "mfr" },
                { "data": "model" },
                { "data": "style" },
                { "data": "angle" },
                { "data": "rack_weight" },
                { "data": "width" },
                { "data": "depth" },
                { "data": "lowest_height" },
                { "data": "module_spacing_EW" },
                { "data": "module_spacing_NS" },
                { "data": "url" },
                { "data": "actions", "orderable": false }
            ]	 
        });

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

    });
</script>

@include('equipment.racking.script')
@endsection