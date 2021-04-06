@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : 'user.layout'))


@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Custom Stanchions
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Manage your stanchions here
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Stanchions List</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    onclick="showAddStanchion()">
                    <i class="fa fa-plus"></i> Add Stanchion
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="equipments" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        @if(Auth::user()->userrole == 2)
                        <tr>
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th class="text-center" style="width: 20%;">Company</th>
                            <th style="width:30%">Manufacturer</th>
                            <th style="width:30%;">Model</th>
                            <th style="min-width: 200px;">Actions</th>
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
                        </tr>
                        @else
                        <tr>
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th style="width:40%">Manufacturer</th>
                            <th style="width:40%;">Model</th>
                            <th style="min-width: 200px;">Actions</th>
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
                    <a class="text-white font-w600" href="#" id="sidebar-title">Edit Stanchion</a>
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
                    <label for="pullout">Vertical Pullout</label>
                    <input type="text" class="form-control" id="pullout" value="">
                </div>
                <div class="form-group">
                    <label for="Z_moment_max">Max Z moment (lb ft)</label>
                    <input type="text" class="form-control" id="Z_moment_max" value="">
                </div>
                <div class="form-group">
                    <label for="Lateral_Pullout">Lateral Pullout</label>
                    <input type="text" class="form-control" id="Lateral_Pullout" value="">
                </div>
                <div class="form-group">
                    <label for="Plate_X">Plate X"</label>
                    <input type="text" class="form-control" id="Plate_X" value="">
                </div>
                <div class="form-group">
                    <label for="Plate_Y">Plate Y"</label>
                    <input type="text" class="form-control" id="Plate_Y" value="">
                </div>
                <div class="form-group">
                    <label for="Height_z">Height z inches</label>
                    <input type="text" class="form-control" id="Height_z" value="">
                </div>
                <div class="form-group">
                    <label for="Bolt_Holes_Total"># Bolt Holes Total</label>
                    <input type="text" class="form-control" id="Bolt_Holes_Total" value="">
                </div>
                <div class="form-group">
                    <label for="X1_Bolts">X1 # Bolts</label>
                    <input type="text" class="form-control" id="X1_Bolts" value="">
                </div>
                <div class="form-group">
                    <label for="X1_Dist_Edge">X1 Dist from Edge</label>
                    <input type="text" class="form-control" id="X1_Dist_Edge" value="">
                </div>
                <div class="form-group">
                    <label for="X2_Bolts">X2 # Bolts</label>
                    <input type="text" class="form-control" id="X2_Bolts" value="">
                </div>
                <div class="form-group">
                    <label for="X2_Dist_Edge">X2 Dist from Edge</label>
                    <input type="text" class="form-control" id="X2_Dist_Edge" value="">
                </div>
                <div class="form-group">
                    <label for="X3_Bolts">X3 # Bolts</label>
                    <input type="text" class="form-control" id="X3_Bolts" value="">
                </div>
                <div class="form-group">
                    <label for="X3_Dist_Edge">X3 Dist from Edge</label>
                    <input type="text" class="form-control" id="X3_Dist_Edge" value="">
                </div>
                <div class="form-group">
                    <label for="X4_Bolts">X4 # Bolts</label>
                    <input type="text" class="form-control" id="X4_Bolts" value="">
                </div>
                <div class="form-group">
                    <label for="X4_Dist_Edge">X4 Dist from Edge</label>
                    <input type="text" class="form-control" id="X4_Dist_Edge" value="">
                </div>
                <div class="form-group">
                    <label for="Y1_Bolts">Y1 # Bolts</label>
                    <input type="text" class="form-control" id="Y1_Bolts" value="">
                </div>
                <div class="form-group">
                    <label for="Y1_Dist_Edge">Y1 Dist from Edge</label>
                    <input type="text" class="form-control" id="Y1_Dist_Edge" value="">
                </div>
                <div class="form-group">
                    <label for="Y2_Bolts">Y2 # Bolts</label>
                    <input type="text" class="form-control" id="Y2_Bolts" value="">
                </div>
                <div class="form-group">
                    <label for="Y2_Dist_Edge">Y2 Dist from Edge</label>
                    <input type="text" class="form-control" id="Y2_Dist_Edge" value="">
                </div>
                <div class="form-group">
                    <label for="Y3_Bolts">Y3 # Bolts</label>
                    <input type="text" class="form-control" id="Y3_Bolts" value="">
                </div>
                <div class="form-group">
                    <label for="Y3_Dist_Edge">Y3 Dist from Edge</label>
                    <input type="text" class="form-control" id="Y3_Dist_Edge" value="">
                </div>
                <div class="form-group">
                    <label for="Y4_Bolts">Y4 # Bolts</label>
                    <input type="text" class="form-control" id="Y4_Bolts" value="">
                </div>
                <div class="form-group">
                    <label for="Y4_Dist_Edge">Y4 Dist from Edge</label>
                    <input type="text" class="form-control" id="Y4_Dist_Edge" value="">
                </div>
                <div class="form-group">
                    <label for="material">Material</label>
                    <input type="text" class="form-control" id="material" value="">
                </div>
                <div class="form-group">
                    <label for="weight">Weight</label>
                    <input type="text" class="form-control" id="weight" value="">
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" class="form-control" id="url" value="">
                </div>
                <div class="block-content row justify-content-center border-top">
                    <div class="col-9">
                        <button type="btn" class="btn btn-block btn-hero-primary" onclick="submitStanchion()">
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
                    "url": "{{ url('getCustomStanchion') }}",
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

@include('customequipment.stanchion.script')
@endsection