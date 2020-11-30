@extends('user.layout')

@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    User List Panel
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
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="users" class="table table-bordered table-striped table-vcenter" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">ID</th>
                            <th style="width:25%">Name</th>
                            <th style="width:25%;">Email</th>
                            <!-- <th class="text-center" style="width: 15%;">Verified</th>
                            <th class="d-none d-md-table-cell" style="width: 10%;">Role</th>
                            <th class="d-none d-md-table-cell" style="width: 10%;">Company</th>
                            <th class="d-none d-md-table-cell" style="width: 10%;">Number</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">Membership</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">MemberRole</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Created Time</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Updated Time</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Registered</th> -->
                            <th class="text-center" style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection