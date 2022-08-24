@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : ((Auth::user()->userrole == 6) ? 'consultant.layout' : 'user.layout')))


@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Standard PV Modules
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
            <h3 class="block-title">Module List</h3>
            @if(Auth::user()->userrole == 2)
            <div class="block-options">
                <button type="button" class="btn-block-option mr-2" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    onclick="showAddModule()">
                    <i class="fa fa-plus"></i> Add Module
                </button>
                <button type="button" class="btn-block-option mr-2" 
                    onclick="copyModules()">
                    <i class="fa fa-copy"></i> Copy Modules
                </button>
                <button type="button" class="btn-block-option" 
                    onclick="delModules()">
                    <i class="fa fa-trash"></i> Delete Modules
                </button>
            </div>
            @endif
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="equipments" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            @if(Auth::user()->userrole == 2)
                            <th class="text-center" style="width: 5%;"></th>
                            @endif
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th style="width:35%">Manufacturer</th>
                            <th style="width:35%;">Model</th>
                            <th style="width:10%;">Type</th>
                            <th style="min-width: 200px;">Actions</th>
                        </tr>
                        @if(Auth::user()->userrole == 2)
                        <tr>
                            <th></th>
                            <th></th>
                            <th class="searchHead"> <input type="text" placeholder="Search Manufacturer" class="searchBox" id="mfrFilter"> </th>
                            <th></th>
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

<div class="modal fade" id="details_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 700px;">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title" id="cec-reset-title">Favorite Product Details</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="col-12">
                            <span>AutoCAD Automation</span>
                            <p>Please enter the complete path and filename and which pages you would like inserted into your AutoCAD template.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-3 text-right p-0">
                            <label style="margin: 3px 0px;">Path Filename</label>
                        </div>
                        <div class="form-group col-8 text-right">
                            <input type="text" id="path_filename" class="form-control mb-1" style="border: 1px solid pink; width: 100%; height: 32px;">
                            <div class="text-left">i.e. G:\Common product sheets\Inverter 1.pdf</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-3 text-right p-0">
                            <label style="margin: 3px 0px;">Pages</label>
                        </div>
                        <div class="form-group col-8 text-right">
                            <input type="text" id="pages" class="form-control mb-1" style="border: 1px solid pink; width: 100%; height: 32px;">
                            <div class="text-left">i.e. 1,3 or 2-4, etc.</div>
                        </div>
                    </div>
                </div>
                <div class="block-content block-content-full text-right bg-light">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" onclick="saveFavorite()">Select as Favorite without Path</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="doToggle()">Save</button>
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                </div>
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
                    <label for="module">Model</label>
                    <input type="text" class="form-control" id="model" value="">
                </div>
                <div class="form-group">
                    <label for="rating">Power Rating(Watts)</label>
                    <input type="text" class="form-control" id="rating" value="">
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
                    "url": "{{ url('getStandardModules') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
            "columns": [
                @if(Auth::user()->userrole == 2)
                { "data": "bulkcheck", "orderable": false },
                @endif
                { "data": "id" },
                { "data": "mfr" },
                { "data": "model" },
                { "data": "type" },
                { "data": "actions", "orderable": false }
            ],
            @if(Auth::user()->userrole == 2)
            "order": [[1, "asc"]]
            @endif
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

@include('standardequipment.module.script')
@endsection