@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : 'user.layout'))


@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Construction Permit & PIL PDF Files
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    You can manage permit & PIL PDF files here.
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">PDF List</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    onclick="showAddPermit()">
                    <i class="fa fa-plus"></i> Upload PDF
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="permitFiles" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th style="width:15%">File Name</th>
                            <th style="width:10%;">State</th>
                            <th style="width:20%;">Description</th>
                            <th style="width:15%;">TabName</th>
                            <th style="width:10%;">Form Type</th>
                            <th style="width:10%;">Configured</th>
                            <th style="min-width: 200px;">Actions</th>
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
                    <a class="text-white font-w600" href="#" id="sidebar-title">Edit PDF</a>
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
        <form class="js-validation" onsubmit="return false;" method="POST" id="pdfForm">
        <!-- Side Overlay Tabs -->
        <div class="block block-transparent pull-x pull-t">
            <div class="block-content tab-content overflow-hidden side-view">
                <div class="form-group">
                    <label for="file">Upload PDF file</label>
                    <input type="file" class="form-control" id="file" name="file">
                </div>
                <div class="form-group">
                    <label for="filename">Filename</label>
                    <input type="text" class="form-control" id="filename" name="filename" value="" disabled>
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <select id="state" name="state" class="form-control">
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" id="description" name="description" value="">
                </div>
                <div class="form-group">
                    <label for="tabname">Tab Name</label>
                    <input type="text" class="form-control" id="tabname" name="tabname" value="">
                </div>
                <div class="form-group">
                    <label for="formtype">Form Type</label>
                    <select id="formtype" name="formtype" class="form-control">
                        <option data-value="Permit">Permit</option>
                        <option data-value="PIL">PIL</option>
                    </select>
                </div>
                <div class="block-content row justify-content-center border-top">
                    <div class="col-9">
                        <button type="btn" class="btn btn-block btn-hero-primary" onclick="submitPermit()">
                            <i class="fa fa-fw fa-save mr-1"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Side Overlay Tabs -->
        </form>
    </div>
    <!-- END Side Content -->
</aside>

<script src="{{ asset('js/pages/common.js') }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>

<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        var table = $('#permitFiles').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "orderCellsTop": true,
            "pageLength" : 50,
            "ajax":{
                    "url": "{{ url('getPermitFiles') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
            "columns": [
                { "data": "id" },
                { "data": "filename" },
                { "data": "state" },
                { "data": "description" },
                { "data": "tabname" },
                { "data": "typebadge", "orderable": false },
                { "data": "configured" },
                { "data": "actions", "orderable": false }
            ]	 
        });

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        var availableUSState = [
        "AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT",
        "NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY"
        ];

        var loadStateOptions = function() {
            for (index=0; index<availableUSState.length; index++) 
            {
                $('#state').append(`<option data-value="${availableUSState[index]}"> 
                        ${availableUSState[index]} 
                </option>`);
            }
        }

        loadStateOptions();
    });
</script>

@include('admin.permit.script')
@endsection