@extends('admin.layout')

@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Billing Info Management Panel
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Manage Company Billing Infos here
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Client List</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="clients" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">ID</th>
                            <th style="width:15%">Client Name</th>
                            <th style="width:15%;">Client Number</th>
                            <th style="width:15%;">Billing Type</th>
                            <th style="width:15%;">Amount Per Job</th>
                            <th style="width:15%;">Send Invoice</th>
                            <th style="width:15%;">Block on Failure</th>
                            <th style="min-width:150px;">Action</th>
                        </tr>
                        {{-- <tr>
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
                                <span class="ml-4" style='writing-mode: tb-rl;width: 42px;transform: rotateZ(180deg);'>Permit</span>
                                <span class="ml-1" style='writing-mode: tb-rl;width: 42px;transform: rotateZ(180deg);'>Delete</span>
                            </th>
                        </tr> --}}
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
                            <h3 class="block-title">Client Billing Info</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content" style="max-height: 700px; overflow: auto;">
                            <div class="row items-push">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="billing_type">Billing Type <span class="text-danger">*</span></label><br/>
                                        <select class="form-control" id="billing_type" name="billing_type">
                                            <option value="0"> Bill on Complete State </option>
                                            <option value="1"> Bill on Job Created Date </option>
                                        </select>
                                        <input type="hidden" class="form-control" id="id" name="id">
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Amound Per Job <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="amount" name="amount" placeholder="ex: 0.00">
                                    </div>
                                    <div class="form-group">
                                        <label for="send_invoice">Send Invoice <span class="text-danger">*</span></label><br/>
                                        <select class="form-control" id="send_invoice" name="send_invoice">
                                            <option value="0"> No, do not send and directly charge </option>
                                            <option value="1"> Yes, send invoice before billing </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="block_on_fail">Block features on bill failure <span class="text-danger">*</span></label><br/>
                                        <select class="form-control" id="block_on_fail" name="block_on_fail">
                                            <option value="0"> No </option>
                                            <option value="1"> Yes </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="bname">Card Issuer Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cardname" name="cardname" placeholder="ex: John Doe">
                                    </div>
                                    <div class="form-group">
                                        <label for="bname">Card Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cardnumber" name="cardnumber" placeholder="ex: John Doe">
                                    </div>
                                    <div class="form-group">
                                        <label for="bname">Expiration Date <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="expiration_date" name="expiration_date" placeholder="ex: John Doe">
                                    </div>
                                    <div class="form-group">
                                        <label for="bname">Security Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="security_code" name="security_code" placeholder="ex: John Doe">
                                    </div>
                                    <div class="form-group">
                                        <label for="bname">Billing Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bname" name="bname" placeholder="ex: John Doe">
                                    </div>
                                    <div class="form-group">
                                        <label for="bmail">Billing Email <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bmail" name="bmail" placeholder="ex: johndoe@gmail.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="baddress">Billing Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="baddress" name="baddress" placeholder="ex: 2154 S. Las Flores">
                                    </div>
                                    <div class="form-group">
                                        <label for="bcity">Billing City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bcity" name="bcity" placeholder="ex: Miami">
                                    </div>
                                    <div class="form-group">
                                        <label for="bstate">Billing State <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bstate" name="bstate" placeholder="ex: MA">
                                    </div>
                                    <div class="form-group">
                                        <label for="bzip">Billing Zip <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bzip" name="bzip" placeholder="ex: 11011">
                                        <input type="hidden" class="form-control" id="billing_same_chk" name="billing_same_chk">
                                    </div>
                                    <div class="form-group">
                                        <label for="bname">Shipping Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="sname" name="sname" placeholder="ex: John Doe">
                                    </div>
                                    <div class="form-group">
                                        <label for="smail">Shipping Email <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="smail" name="smail" placeholder="ex: johndoe@gmail.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="saddress">Shipping Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="saddress" name="saddress" placeholder="ex: 2154 S. Las Flores">
                                    </div>
                                    <div class="form-group">
                                        <label for="scity">Shipping City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="scity" name="scity" placeholder="ex: Miami">
                                    </div>
                                    <div class="form-group">
                                        <label for="sstate">Shipping State <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="sstate" name="sstate" placeholder="ex: MA">
                                    </div>
                                    <div class="form-group">
                                        <label for="szip">Shipping Zip <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="szip" name="szip" placeholder="ex: 11011">
                                        <input type="hidden" class="form-control" id="shipping_same_chk" name="shipping_same_chk">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="block-content block-content-full text-right bg-light">
                            <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                            <button id="updateButton" class="btn btn-sm btn-primary" onclick="updateBilling()">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Normal Block Modal -->

        <script src="{{ asset('js/pages/common.js') }}"></script>

        <script>
            $(document).ready(function () {
                var table = $('#clients').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "orderCellsTop": true,
                    "pageLength" : 50,
                    "ajax":{
                            "url": "{{ url('getCompanyBilling') }}",
                            "dataType": "json",
                            "type": "POST",
                            "data":{ _token: "{{csrf_token()}}"}
                        },
                    "columns": [
                        { "data": "id" },
                        { "data": "name" },
                        { "data": "number" },
                        { "data": "billing_type" },
                        { "data": "amount" },
                        { "data": "send_invoice" },
                        { "data": "block_on_fail" },
                        { "data": "actions", "orderable": false }
                    ]	 
                });

                $.ajaxSetup({
                    headers:
                    { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                // $("#nameFilter, #numberFilter, #phoneFilter, #addressFilter, #emailFilter, #siteFilter").on('keyup change', function() {
                //     table.column($(this).parent().index() + ':visible').search(this.value).draw();
                // });
            });
        </script>

        
        @include('admin.billing.script')
@endsection