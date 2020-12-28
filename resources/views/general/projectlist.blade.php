@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1) ? 'clientadmin.layout' : 'user.layout'))

@section('content')

<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Project List
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Check your projects here
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Project List</h3>
            <div class="block-options">
                <a type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    href="{{ route('rsinput') }}">
                    <i class="fa fa-plus"></i> Add Project
                </a>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="users" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            @if(Auth::user()->userrole == 2)
                            <th class="text-center" style="width: 7%;">ID</th>
                            <th style="width:10%">Company Name</th>
                            <th style="width:10%;">User</th>
                            <th style="width:15%;">Project Name</th>
                            <th style="width:7%;">Project Number</th>
                            <th style="width:10%;">File Name</th>
                            <th style="width:10%;">Created Time</th>
                            <th style="width:10%;">Submitted Time</th>
                            <th style="width:8%;">Project Status</th>
                            <th style="width:8%;">Plan Status</th>
                            <th style="min-width: 90px;">Action</th>
                            @else
                            <th class="text-center" style="width: 7%;">ID</th>
                            <th style="width:15%;">User</th>
                            <th style="width:15%;">Project Name</th>
                            <th style="width:7%;">Project Number</th>
                            <th style="width:15%;">Created Time</th>
                            <th style="width:15%;">Submitted Time</th>
                            <th style="width:15%;">Project Status</th>
                            <th style="width:10%;">Plan Status</th>
                            <th style="min-width: 70px;">Action</th>
                            @endif
                        </tr>
                        <tr>
                            @if(Auth::user()->userrole == 2)
                            <th></th>
                            <th class="searchHead"> <input type="text" placeholder="Search Company" class="searchBox" id="companyFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search User" class="searchBox" id="userFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Name" class="searchBox" id="projectNameFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Number" class="searchBox" id="projectNumberFilter"> </th>
                            <th></th>
                            <th class="searchHead">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="created_from" name="created_from_datetime" placeholder="From" style="margin-bottom: 5px;">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="created_to" name="created_to_datetime" placeholder="To">
                            </th>
                            <th class="searchHead">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="submitted_from" name="submitted_from_datetime" placeholder="From" style="margin-bottom: 5px;">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="submitted_to" name="submitted_to_datetime" placeholder="To">
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            @else
                            <th></th>
                            <th class="searchHead"> <input type="text" placeholder="Search User" class="searchBox" id="userFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Name" class="searchBox" id="projectNameFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Number" class="searchBox" id="projectNumberFilter"> </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/pages/common.js') }}"></script>

<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        var table = $('#users').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "orderCellsTop": true,
            "ajax":{
                    "url": "{{ url('getProjectList') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(data){ 
                        data.created_from = $("#created_from").val();
                        data.created_to = $("#created_to").val();
                        data.submitted_from = $("#submitted_from").val();
                        data.submitted_to = $("#submitted_to").val();
                    }
                },
            "columns": [
                @if(Auth::user()->userrole == 2)
                { "data": "id" },
                { "data": "companyname" },
                { "data": "username" },
                { "data": "projectname" },
                { "data": "projectnumber" },
                { "data": "requestfile" },
                { "data": "createdtime" },
                { "data": "submittedtime" },
                { "data": "projectstate" },
                { "data": "planstatus" },
                { "data": "actions", "orderable": false }
                @else
                { "data": "id" },
                { "data": "username" },
                { "data": "projectname" },
                { "data": "projectnumber" },
                { "data": "createdtime" },
                { "data": "submittedtime" },
                { "data": "projectstate" },
                { "data": "planstatus" },
                { "data": "actions", "orderable": false }
                @endif
            ],
            @if(Auth::user()->userrole == 2)
            "order": [[ 6, "desc" ]]
            @else
            "order": [[ 4, "desc" ]]
            @endif
        });
        
        //console.log(table.column("2:visible"));

        $("#userFilter, #projectNameFilter, #projectNumberFilter").on('keyup change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });

        $("#created_from, #created_to, #submitted_from, #submitted_to").on('change', function() {
            console.log(this.value);
            table.draw();
        });

        @if(Auth::user()->userrole == 2)
        $("#companyFilter").on('keyup change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });
        @endif

    });
</script>

@if (Auth::user()->userrole == 2)
    @include('general.projectscript')
@endif

@endsection