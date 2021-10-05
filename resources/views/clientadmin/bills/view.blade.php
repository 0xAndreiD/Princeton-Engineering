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
                    Manage your bills here
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
            <h3 class="block-title">Your Bills</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="infos" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th style="width:14%">Issued Date</th>
                            <th style="width:12%">Issued From</th>
                            <th style="width:12%">Issued To</th>
                            <th style="width:15%">Paid / Total</th>
                            <th style="width:10%">Amount</th>
                            <th style="width:10%">State</th>
                            <th style="width:10%">Actions</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="display: flex; align-items: center; justify-content: center;">
                                <span class="ml-1" style='writing-mode: vertical-lr;width: 17px;transform: rotateZ(180deg);'>Open Invoice</span>
                                <span class="ml-2" style='writing-mode: tb-rl;width: 26px;transform: rotateZ(180deg);'>Pay Now</span>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
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
                    "data":{ _token: "{{csrf_token()}}"}
                },
            "columns": [
                { "data": "id" },
                { "data": "issuedDate" },
                { "data": "issuedFrom" },
                { "data": "issuedTo" },
                { "data": "jobCount" },
                { "data": "amount" },
                { "data": "state" },
                { "data": "actions", "orderable": false }
            ]	 
        });
    })
</script>

@include('clientadmin.bills.script')
@endsection