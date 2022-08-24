@extends('admin.layout')

@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    User Management Panel
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Panel Status
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">User List</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    onclick="showAddUser()">
                    <i class="fa fa-plus"></i> Add User
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="users" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">ID</th>
                            <th style="width:15%">Name</th>
                            <th style="width:15%;">Email</th>
                            <th style="width:15%;">Company</th>
                            <th style="width:5%;">UserRole</th>
                            <th style="width:5%;">UserNumber</th>
                            <th style="width:10%;">Distance Limit(Miles)</th>
                            <th style="width:5%;">Ask Two Factor</th>
                            <th style="width:10%;">Allow Permit</th>
                            <th style="min-width: 150px;">Action</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="searchHead"> <input type="text" placeholder="Search Name" class="searchBox" id="nameFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Email" class="searchBox" id="emailFilter"> </th>
                            <th class="searchHead">
                                <select placeholder="Search Company" class="searchBox" id="companyFilter">
                                    <option value="">All</option>
                                    @foreach($companyList as $company)
                                        <option value="{{ $company['company_name'] }}">{{ $company['company_name'] }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th class="searchHead">
                                <select placeholder="Search Role" class="searchBox" id="roleFilter">
                                    <option value="">All</option>
                                    <option value="2">Super Admin</option>
                                    <option value="3">Junior Super Admin</option>
                                    <option value="4">Reviewer</option>
                                    <option value="1">Client</option>
                                    <option value="0">User</option>
                                    <option value="5">Printer</option>
                                    <option value="6">Consultant Admin</option>
                                    <option value="7">Consultant User</option>
                                </select>
                            </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Number" class="searchBox" id="userNumFilter"> </th>
                            <th></th>
                            <th class="searchHead">
                                <select placeholder="Search Role" class="searchBox" id="verifyFilter">
                                    <option value="">All</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </th>
                            <th class="searchHead">
                                <select placeholder="Search Role" class="searchBox" id="permitFilter">
                                    <option value="">All</option>
                                    <option value="0">No</option>
                                    <option value="1">Regular User with Permit</option>
                                    <option value="2">Only Permit</option>
                                </select>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Normal Block Modal -->
<div class="modal" id="modal-block-normal" tabindex="-1" role="dialog" aria-labelledby="modal-block-normal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="js-validation" onsubmit="return false;" method="POST" id="profileForm">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">User Info</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter A Name..">
                                    <input type="hidden" class="form-control" id="userid" name="userid">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Type Email">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="company">Company <span class="text-danger">*</span></label><br/>
                                    <select class="form-control" id="company" name="company">
                                        @foreach ($companyList as $company)
                                            <option value="{{$company->id}}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="userrole">User Role <span class="text-danger">*</span></label><br/>
                                    <select class="form-control" id="userrole" name="userrole">
                                        <option value="0"><span class='badge badge-info'> User </span></option>
                                        <option value="1"><span class='badge badge-primary'> Client </span></option>
                                        <option value="3"><span class='badge badge-warning'> Junior Super Admin </span></option>
                                        <option value="2"><span class='badge badge-danger'> Super Admin </span></option>
                                        <option value="4"><span class='badge badge-secondary'> Reviewer </span></option>
                                        <option value="5"><span class='badge badge-secondary'> Printer </span></option>
                                        <option value="6"><span class='badge badge-secondary'> Consultant Admin </span></option>
                                        <option value="7"><span class='badge badge-secondary'> Consultant User </span></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="userrole">User Number (1: Administrator) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="usernumber" name="usernumber" placeholder="Type User Number">
                                </div>
                                <div class="form-group">
                                    <label for="distance_limit">Distance Limit(Miles) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="distance_limit" name="distance_limit" placeholder="Type Distance Limit">
                                </div>
                                <div class="form-group">
                                    <label for="userrole">Ask Two Factor Verification <span class="text-danger">*</span></label><br/>
                                    <select class="form-control" id="ask_two_factor" name="ask_two_factor">
                                        <option value="1"><span class='badge badge-primary'> Yes </span></option>
                                        <option value="0"><span class='badge badge-danger'> No </span></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="userrole">Allow Permit Submit <span class="text-danger">*</span></label><br/>
                                    <select class="form-control" id="allow_permit" name="allow_permit">
                                        <option value="0"><span class='badge badge-primary'> No </span></option>
                                        <option value="1"><span class='badge badge-danger'> Regular user with Permit Accessible </span></option>
                                        <option value="2"><span class='badge badge-danger'> Only Permit Accessible </span></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="block-content block-content-full text-right bg-light">
                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                        <button id="updateButton" type="submit" class="btn btn-sm btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Normal Block Modal -->
<script src="{{ asset('js/pages/common.js') }}"></script>

<script>
    $(document).ready(function () {
        var table = $('#users').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "orderCellsTop": true,
            "pageLength" : 50,
            "ajax":{
                    "url": "{{ url('getUserData') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
            "columns": [
                { "data": "id" },
                { "data": "username" },
                { "data": "email" },
                { "data": "companyname" },
                { "data": "userrole" },
                { "data": "usernumber" },
                { "data": "distance" },
                { "data": "ask_two_factor" },
                { "data": "allow_permit" },
                { "data": "actions", "orderable": false }
            ]	 
        });

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $("#company").select2({ width: '100%' });
        $("#userrole").select2({ width: '100%' });

        $("#nameFilter, #emailFilter, #userNumFilter").on('keyup change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });

        $("#companyFilter, #roleFilter, #verifyFilter, #permitFilter").on('change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });
    });
</script>
@include('admin.user.script')
@endsection