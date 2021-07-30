@extends((Auth::user()->userrole == 2)? 'admin.layout' : 'clientadmin.layout')

@section('content')
<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Check Individual User's statistics here
                </h1>
                <h2 class="h5 text-white mb-0">
                    {{ $user->username }}'s statistics
                </h2>
                <!-- <span class="badge badge-success mt-2">
                    <i class="fa fa-spinner fa-spin mr-1"></i> Running
                </span> -->
            </div>
        </div>
    </div>
</div>
<!-- END Hero -->

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
            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                <div class="mr-3">
                    <p class="text-white font-size-h3 font-w300 mb-0" id="avgChats">
                        0
                    </p>
                    <p class="text-white mb-0">
                        Average Chats
                    </p>
                </div>
                <div>
                    <i class="fa fa-2x fa-comments text-black-50"></i>
                </div>
            </div>
        </a>
    </div>
    <input type="hidden" value="{{ $user->id }}" id="userId">
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
                            <th style="width:15%">Project Number</th>
                            <th style="width:25%;">Project Name</th>
                            <th style="width:10%;">State</th>
                            <th style="width:10%;">Created Time</th>
                            <th style="width:10%;">Submitted Time</th>
                            <th style="width:15%;">Project Status</th>
                            <th style="width:5%;">Chats</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="searchHead">
                                <select placeholder="State" class="searchBox" id="usStateFilter">
                                    <option value="">All</option>
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
                            </th>
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
            url:"getUserSummary",
            type:'post',
            data: { userId: $("#userId").val() },
            success:function(res){
                if(res && res.success){
                    $("#openJobsCount").html(res.info.opened);
                    $("#completedJobsCount").html(res.info.completed);
                    $("#totalChats").html(res.info.totalchats);
                    $("#avgChats").html(res.info.avgchats);
                } else
                    console.log('Error: ', res.message);
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
                    "url": "{{ url('getUserProjects') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}", userId: $("#userId").val() }
                },
            "columns": [
                { "data": "id" },
                { "data": "projectNumber" },
                { "data": "projectName" },
                { "data": "state" },
                { "data": "createdTime" },
                { "data": "submittedTime" },
                { "data": "projectStatus" },
                { "data": "chats" }
            ]	 
        });

        $("#usStateFilter").on('change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });
    })
</script>

@endsection

