@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1) ? 'clientadmin.layout' : 'user.layout'))


@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Standard PV Inverters
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    To select / deselect equipment as a favorite, click the star.
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
            @if(Auth::user()->userrole == 2)
            <div class="block-options">
                <button type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    onclick="showAddInverter()">
                    <i class="fa fa-plus"></i> Add Inverter
                </button>
            </div>
            @endif
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="equipments" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th style="width:40%">Manufacturer</th>
                            <th style="width:40%;">Model</th>
                            <th style="min-width: 200px;">Actions</th>
                        </tr>
                        @if(Auth::user()->userrole == 2)
                        <tr>
                            <th></th>
                            <th class="searchHead"> <input type="text" placeholder="Search Manufacturer" class="searchBox" id="mfrFilter"> </th>
                            <th></th>
                            <th></th>
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
                    <label for="module">Inverter Manufacturer</label>
                    <input type="text" class="form-control" id="module" value="">
                </div>
                <div class="form-group">
                    <label for="submodule">Model</label>
                    <input type="text" class="form-control" id="submodule" value="">
                </div>
                <div class="form-group">
                    <label for="option1">Option 1</label>
                    <input type="text" class="form-control" id="option1" value="">
                </div>
                <div class="form-group">
                    <label for="option2">Option 2</label>
                    <input type="text" class="form-control" id="option2" value="">
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
                    "url": "{{ url('getStandardInverters') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
            "columns": [
                { "data": "id" },
                { "data": "module" },
                { "data": "submodule" },
                { "data": "actions", "orderable": false }
            ]	 
        });

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        @if(Auth::user()->userrole == 2)
        $("#mfrFilter").on('keyup change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });
        @endif
    });
</script>

@include('standardequipment.inverter.script')
@endsection