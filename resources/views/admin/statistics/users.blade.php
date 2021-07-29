@extends('admin.layout')

@section('content')
<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Admin Panel
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Statistics
                </h2>
                <!-- <span class="badge badge-success mt-2">
                    <i class="fa fa-spinner fa-spin mr-1"></i> Running
                </span> -->
            </div>
        </div>
    </div>
</div>

<div class="row m-3">
    <div class="col-md-6 col-xl-3">
        <a class="block block-rounded block-link-shadow bg-primary" href="javascript:void(0)">
            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                <div>
                    <i class="fa fa-2x fa-folder-open text-primary-lighter"></i>
                </div>
                <div class="ml-3 text-right">
                    <p class="text-white font-size-h3 font-w300 mb-0" id="openJobsCount">
                        0
                    </p>
                    <p class="text-white mb-0">
                        Open Jobs
                    </p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3">
        <a class="block block-rounded block-link-shadow bg-success" href="javascript:void(0)">
            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                <div>
                    <i class="fa fa-2x fa-folder text-primary-lighter"></i>
                </div>
                <div class="ml-3 text-right">
                    <p class="text-white font-size-h3 font-w300 mb-0" id="completedJobsCount">
                        0
                    </p>
                    <p class="text-white mb-0">
                        Completed Jobs
                    </p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3">
        <a class="block block-rounded block-link-shadow bg-danger" href="javascript:void(0)">
            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                <div class="mr-3">
                    <p class="text-white font-size-h3 font-w300 mb-0" id="totalChats">
                        0
                    </p>
                    <p class="text-white mb-0">
                        Total Chats
                    </p>
                </div>
                <div>
                    <i class="fab fa-2x fa-rocketchat text-black-50"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3">
        <a class="block block-rounded block-link-shadow bg-warning" href="javascript:void(0)">
            <div class="block-content-full d-flex align-items-center justify-content-between" style="padding: 0.8rem;">
                <div class="mr-3">
                    <p class="text-white mb-0" id="maxChatCompany">
                        -- Company --
                    </p>
                    <p class="text-white mb-0" id="maxChatProject">
                        -- Project Name --
                    </p>
                    <p class="text-white mb-0">
                        Max Chat Project
                    </p>
                </div>
                <div>
                    <i class="fa fa-2x fa-bookmark text-black-50"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Users Summary</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="infos" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th style="width:22%">Company Name</th>
                            <th style="width:22%">Username</th>
                            <th style="width:10%;">Opened Jobs</th>
                            <th style="width:10%;">Completed Jobs</th>
                            <th style="width:10%;">Chats Total</th>
                            <th style="width:10%;">Average Chats</th>
                            <th style="min-width: 100px;">Details</th>
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
                                <!-- <input type="text" placeholder="Search Company" class="searchBox" id="companyFilter">  -->
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
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

        $.ajax({
            url:"getCompanySummary",
            type:'post',
            success:function(res){
                if(res){
                    $("#openJobsCount").html(res.opened);
                    $("#completedJobsCount").html(res.completed);
                    $("#totalChats").html(res.chatstotal);
                    if(res.maxchat){
                        $("#maxChatCompany").html(res.maxchat.companyName);
                        $("#maxChatProject").html(res.maxchat.projectNumber + '. ' + res.maxchat.projectName + ' - ' + res.maxchat.count + ' chats');
                    } else{
                        $("#maxChatCompany").html('0');
                        $("#maxChatProject").html('No chats available.');
                    }
                } else
                    console.log('Error: ', res);
            },
            error: function(xhr, status, error) {
                swal.fire({ title: "Error",
                        text: "Error happened while getting company summary. Please try again later.",
                        icon: "error",
                        confirmButtonText: `OK` });
            }
        });

        var table = $('#infos').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "orderCellsTop": true,
            "pageLength" : 50,
            "ajax":{
                    "url": "{{ url('getUserMetrics') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
            "columns": [
                { "data": "id" },
                { "data": "companyname" },
                { "data": "username" },
                { "data": "opened" },
                { "data": "completed" },
                { "data": "totalchats" },
                { "data": "avgchats" },
                { "data": "actions", "orderable": false }
            ]	 
        });

        $("#companyFilter").on('change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();

            $.ajax({
                url:"getCompanySummary",
                type:'post',
                data: {companyId: this.value},
                success:function(res){
                    if(res){
                        $("#openJobsCount").html(res.opened);
                        $("#completedJobsCount").html(res.completed);
                        $("#totalChats").html(res.chatstotal);
                        if(res.maxchat){
                            $("#maxChatCompany").html(res.maxchat.companyName);
                            $("#maxChatProject").html(res.maxchat.projectNumber + '. ' + res.maxchat.projectName + ' - ' + res.maxchat.count + ' chats');
                        } else{
                            $("#maxChatCompany").html('0');
                            $("#maxChatProject").html('No chats available.');
                        }
                    } else
                        console.log('Error: ', res);
                },
                error: function(xhr, status, error) {
                    swal.fire({ title: "Error",
                            text: "Error happened while changing company. Please try again later.",
                            icon: "error",
                            confirmButtonText: `OK` });
                }
            });
        });
    })
</script>

@endsection