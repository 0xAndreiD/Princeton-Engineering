@extends('clientadmin.layout')

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
                    Manage your company users here
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
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th style="width:20%">Name</th>
                            <th style="width:20%;">Email</th>
                            <th style="width:20%;">UserRole</th>
                            <th style="width:20%;">UserNumber</th>
                            <th style="min-width: 150px;">Action</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="searchHead"> <input type="text" placeholder="Search Name" class="searchBox" id="nameFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Email" class="searchBox" id="emailFilter"> </th>
                            <th class="searchHead">
                                <select placeholder="Search Role" class="searchBox" id="roleFilter">
                                    <option value="">All</option>
                                    <option value="1">Client</option>
                                    <option value="0">User</option>
                                </select>
                            </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Number" class="searchBox" id="userNumFilter"> </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
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
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Type Your Email">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="userrole">User Role <span class="text-danger">*</span></label><br/>
                                    <select class="js-select2 form-control" id="userrole" name="userrole">
                                        <option value="1"><span class='badge badge-primary'> Company Admin </span></option>
                                        <option value="0"><span class='badge badge-info'> User </span></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="userrole">User Number (1: Administrator) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="usernumber" name="usernumber" placeholder="Type Your User Number">
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
                { "data": "userrole" },
                { "data": "usernumber" },
                { "data": "actions", "orderable": false }
            ]	 
        });

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $("#nameFilter, #emailFilter, #userNumFilter").on('keyup change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });

        $("#roleFilter").on('change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });
    });
</script>
@include('admin.user.script')
@endsection