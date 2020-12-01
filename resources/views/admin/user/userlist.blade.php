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
                            <th class="text-center" style="width: 50px;">ID</th>
                            <th style="width:15%">Name</th>
                            <th style="width:20%;">Email</th>
                            <th style="width:10%;">Company</th>
                            <th style="width:20%;">User Number</th>
                            <th style="width:20%;">Membership</th>
                            <!-- <th class="text-center" style="width: 15%;">Verified</th>
                            <th class="d-none d-md-table-cell" style="width: 10%;">Role</th>
                            <th class="d-none d-md-table-cell" style="width: 10%;">Company</th>
                            <th class="d-none d-md-table-cell" style="width: 10%;">Number</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">Membership</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">MemberRole</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Created Time</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Updated Time</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Registered</th> -->
                            <th style="width: 100px;">Action</th>
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
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Modal Title</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="row push">
                                <div class="col-12">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Type Your Name">
                                    <input type="hidden" class="form-control" id="userid" name="userid">
                                </div>
                            </div>
                            <div class="row push">
                                <div class="col-12">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Type Your Email">
                                </div>
                            </div>
                            <div class="row push">
                                <div class="col-12">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                            <div class="row push">
                                <div class="col-12">
                                    <label for="company">Company</label>
                                    <select class="form-control" id="company" name="company">
                                        @foreach ($companyList as $company)
                                            <option value="{{$company->id}}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row push">
                                <div class="col-12">
                                    <label for="usernumber">User Number (1: Administrator)</label>
                                    <input type="text" class="form-control" id="usernumber" name="usernumber" placeholder="Type Your User Number">
                                </div>
                            </div>
                            <div class="row push">
                                <div class="col-12">
                                    <label for="membership">Membership(0: General,1: allowed by Admin)</label>
                                    <input type="text" class="form-control" id="membership" name="membership" placeholder="Type Your Membership">
                                </div>
                            </div>
                        </div>
                        <div class="block-content block-content-full text-right bg-light">
                            <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                            <button id="updateButton" type="button" class="btn btn-sm btn-primary" onclick="updateUser()">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Normal Block Modal -->
        <script src="{{ asset('js/pages/common.js') }}"></script>

        <script>
            $(document).ready(function () {
                $('#users').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
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
                        { "data": "usernumber" },
                        { "data": "membershipid" },
                        { "data": "actions", "orderable": false }
                    ]	 
                });

                $.ajaxSetup({
                    headers:
                    { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });
            });
        </script>
        @include('admin.user.script')
@endsection