@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : (Auth::user()->userrole == 4 ? 'reviewer.layout' : 'user.layout')))

@section('content')

<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Project List
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Check your projects here
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<div class="content" style="text-align:left; width: 100%; padding: 5px;">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Project List</h3>
            <!-- <div class="block-options">
                <a type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    href="{{ route('rsinput') }}">
                    <i class="fa fa-plus"></i> Add Project
                </a>
            </div> -->
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="projects" class="table table-bordered table-striped table-vcenter text-center" style="width:100%; min-height: 350px;">
                    <thead>
                        <tr>
                            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
                            <th class="text-center" style="width: 7%;">ID</th>
                            <th style="width:10%">Company Name</th>
                            <th style="width:8%;">User</th>
                            <th style="width:7%;">Project Number</th>
                            <th style="width:8%;">Project Name</th>
                            <th style="width:4%;">State</th>
                            <th style="width:6%;">File Name</th>
                            <th style="width:8%;">Created Time</th>
                            <th style="width:8%;">Submitted Time</th>
                            <th style="width:8%;">Project Status</th>
                            <th style="width:8%;">Plan Status</th>
                            <th style="min-width: 180px;">Action</th>
                            @else
                            <th style="width:14%;">User</th>
                            <th style="width:8%;">Project Number</th>
                            <th style="width:14%;">Project Name</th>
                            <th style="width:6%;">State</th>
                            <th style="width:10%;">Created Time</th>
                            <th style="width:10%;">Submitted Time</th>
                            <th style="width:11%;">Project Status</th>
                            <th style="width:11%;">Plan Status</th>
                            <th style="min-width: 100px;">Action</th>
                            @endif
                        </tr>
                        <tr>
                            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
                            <th class="searchHead">
                                <select placeholder="Chat Filter" class="searchBox" id="chatFilter">
                                    <option value="">All</option>
                                    <option value="1">User/Client Admin</option>
                                    <option value="2">Junior/Super Admin</option>
                                    <option value="3">Error chat</option>
                                    <option value="4">Completed</option>
                                </select>
                            </th>
                            <th class="searchHead">
                                <select placeholder="Search Company" class="searchBox" id="companyFilter">
                                    <option value="">All</option>
                                    @foreach($companyList as $company)
                                        <option value="{{ $company['company_name'] }}">{{ $company['company_name'] }}</option>
                                    @endforeach
                                </select>
                                <!-- <input type="text" placeholder="Search Company" class="searchBox" id="companyFilter">  -->
                            </th>
                            <th class="searchHead"> <input type="text" placeholder="Search User" class="searchBox" id="userFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Number" class="searchBox" id="projectNumberFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Name" class="searchBox" id="projectNameFilter"> </th>
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
                            <th class="searchHead">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="created_from" name="created_from_datetime" placeholder="From" style="margin-bottom: 5px;">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="created_to" name="created_to_datetime" placeholder="To">
                            </th>
                            <th class="searchHead">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="submitted_from" name="submitted_from_datetime" placeholder="From" style="margin-bottom: 5px;">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="submitted_to" name="submitted_to_datetime" placeholder="To">
                            </th>
                            <th class="searchHead">
                                <span class="badge dropdown-toggle job-dropdown" id="statusFilter" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>All</span>
                                <div class="dropdown-menu"  aria-labelledby="statusFilter">
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('')">All</a>
                                    @foreach($projectStatusList as $item)
                                        <a class="dropdown-item" href="javascript:changeStatusFilter({{ $item['id'] }})" style="color: white; background-color: {{$item['color']}}">{{ $item['notes'] }}</a>
                                    @endforeach
                                </div>
                            </th>
                            <th class="searchHead">
                                <span class="badge dropdown-toggle job-dropdown" id="stateFilter" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>All</span>
                                <div class="dropdown-menu"  aria-labelledby="stateFilter">
                                    <a class="dropdown-item" href="javascript:changeStateFilter('')">All</a>
                                    @foreach($planStatusList as $item)
                                        <a class="dropdown-item" href="javascript:changeStateFilter({{ $item['id'] }})" style="color: white; background-color: {{$item['color']}}">{{ $item['notes'] }}</a>
                                    @endforeach
                                </div>  
                            </th>
                            <th style="display: flex; align-items: center; justify-content: center;">
                                <span class="ml-1" style='writing-mode: vertical-lr;width: 18px;transform: rotateZ(180deg);'>Edit</span>
                                <span class="ml-2" style='writing-mode: tb-rl;width: 28px;transform: rotateZ(180deg);'>Chat</span>
                                <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="planCheckFilter" style="transform: rotateZ(180deg);"> Review</span>
                                <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 22px;'><input type='checkbox' id="asBuiltFilter" style="transform: rotateZ(180deg);"> As-built</span>
                                <span class="ml-1" style='writing-mode: tb-rl;width: 28px;transform: rotateZ(180deg);'>Plan Check</span>
                                @if(Auth::user()->userrole == 2)
                                <span class="" style='writing-mode: tb-rl;width: 24px;transform: rotateZ(180deg);'>Delete</span>
                                @endif
                            </th>
                            @else
                            <th class="searchHead"> <input type="text" placeholder="Search User" class="searchBox" id="userFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Number" class="searchBox" id="projectNumberFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Name" class="searchBox" id="projectNameFilter"> </th>
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
                            <th class="searchHead">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="created_from" name="created_from_datetime" placeholder="From" style="margin-bottom: 5px;">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="created_to" name="created_to_datetime" placeholder="To">
                            </th>
                            <th class="searchHead">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="submitted_from" name="submitted_from_datetime" placeholder="From" style="margin-bottom: 5px;">
                                <input type="text" class="js-flatpickr bg-white searchBox" id="submitted_to" name="submitted_to_datetime" placeholder="To">
                            </th>
                            <th class="searchHead">
                                <span class="badge dropdown-toggle job-dropdown" id="statusFilter" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>All</span>
                                <div class="dropdown-menu"  aria-labelledby="statusFilter">
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('')">All</a>
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('0')">None</a>
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('1')">Saved</a>
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('2')">Check Requested</a>
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('3')">Reviewed</a>
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('4')">Submitted</a>
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('5')">Report Prepared</a>
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('6')">Plan Requested</a>
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('7')">Plan Reviewed</a>
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('8')">Link Sent</a>
                                    <a class="dropdown-item" href="javascript:changeStatusFilter('9')">Completed</a>
                                </div>
                            </th>
                            <th class="searchHead">
                                <span class="badge dropdown-toggle job-dropdown" id="stateFilter" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>All</span>
                                <div class="dropdown-menu"  aria-labelledby="stateFilter">
                                    <a class="dropdown-item" href="javascript:changeStateFilter('')">All</a>
                                    <a class="dropdown-item" href="javascript:changeStateFilter('0')">No action</a>
                                    <a class="dropdown-item" href="javascript:changeStateFilter('1')">Plans uploaded to portal</a>
                                    <a class="dropdown-item" href="javascript:changeStateFilter('2')">Plans reviewed</a>
                                    <a class="dropdown-item" href="javascript:changeStateFilter('3')">Comments issued</a>
                                    <a class="dropdown-item" href="javascript:changeStateFilter('4')">Updated plans uploaded to portal</a>
                                    <a class="dropdown-item" href="javascript:changeStateFilter('5')">Revised comments issued</a>
                                    <a class="dropdown-item" href="javascript:changeStateFilter('6')">Final plans uploaded to portal</a>
                                    <a class="dropdown-item" href="javascript:changeStateFilter('7')">PE sealed plans link sent</a>
                                </div>
                            </th>
                            <th style="display: flex; align-items: center; justify-content: center;">
                            <span class="ml-1" style='writing-mode: vertical-lr;width: 22px;transform: rotateZ(180deg);'>Edit</span>
                                <span class="ml-2" style='writing-mode: tb-rl;width: 32px;transform: rotateZ(180deg);'>Chat</span>
                                <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="planCheckFilter" style="transform: rotateZ(180deg);"> Review</span>
                                <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 22px;'><input type='checkbox' id="asBuiltFilter" style="transform: rotateZ(180deg);"> As-built</span>
                            </th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/pages/common.js') }}"></script>

<script>
    var filterJson;
    $(document).ready(function () {
        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        if (localStorage.getItem('projectFilterJson')!=undefined){
            filterJson = JSON.parse(localStorage.getItem('projectFilterJson'));
        } else {
            filterJson = {};
            localStorage.setItem('projectFilterJson', JSON.stringify(filterJson));
        }

        changeStateFilterLabel = function(status){
            $(`#stateFilter`).css('color', '#FFFFFF');
            if(status == ''){ $(`#stateFilter`).css('color', '#495057'); $(`#stateFilter`).html('All');  $(`#stateFilter`).css('background-color', '#FFFFFF'); }
            else if(status == '0'){ $(`#stateFilter`).html('No action');  $(`#stateFilter`).css('background-color', '#e04f1a'); }
            else if(status == '1'){ $(`#stateFilter`).html('Plans uploaded to portal');  $(`#stateFilter`).css('background-color', '#689550'); }
            else if(status == '2'){ $(`#stateFilter`).html('Plans reviewed');  $(`#stateFilter`).css('background-color', '#3c90df'); }
            else if(status == '3'){ $(`#stateFilter`).html('Comments issued');  $(`#stateFilter`).css('background-color', '#ffb119'); }
            else if(status == '4'){ $(`#stateFilter`).html('Updated plans uploaded to portal');  $(`#stateFilter`).css('background-color', '#689550'); }
            else if(status == '5'){ $(`#stateFilter`).html('Revised comments issued');  $(`#stateFilter`).css('background-color', '#343a40'); }
            else if(status == '6'){ $(`#stateFilter`).html('Final plans uploaded to portal');  $(`#stateFilter`).css('background-color', 'rgba(0, 0, 0, 0.33)'); }
            else if(status == '7'){ $(`#stateFilter`).html('PE sealed plans link sent');  $(`#stateFilter`).css('background-color', '#82b54b'); }
        }

        changeStatusFilterLabel = function(status) {
            $(`#statusFilter`).css('color', '#FFFFFF');
            if(status == ''){ $(`#statusFilter`).css('color', '#495057'); $(`#statusFilter`).html('All');  $(`#statusFilter`).css('background-color', '#FFFFFF'); }
            else if(status == '0'){ $(`#statusFilter`).html('None');  $(`#statusFilter`).css('background-color', '#e04f1a'); }
            else if(status == '1'){ $(`#statusFilter`).html('Saved');  $(`#statusFilter`).css('background-color', '#689550'); }
            else if(status == '2'){ $(`#statusFilter`).html('Check Requested');  $(`#statusFilter`).css('background-color', '#3c90df'); }
            else if(status == '3'){ $(`#statusFilter`).html('Reviewed');  $(`#statusFilter`).css('background-color', '#ffb119'); }
            else if(status == '4'){ $(`#statusFilter`).html('Submitted');  $(`#statusFilter`).css('background-color', '#689550'); }
            else if(status == '5'){ $(`#statusFilter`).html('Report Prepared');  $(`#statusFilter`).css('background-color', '#3c90df'); }
            else if(status == '6'){ $(`#statusFilter`).html('Plan Requested');  $(`#statusFilter`).css('background-color', '#689550'); }
            else if(status == '7'){ $(`#statusFilter`).html('Plan Reviewed');  $(`#statusFilter`).css('background-color', '#343a40'); }
            else if(status == '8'){ $(`#statusFilter`).html('Link Sent');  $(`#statusFilter`).css('background-color', 'rgba(0, 0, 0, 0.33)'); }
            else if(status == '9'){ $(`#statusFilter`).html('Completed');  $(`#statusFilter`).css('background-color', '#82b54b'); }
        }

        Object.keys(filterJson).forEach(function(k) {
            $("#" + k).val(filterJson[k]);
            switch (k) {
                case 'stateFilter':
                    changeStateFilterLabel(filterJson[k]);
                    break;
                case 'statusFilter':
                    changeStatusFilterLabel(filterJson[k]);
                    break;
                case 'planCheckFilter':
                    if (filterJson[k] == 1) $("#planCheckFilter").prop("checked", true);
                    else $("#planCheckFilter").prop("checked", false);
                    break;
                case 'asBuiltFilter':
                    if (filterJson[k] == 1) $("#asBuiltFilter").prop("checked", true);
                    else $("#asBuiltFilter").prop("checked", false);
                    break;
            }
        });

        $("#userFilter, #projectNameFilter, #projectNumberFilter").on('keyup change', function() {
            console.log("key changed");
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
            let key = $(this).attr('id');
            filterJson[key] = $(this).val();
            localStorage.setItem('projectFilterJson', JSON.stringify(filterJson));
        });

        $("#userFilter, #projectNameFilter, #projectNumberFilter").on('change', function() {
            console.log("changed");
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
            let key = $(this).attr('id');
            filterJson[key] = $(this).val();
            localStorage.setItem('projectFilterJson', JSON.stringify(filterJson));
        });

        $("#created_from, #created_to, #submitted_from, #submitted_to, #planCheckFilter, #asBuiltFilter, #chatFilter").on('change', function() {
            let key = $(this).attr('id');
            if (key == 'planCheckFilter' || key == 'asBuiltFilter') {
                filterJson[key] = $("#"+key)[0].checked ? $(this).val(1) : $(this).val(0);
            }
            filterJson[key] = $(this).val();
            localStorage.setItem('projectFilterJson', JSON.stringify(filterJson));
            table.draw();
        });

        $("#usStateFilter").on('change', function() {
            let key = $(this).attr('id');
            filterJson[key] = $(this).val();
            localStorage.setItem('projectFilterJson', JSON.stringify(filterJson));
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });

        changeStatusFilter = function(status){
            //"statusFilter" - status
            filterJson.statusFilter = status;
            localStorage.setItem('projectFilterJson', JSON.stringify(filterJson));

            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
            table.column('9:visible').search(status).draw();
            @else
            table.column('6:visible').search(status).draw();
            @endif
            
            changeStatusFilterLabel(status);
        }

        changeStateFilter = function(status){
            //"stateFilter" - status
            filterJson.stateFilter = status;
            localStorage.setItem('projectFilterJson', JSON.stringify(filterJson));

            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
            table.column('10:visible').search(status).draw();
            @else
            table.column('7:visible').search(status).draw();
            @endif

            changeStateFilterLabel(status);
        }

        var table = $('#projects').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "orderCellsTop": true,
            "pageLength" : 50,
            "stateSave" : true,
            "paging":   true,
            "ordering": true,
            "info":     true,
            // "dom": '<"toolbar">frtip',
            "fnInitComplete": function(){
                $('div#projects_filter').append("<input type='button' class='btn btn-hero-primary' value='Clear Filter' style='line-height:0.8' onclick='clearFilter()'/>");
            },
            "ajax":{
                    "url": "{{ url('getProjectList') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(data){ 
                        data.created_from = $("#created_from").val();
                        data.created_to = $("#created_to").val();
                        data.submitted_from = $("#submitted_from").val();
                        data.submitted_to = $("#submitted_to").val();
                        data.plancheck = $("#planCheckFilter")[0].checked ? 1 : 0;
                        data.asbuilt = $("#asBuiltFilter")[0].checked ? 1 : 0;
                        data.chat = $("#chatFilter").val();
                    }
                },
            "columns": [
                @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
                { "data": "id" },
                { "data": "companyname" },
                { "data": "username" },
                { "data": "projectnumber" },
                { "data": "projectname" },
                { "data": "state" },
                { "data": "requestfile" },
                { "data": "createdtime" },
                { "data": "submittedtime" },
                { "data": "projectstate" },
                { "data": "planstatus" },
                { "data": "actions", "orderable": false }
                @else
                { "data": "username" },
                { "data": "projectnumber" },
                { "data": "projectname" },
                { "data": "state" },
                { "data": "createdtime" },
                { "data": "submittedtime" },
                { "data": "projectstate" },
                { "data": "planstatus" },
                { "data": "actions", "orderable": false }
                @endif
            ],
            @if(Auth::user()->userrole == 2)
            "order": [[ 8, "desc" ]]
            @else
            "order": [[ 5, "desc" ]]
            @endif
        });

        @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
        $("#companyFilter").on('keyup change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });
        $("#companyFilter").on('change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });
        @endif

        clearFilter = function (){
            $("#created_from").val('');
            $("#created_to").val('');
            $("#submitted_from").val('');
            $("#submitted_to").val('');
            $("#planCheckFilter").prop("checked", false);
            $("#asBuiltFilter").prop("checked", false);
            $("#chatFilter").val('');
            $("#companyFilter").val('');
            $("#userFilter").val('');
            $("#projectNumberFilter").val('');
            $("#projectNameFilter").val('');
            $("#usStateFilter").val('');
            changeStatusFilter('');
            changeStateFilter('');
            localStorage.setItem('projectFilterJson', JSON.stringify({}));
            table.search('').columns().search('').draw();
        }

    });



    function togglePlanCheck(jobId){
        $.ajax({
            url:"togglePlanCheck",
            type:'post',
            data:{jobId: jobId},
            success: function(res){
                if(res.success){
                    return;
                    // $('#projects').DataTable().draw();
                }else
                    swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                message = res.message;
                swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
            }
        });
    }

    function toggleAsBuilt(jobId){
        $.ajax({
            url:"toggleAsBuilt",
            type:'post',
            data:{jobId: jobId},
            success: function(res){
                if(res.success){
                    return;
                    // $('#projects').DataTable().draw();
                }else
                    swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                message = res.message;
                swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
            }
        });
    }

    function openReviewTab(jobId){
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        $.ajax({
            url:"checkReviewer",
            type:'post',
            data:{projectId: jobId},
            success:function(res){
                swal.close();
                if (res.success == true) {
                    if(res.inReview){
                        swal.fire({ title: "Warning", text: "Already in review by a reviewer!", icon: "info", confirmButtonText: `OK` });
                    } else
                        window.top.location.href = "{{route('onReview')}}" + '?projectId=' + jobId;
                } else
                    swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                message = res.message;
                swal.fire({ title: "Error",
                        text: message == "" ? "Error happened while processing. Please try again later." : message,
                        icon: "error",
                        confirmButtonText: `OK` });
            }
        });
    }

</script>

@if (Auth::user()->userrole == 2)
    @include('general.projectscript')
@endif

@endsection