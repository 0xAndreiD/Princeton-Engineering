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
                            <th class="text-center" style="width: 5%;">ID</th>
                            <th style="width:15%">Name</th>
                            <th style="width:5%;">Number</th>
                            <th style="width:10%;">Tel No</th>
                            <th style="width:15%;">Address</th>
                            <th style="width:15%;">Email</th>
                            <th style="width:10%;">Website</th>
                            <th style="width:10%;">Max Allow Skip</th>
                            <th style="min-width:150px;">Action</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="searchHead"> <input type="text" placeholder="Search Name" class="searchBox" id="nameFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Number" class="searchBox" id="numberFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Phone" class="searchBox" id="phoneFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Address" class="searchBox" id="addressFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Email" class="searchBox" id="emailFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Website" class="searchBox" id="siteFilter"> </th>
                            <th></th>
                            <th style="display: flex;align-items: center;justify-content: center;">
                                <span class="ml-1" style='writing-mode: vertical-lr;width: 42px;transform: rotateZ(180deg);'>Edit</span>
                                {{-- <span class="ml-4" style='writing-mode: tb-rl;width: 42px;transform: rotateZ(180deg);'>Permit</span> --}}
                                <span class="ml-3" style='writing-mode: tb-rl;width: 42px;transform: rotateZ(180deg);'>Delete</span>
                            </th>
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
                                        <div class="form-group">
                                            <label for="website">Max Allow Skip <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="max_allowable_skip" name="max_allowable_skip" placeholder="10">
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

        <!-- Permit Info Normal Block Modal -->
        <div class="modal" id="modal-permit-normal" tabindex="-1" role="dialog" aria-labelledby="modal-permit-normal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="js-validation" onsubmit="return false;" method="POST" id="permitForm">
                        <div class="block block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">Building Permit Related Information</h3>
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
                                            <label for="usState">State <span class="text-danger">*</span></label>
                                            <input type="hidden" class="form-control" id="permitId" name="id">
                                            <select id="usState" onchange="pullPermit()">
                                                <option value="" selected>Select state</option>
                                                <option value="AL">AL</option>
                                                <option value="AZ">AZ</option>
                                                <option value="AR">AR</option>
                                                <option value="CA">CA</option>
                                                <option value="CO">CO</option>
                                                <option value="CT">CT</option>
                                                <option value="DE">DE</option>
                                                <option value="FL">FL</option>
                                                <option value="GA">GA</option>
                                                <option value="HI">HI</option>
                                                <option value="ID">ID</option>
                                                <option value="IL">IL</option>
                                                <option value="IN">IN</option>
                                                <option value="IA">IA</option>
                                                <option value="KS">KS</option>
                                                <option value="KY">KY</option>
                                                <option value="LA">LA</option>
                                                <option value="ME">ME</option>
                                                <option value="MD">MD</option>
                                                <option value="MA">MA</option>
                                                <option value="MI">MI</option>
                                                <option value="MN">MN</option>
                                                <option value="MS">MS</option>
                                                <option value="MO">MO</option>
                                                <option value="MT">MT</option>
                                                <option value="NE">NE</option>
                                                <option value="NV">NV</option>
                                                <option value="NH">NH</option>
                                                <option value="NJ">NJ</option>
                                                <option value="NM">NM</option>
                                                <option value="NY">NY</option>
                                                <option value="NC">NC</option>
                                                <option value="ND">ND</option>
                                                <option value="OH">OH</option>
                                                <option value="OK">OK</option>
                                                <option value="OR">OR</option>
                                                <option value="PA">PA</option>
                                                <option value="RI">RI</option>
                                                <option value="SC">SC</option>
                                                <option value="SD">SD</option>
                                                <option value="TN">TN</option>
                                                <option value="TX">TX</option>
                                                <option value="UT">UT</option>
                                                <option value="VT">VT</option>
                                                <option value="VA">VA</option>
                                                <option value="WA">WA</option>
                                                <option value="WV">WV</option>
                                                <option value="WI">WI</option>
                                                <option value="WY">WY</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="construction_email">Construction Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="construction_email" 
                                                name="construction_email" placeholder="Enter Construction Email..">
                                        </div>
                                        <div class="form-group">
                                            <label for="registration">License No. OR, if new home, Builder Reg. No. <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="registration" 
                                            name="registration" placeholder="Registration #">
                                        </div>
                                        <div class="form-group">
                                            <label for="exp_date">Expire date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="exp_date" 
                                            name="exp_date" >
                                        </div>
                                        <div class="form-group">
                                            <label for="EIN">Federal Emp. ID No.(EIN) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="EIN" 
                                            name="EIN" placeholder="EIN">
                                        </div>
                                        <div class="form-group">
                                            <label for="fax">Responsible Person in Charge once Work has Begun <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact_person" 
                                            name="responsible_person" placeholder="Enter Person Name..">
                                        </div>
                                        <div class="form-group">
                                            <label for="fax">Contact Tel <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact_phone" 
                                            name="contact_phone" placeholder="212-999-0000">
                                        </div>
                                        <div class="form-group">
                                            <label for="fax">FAX <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="fax" 
                                            name="contact_fax" placeholder="Enter Fax Number..">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="block-content block-content-full text-right bg-light">
                                <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END Permit Info Normal Block Modal -->
        <script src="{{ asset('js/pages/common.js') }}"></script>

        <script>
            $(document).ready(function () {
                var table = $('#companys').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "orderCellsTop": true,
                    "pageLength" : 50,
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
                        { "data": "maxallowskip" },
                        { "data": "actions", "orderable": false }
                    ]	 
                });

                $.ajaxSetup({
                    headers:
                    { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                $("#nameFilter, #numberFilter, #phoneFilter, #addressFilter, #emailFilter, #siteFilter").on('keyup change', function() {
                    table.column($(this).parent().index() + ':visible').search(this.value).draw();
                });
            });
        </script>

        
        @include('admin.company.script')
@endsection