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
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th style="width:10%">Company Name</th>
                            <th style="width:10%;">User</th>
                            <th style="width:15%;">Project Name</th>
                            <th style="width:10%;">Project Number</th>
                            <th style="width:5%;">File Name</th>
                            <th style="width:10%;">Created Time</th>
                            <th style="width:10%;">Submitted Time</th>
                            <th style="width:5%;">Plan Status</th>
                            <th style="width:5%;">Project Status</th>
                            <th style="min-width: 150px;">Action</th>
                            @else
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th style="width:15%;">User</th>
                            <th style="width:20%;">Project Name</th>
                            <th style="width:10%;">Project Number</th>
                            <th style="width:10%;">Created Time</th>
                            <th style="width:10%;">Submitted Time</th>
                            <th style="width:5%;">Plan Status</th>
                            <th style="width:10%;">Project Status</th>
                            <th style="min-width: 70px;">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/pages/common.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#users').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax":{
                    "url": "{{ url('getProjectList') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
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
                { "data": "planstatus" },
                { "data": "projectstate" },
                { "data": "actions", "orderable": false }
                @else
                { "data": "id" },
                { "data": "username" },
                { "data": "projectname" },
                { "data": "projectnumber" },
                { "data": "createdtime" },
                { "data": "submittedtime" },
                { "data": "planstatus" },
                { "data": "projectstate" },
                { "data": "actions", "orderable": false }
                @endif
            ]	 
        });

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    });
</script>

@if (Auth::user()->userrole == 2)
    @include('general.projectscript')
@endif

@endsection