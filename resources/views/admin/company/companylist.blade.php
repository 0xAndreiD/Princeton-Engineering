@extends('admin.layout')

@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Company Management Panel
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
            <h3 class="block-title">Company List</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    onclick="showAddCompany()">
                    <i class="fa fa-plus"></i> Add Comany
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="companys" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">ID</th>
                            <th style="width:15%">Name</th>
                            <th style="width:10%;">Number</th>
                            <th style="width:10%;">Tel No</th>
                            <th style="width:20%;">Address</th>
                            <th style="width:10%;">Email</th>
                            <th style="width:15%;">Website</th>
                            <th style="width:150px;">Action</th>
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
                    <form class="js-validation" onsubmit="mySubmitFunction(event)" method="POST">
                        <div class="block block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">Company Info</h3>
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
                                            <label for="number">Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="number" name="digits" placeholder="Enter A Number..">
                                            <input type="hidden" class="form-control" id="id" name="id">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter A Name..">
                                        </div>
                                        <div class="form-group">
                                            <label for="telno">Tel No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="telno" name="telno" placeholder="212-999-0000">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Your Address..">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Your A Valid Email..">
                                        </div>
                                        <div class="form-group">
                                            <label for="website">Website <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="website" name="website" placeholder="http://example.com">
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
                $('#companys').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "ajax":{
                            "url": "{{ url('getCompanyData') }}",
                            "dataType": "json",
                            "type": "POST",
                            "data":{ _token: "{{csrf_token()}}"}
                        },
                    "columns": [
                        { "data": "id" },
                        { "data": "name" },
                        { "data": "number" },
                        { "data": "telno" },
                        { "data": "address" },
                        { "data": "email" },
                        { "data": "website" },
                        { "data": "actions", "orderable": false }
                    ]	 
                });

                $.ajaxSetup({
                    headers:
                    { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });
            });
        </script>

        
        @include('admin.company.script')
@endsection