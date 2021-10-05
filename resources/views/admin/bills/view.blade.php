@extends('admin.layout')

@section('content')
<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Bill histories
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Manage all clients' bills here
                </h2>
                <!-- <span class="badge badge-success mt-2">
                    <i class="fa fa-spinner fa-spin mr-1"></i> Running
                </span> -->
            </div>
        </div>
    </div>
</div>

<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Client Bills</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    onclick="addBill()">
                    <i class="fa fa-plus"></i> Add Bill
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="infos" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 7%;">ID</th>
                            <th style="width:17%">Company Name</th>
                            <th style="width:12%">Issued Date</th>
                            <th style="width:12%">Issued From</th>
                            <th style="width:12%">Issued To</th>
                            <th style="width:10%">Paid / Total</th>
                            <th style="width:10%">Amount</th>
                            <th style="width:10%">State</th>
                            <th style="width:10%">Actions</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="searchHead">
                                <select placeholder="Search Company" class="searchBox" id="companyFilter">
                                    <option value="">All</option>
                                    @foreach($companyList as $company)
                                        <option value="{{ $company['id'] }}">{{ $company['company_name'] }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th class="searchHead">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="issued_at" name="issued_at_datetime" placeholder="Select Date">
                            </th>
                            <th class="searchHead">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="issued_from" name="issued_from_datetime" placeholder="Select From">
                            </th>
                            <th class="searchHead">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="issued_to" name="issued_to_datetime" placeholder="Select To">
                            </th>
                            <th></th>
                            <th></th>
                            <th class="searchHead">
                                <span class='badge dropdown-toggle job-dropdown' id="stateFilter" style='background-color: #fff; color: #000;' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> All </span>
                                <div class='dropdown-menu' aria-labelledby='state_{$bill->id}'>
                                    <a class='dropdown-item' href="javascript:changeStateFilter('')" style='background-color: #fff; color: #000;'>All</a>
                                    <a class='dropdown-item' href='javascript:changeStateFilter(1)' style='color: white; background-color: #e04f1a;'>Unpaid</a>
                                    <a class='dropdown-item' href='javascript:changeStateFilter(2)' style='color: white; background-color: rgb(255, 177, 25);'>Failed</a>
                                    <a class='dropdown-item' href='javascript:changeStateFilter(3)' style='color: white; background-color: #82b54b;'>Paid</a>
                                    <a class='dropdown-item' href='javascript:changeStateFilter(4)' style='color: white; background-color: #343a40;'>Deleted</a>
                                </div>
                            </th>
                            <th style="display: flex; align-items: center; justify-content: center;">
                                <span class="ml-2" style='writing-mode: vertical-lr;width: 17px;transform: rotateZ(180deg);'>Edit</span>
                                <span class="ml-2" style='writing-mode: vertical-lr;width: 17px;transform: rotateZ(180deg);'>Open Invoice</span>
                                <span class="ml-2" style='writing-mode: tb-rl;width: 26px;transform: rotateZ(180deg);'>Pay Now</span>
                                <span class="ml-2" style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 21px;'>Mark As Paid</span>
                                <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 29px;'>Delete</span>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bill Management Modal -->
<div class="modal" id="billmodal" tabindex="-1" role="dialog" aria-labelledby="billmodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="js-validation" onsubmit="return false;" method="POST" id="billUpdateForm">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Invoice Details</h3>
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
                                    <label for="company">Company <span class="text-danger">*</span></label><br/>
                                    <select class="form-control" id="company" name="company">
                                        @foreach ($companyList as $company)
                                            <option value="{{$company->id}}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="id" name="id">
                                </div>
                                <div class="form-group">
                                    <label for="issuedAt">Issued DateTime <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="issuedAt" name="issuedAt" placeholder="Enter Issued Datetime...">
                                </div>
                                <div class="form-group">
                                    <label for="issuedFrom">Issued From <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="issuedFrom" name="issuedFrom" placeholder="Enter Issued From...">
                                </div>
                                <div class="form-group">
                                    <label for="issuedTo">Issued To <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="issuedTo" name="issuedTo" placeholder="Enter Issued To...">
                                </div>
                                <div class="form-group">
                                    <label for="jobCount">Job Counts <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="jobCount" name="jobCount" placeholder="Enter Job Counts...">
                                </div>
                                <div class="form-group">
                                    <label for="jobIds">Job Ids <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="jobIds" name="jobIds" placeholder="Enter Job Ids with comma...">
                                </div>
                                <div class="form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter total amount...">
                                </div>
                                <div class="form-group">
                                    <label for="state">State <span class="text-danger">*</span></label><br/>
                                    <select class="form-control" id="state" name="state">
                                        <option value="0">Unpaid</option>
                                        <option value="1">Failed</option>
                                        <option value="2">Paid</option>
                                        <option value="3">Deleted</option>
                                    </select>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-dark mb-1 mr-2">
                                    <input type="checkbox" class="custom-control-input" id="updatePDF" name="updatePDF" checked>
                                    <label style="cursor: pointer;" class="custom-control-label" for="updatePDF">Update Invoice PDF</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-right bg-light">
                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary" onclick="saveBill()">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#infos').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "orderCellsTop": true,
            "pageLength" : 50,
            "ajax":{
                    "url": "{{ url('getBills') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(data){
                        data.issued_at = $("#issued_at").val();
                        data.issued_from = $("#issued_from").val();
                        data.issued_to = $("#issued_to").val();
                    }
                },
            "columns": [
                { "data": "id" },
                { "data": "companyname" },
                { "data": "issuedDate" },
                { "data": "issuedFrom" },
                { "data": "issuedTo" },
                { "data": "jobCount" },
                { "data": "amount" },
                { "data": "state" },
                { "data": "actions", "orderable": false }
            ]	 
        });

        $("#companyFilter").on('change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });

        changeStateFilter = function(state){
            if(state == '') { $("#stateFilter").css('background-color', '#fff'); $("#stateFilter").css('color', '#000'); $("#stateFilter").html('All'); }
            else if(state == 1) { $("#stateFilter").css('background-color', '#e04f1a'); $("#stateFilter").css('color', '#fff'); $("#stateFilter").html('Unpaid'); }
            else if(state == 2) { $("#stateFilter").css('background-color', 'rgb(255, 177, 25)'); $("#stateFilter").css('color', '#fff'); $("#stateFilter").html('Failed'); }
            else if(state == 3) { $("#stateFilter").css('background-color', '#82b54b'); $("#stateFilter").css('color', '#fff'); $("#stateFilter").html('Paid'); }
            else if(state == 4) { $("#stateFilter").css('background-color', '#343a40'); $("#stateFilter").css('color', '#fff'); $("#stateFilter").html('Deleted'); }
            table.column('7:visible').search(state).draw();
        }

        $("#issued_at, #issued_from, #issued_to").on('change', function() {
            table.draw();
        });
    })
</script>

@include('admin.bills.script')
@endsection