@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : (Auth::user()->userrole == 4 ? 'reviewer.layout' : 'user.layout')))

@section('css_after')
<style>
    @media print {
        body * {
            display: none;
        }
    }
</style>
@endsection

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
                            <th class="text-center" style="width: 5%;">No</th>
                            <th class="text-center" style="width: 5%;">Job ID</th>
                            <th style="width:8%">Company Name</th>
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
                            <th class="text-center" style="width: 7%;">No</th>
                            <th style="width:12%;">User</th>
                            <th style="width:8%;">Project Number</th>
                            <th style="width:12%;">Project Name</th>
                            <th style="width:6%;">State</th>
                            <th style="width:10%;">Created Time</th>
                            <th style="width:10%;">Submitted Time</th>
                            <th style="width:10%;">Project Status</th>
                            <th style="width:10%;">Plan Status</th>
                            <th style="min-width: 100px;">Action</th>
                            @endif
                        </tr>
                        <tr>
                            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
                            <th class="searchHead"> </th>
                            <th class="searchHead"> </th>
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
                                        <a class="dropdown-item" href="javascript:changeStatusFilter({{ $item['id'] }})" style="color: black; background-color: {{$item['color']}}">{{ $item['notes'] }}</a>
                                    @endforeach
                                </div>
                            </th>
                            <th class="searchHead">
                                <span class="badge dropdown-toggle job-dropdown" id="stateFilter" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>All</span>
                                <div class="dropdown-menu"  aria-labelledby="stateFilter">
                                    <a class="dropdown-item" href="javascript:changeStateFilter('')">All</a>
                                    @foreach($planStatusList as $item)
                                        <a class="dropdown-item" href="javascript:changeStateFilter({{ $item['id'] }})" style="color: black; background-color: {{$item['color']}}">{{ $item['notes'] }}</a>
                                    @endforeach
                                </div>  
                            </th>
                            <th style="display: flex; align-items: center; justify-content: center;">
                                <span class="ml-1" style='writing-mode: vertical-lr;width: 18px;transform: rotateZ(180deg);'>Edit</span>
                                <span class="ml-2" style='writing-mode: tb-rl;width: 28px;transform: rotateZ(180deg);cursor: pointer;' class="badge dropdown-toggle job-dropdown" id="chatFilter" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fa fa-angle-up'></i>Chat</span>
                                <div class="dropdown-menu"  aria-labelledby="chatFilter">
                                    <a class="dropdown-item chat-dropdown" id="chat-dropdown-" href="javascript:changeChatFilter('')">All</a>
                                    @foreach($companyList as $company)
                                        <a class="dropdown-item chat-dropdown" id="chat-dropdown-{{ $company['id'] }}" href="javascript:changeChatFilter({{ $company['id'] }})">{{ $company['company_name'] }}</a>
                                    @endforeach
                                </div>
                                <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="planCheckFilter" style="transform: rotateZ(180deg);"> Review</span>
                                <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="asBuiltFilter" style="transform: rotateZ(180deg);"> As-built</span>
                                @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->allow_permit > 0)
                                <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 22px;'><input type='checkbox' id="pilFilter" style="transform: rotateZ(180deg);"> PIL</span>
                                @endif
                                <span class="ml-1" style='writing-mode: tb-rl;width: 28px;transform: rotateZ(180deg);'>Plan Check</span>
                                @if(Auth::user()->userrole == 2)
                                <span class="" style='writing-mode: tb-rl;width: 24px;transform: rotateZ(180deg);'>Delete</span>
                                @endif
                            </th>
                            @else
                            <th class="searchHead"> </th>
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
                                    @foreach($projectStatusList as $item)
                                        <a class="dropdown-item" href="javascript:changeStatusFilter({{ $item['id'] }})" style="color: black; background-color: {{$item['color']}}">{{ $item['notes'] }}</a>
                                    @endforeach
                                </div>
                            </th>
                            <th class="searchHead">
                                <span class="badge dropdown-toggle job-dropdown" id="stateFilter" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>All</span>
                                <div class="dropdown-menu"  aria-labelledby="stateFilter">
                                    <a class="dropdown-item" href="javascript:changeStateFilter('')">All</a>
                                    @foreach($planStatusList as $item)
                                        <a class="dropdown-item" href="javascript:changeStateFilter({{ $item['id'] }})" style="color: black; background-color: {{$item['color']}}">{{ $item['notes'] }}</a>
                                    @endforeach
                                </div>
                            </th>
                            <th style="display: flex; align-items: center; justify-content: center;">
                                <span class="ml-1" style='writing-mode: vertical-lr;width: 22px;transform: rotateZ(180deg);'>Edit</span>
                                <span class="ml-2" style='writing-mode: tb-rl;width: 28px;transform: rotateZ(180deg);cursor: pointer;' class="badge dropdown-toggle job-dropdown" id="chatFilter" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Chat</span>
                                <div class="dropdown-menu"  aria-labelledby="chatFilter">
                                    <a class="dropdown-item chat-dropdown" id="chat-dropdown-" href="javascript:changeChatFilter('')">All</a>
                                    @foreach($users as $user)
                                        <a class="dropdown-item chat-dropdown" id="chat-dropdown-{{ $user['id'] }}" href="javascript:changeChatFilter({{ $user['id'] }})">{{ $user['username'] }}</a>
                                    @endforeach
                                </div>
                                <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="planCheckFilter" style="transform: rotateZ(180deg);"> Review</span>
                                <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="asBuiltFilter" style="transform: rotateZ(180deg);"> As-built</span>
                                @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->allow_permit > 0)
                                <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 22px;'><input type='checkbox' id="pilFilter" style="transform: rotateZ(180deg);"> PIL</span>
                                @endif
                            </th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-print" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Print Range</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="print-from">From</label>
                            <input type="number" class="form-control" id="print-from" name="print-from" value="1" min="1">
                        </div>
                        <div class="form-group col-6">
                            <label for="print-to">To</label>
                            <input type="number" class="form-control" id="print-to" name="print-to" value="1" min="1">
                        </div>
                    </div>
                </div>
                <div class="block-content block-content-full text-right bg-light">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal" onclick="callPrint()">Done</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/pages/common.js') }}"></script>

<script>
    var projectStatus = [
        @foreach($projectStatusList as $item)
        { id:"{{ $item['id'] }}", notes: "{{ $item['notes'] }}", color: "{{ $item['color'] }}" },
        @endforeach
    ];
    var planStatus = [
        @foreach($planStatusList as $item)
        { id:"{{ $item['id'] }}", notes: "{{ $item['notes'] }}", color: "{{ $item['color'] }}" },
        @endforeach
    ];
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
            $(`#stateFilter`).css('color', 'black');
            if(status === ''){ $(`#stateFilter`).css('color', '#495057'); $(`#stateFilter`).html('All');  $(`#stateFilter`).css('background-color', '#FFFFFF'); }
            else {
                let item = planStatus.filter(e => e.id && e.id == status);
                if(item[0]){
                    $(`#stateFilter`).css('color', 'black'); $(`#stateFilter`).html(item[0].notes);  $(`#stateFilter`).css('background-color', item[0].color);
                }
            }
        }

        changeStatusFilterLabel = function(status) {
            $(`#statusFilter`).css('color', 'black');
            if(status === ''){ $(`#statusFilter`).css('color', '#495057'); $(`#statusFilter`).html('All');  $(`#statusFilter`).css('background-color', '#FFFFFF'); }
            else {
                let item = projectStatus.filter(e => e.id && e.id == status);
                if(item[0]){
                    $(`#statusFilter`).css('color', 'black'); $(`#statusFilter`).html(item[0].notes);  $(`#statusFilter`).css('background-color', item[0].color);
                }
            }
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
                case 'pilFilter':
                    if (filterJson[k] == 1) $("#pilFilter").prop("checked", true);
                    else $("#pilFilter").prop("checked", false);
                    break;
                case 'chatFilter':
                    console.log(filterJson[k]);
                    if (filterJson[k] != '')
                        $(`#chat-dropdown-${filterJson[k]}`).addClass('active');
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

        $("#created_from, #created_to, #submitted_from, #submitted_to, #planCheckFilter, #asBuiltFilter, #pilFilter").on('change', function() {
            let key = $(this).attr('id');
            if (key == 'planCheckFilter' || key == 'asBuiltFilter' || key == 'pilFilter') {
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
            table.column('10:visible').search(status).draw();
            @else
            table.column('7:visible').search(status).draw();
            @endif
            
            changeStatusFilterLabel(status);
        }

        changeStateFilter = function(status){
            //"stateFilter" - status
            filterJson.stateFilter = status;
            localStorage.setItem('projectFilterJson', JSON.stringify(filterJson));

            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
            table.column('11:visible').search(status).draw();
            @else
            table.column('8:visible').search(status).draw();
            @endif

            changeStateFilterLabel(status);
        }

        changeChatFilter = function(id){
            filterJson.chatFilter = id;
            localStorage.setItem('projectFilterJson', JSON.stringify(filterJson));

            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
            table.column('12:visible').search(id).draw();
            @else
            table.column('9:visible').search(id).draw();
            @endif

            table.draw();
            $(".chat-dropdown").removeClass('active');
            $(`#chat-dropdown-${id}`).addClass('active');
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
                $('div#projects_length').append("<input type='button' class='btn btn-hero-primary ml-3' value='Print' style='line-height:0.8' onclick='printDlgShow()'/>");
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
                        data.pil = $("#pilFilter").length > 0 && $("#pilFilter")[0].checked ? 1 : 0;
                    }
                },
            "columns": [
                @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
                { "data": "idx", "orderable": false },
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
                { "data": "idx", "orderable": false },
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
            $("#pilFilter").prop("checked", false);
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

    function getProjectState(jobId){
        return new Promise((resolve, reject) => {
            $.ajax({
                url:"getProjectState",
                type:'post',
                data:{jobId: jobId},
                success: function(res){
                    if(res.success){
                        resolve(res.state);
                    }else{
                        swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
                        resolve(0);
                    }
                },
                error: function(xhr, status, error) {
                    res = JSON.parse(xhr.responseText);
                    message = res.message;
                    swal.fire({ title: "Error",
                        text: message == "" ? "Error happened while processing. Please try again later." : message,
                        icon: "error",
                        confirmButtonText: `OK` });
                    resolve(0);
                }
            });
        })
    }

    async function togglePlanCheck(obj, jobId){
        @if(Auth::user()->userrole == 0)
        let state = await getProjectState(jobId);
        if(state != 3 && state < 5){
            swal.fire({ title: "Warning", text: 'Project must be calculated before asking for a Review.', icon: "warning", confirmButtonText: `OK` });
            $(obj)[0].checked = false;
            return;
        }
        @endif

        if($(obj)[0].checked == true && ($(obj).parents('tr').find(".asbuilt")[0].checked == true || ($(obj).parents('tr').find(".pilcheck").length > 0 && $(obj).parents('tr').find(".pilcheck")[0].checked == true ))){
            swal.fire({ title: "Warning", text: "Only one type of review checkbox at a time please.", icon: "warning", confirmButtonText: `OK` });
            $(obj)[0].checked = false;
            return;
        }

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
    
    async function toggleAsBuilt(obj, jobId){
        @if(Auth::user()->userrole == 0)
        let state = await getProjectState(jobId);
        if(state != 3 && state < 5){
            swal.fire({ title: "Warning", text: 'Project must be calculated before asking for a As-Built.', icon: "warning", confirmButtonText: `OK` });
            $(obj)[0].checked = false;
            return;
        }
        @endif

        if($(obj)[0].checked == true && ($(obj).parents('tr').find(".plancheck")[0].checked == true || ($(obj).parents('tr').find(".pilcheck").length > 0 && $(obj).parents('tr').find(".pilcheck")[0].checked == true ))){
            swal.fire({ title: "Warning", text: "Only one type of review checkbox at a time please.", icon: "warning", confirmButtonText: `OK` });
            $(obj)[0].checked = false;
            return;
        }

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

    async function togglePilStatus(obj, jobId){
        @if(Auth::user()->userrole == 0)
        let state = await getProjectState(jobId);
        if(state != 3 && state < 5){
            swal.fire({ title: "Warning", text: 'Project must be calculated before asking for a PIL.', icon: "warning", confirmButtonText: `OK` });
            $(obj)[0].checked = false;
            return;
        }
        @endif

        if($(obj)[0].checked == true && ($(obj).parents('tr').find(".plancheck")[0].checked == true || $(obj).parents('tr').find(".asbuilt")[0].checked == true)){
            swal.fire({ title: "Warning", text: "Only one type of review checkbox at a time please.", icon: "warning", confirmButtonText: `OK` });
            $(obj)[0].checked = false;
            return;
        }

        $.ajax({
            url:"togglePIL",
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

    function printDlgShow(){
        jQuery('#modal-print').modal('show');
    }

    function Printer($c){
        var h = $c;
        return {
            doPrint: function(){
                var d = $("<div>").html(h).appendTo("html");
                //$("body").hide();
                window.print();
                d.remove();
                //$("body").show();
            },
            setContent: function($c){
                h = $c;
            }
        };
    }
    
    function callPrint(){
        if(parseInt($("#print-from").val()) > parseInt($("#print-to").val()))
        {
            swal.fire({ title: "Warning", text: "Please input correct numbers!", icon: "info", confirmButtonText: `OK` });
            return;
        }
        
        jQuery('#modal-print').modal('hide');
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        
        var params = $('#projects').DataTable().ajax.params();
        params['start'] = parseInt($("#print-from").val()) - 1;
        params['length'] = parseInt($("#print-to").val()) - parseInt($("#print-from").val()) + 1;
        $.ajax({
            url:"getProjectList",
            type:'post',
            data: $('#projects').DataTable().ajax.params(),
            success:function(res){
                swal.close();
                var response = JSON.parse(res);
                if(response && response.data && response.data.length > 0){
                    let html = '';
                    
                    @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
                    html += "<table id='projects' class='table table-bordered table-striped table-vcenter text-center' style='width:100%;'>\
                    <thead>\
                        <tr>\
                            <th class='text-center' style='width: 5%;'>No</th>\
                            <th class='text-center' style='width: 7%;'>Job ID</th>\
                            <th style='width:10%'>Company Name</th>\
                            <th style='width:10%;'>User</th>\
                            <th style='width:9%;'>Project Number</th>\
                            <th style='width:9%;'>Project Name</th>\
                            <th style='width:5%;'>State</th>\
                            <th style='width:7%;'>File Name</th>\
                            <th style='width:10%;'>Created Time</th>\
                            <th style='width:10%;'>Submitted Time</th>\
                            <th style='width:10%;'>Project Status</th>\
                            <th style='width:10%;'>Plan Status</th>\
                        </tr>\
                    </thead>\
                    <tbody>";
                    
                    let i = parseInt($("#print-from").val());
                    response.data.forEach(job => {
                        html += `<tr>\
                            <td>${i}</td>\
                            <td>${job.id}</td>\
                            <td>${job.companyname}</td>\
                            <td>${job.username}</td>\
                            <td>${job.projectnumber}</td>\
                            <td>${job.projectname}</td>\
                            <td>${job.state}</td>\
                            <td>${job.requestfile}</td>\
                            <td>${job.createdtime}</td>\
                            <td>${job.submittedtime}</td>\
                            <td>${job.statenote}</td>\
                            <td>${job.statusnote}</td>\
                        <tr/>`;
                        i ++;
                    });
                    html += "</tbody>";

                    var order = $('#projects').DataTable().order();
                    var today = new Date();
                    html = `<h2 style='text-align: center;'>Project List ${$("#print-from").val()} to ${i - 1}, Sorted by ${$($('#projects').DataTable().column(order[0][0]).header()).html()}, Print Date ${String(today.getMonth() + 1).padStart(2, '0') + '/' + String(today.getDate()).padStart(2, '0') + '/' + today.getFullYear()}</h2>` + html;
                    @else
                    html += "<table id='projects' class='table table-bordered table-striped table-vcenter text-center' style='width:100%;'>\
                    <thead>\
                        <tr>\
                            <th class='text-center' style='width: 7%;'>No</th>\
                            <th style='width:14%;'>User</th>\
                            <th style='width:10%;'>Project Number</th>\
                            <th style='width:14%;'>Project Name</th>\
                            <th style='width:7%;'>State</th>\
                            <th style='width:12%;'>Created Time</th>\
                            <th style='width:12%;'>Submitted Time</th>\
                            <th style='width:12%;'>Project Status</th>\
                            <th style='width:12%;'>Plan Status</th>\
                        </tr>\
                    </thead>\
                    <tbody>";
                    
                    let i = parseInt($("#print-from").val());
                    response.data.forEach(job => {
                        html += `<tr>\
                            <td>${i}</td>\
                            <td>${job.username}</td>\
                            <td>${job.projectnumber}</td>\
                            <td>${job.projectname}</td>\
                            <td>${job.state}</td>\
                            <td>${job.createdtime}</td>\
                            <td>${job.submittedtime}</td>\
                            <td>${job.statenote}</td>\
                            <td>${job.statusnote}</td>\
                        <tr/>`;
                        i ++;
                    });
                    html += "</tbody>";

                    var order = $('#projects').DataTable().order();
                    var today = new Date();
                    html = `<h2 style='text-align: center;'>{{ $companyName }} - Project List ${$("#print-from").val()} to ${i - 1}, Sorted by ${$($('#projects').DataTable().column(order[0][0]).header()).html()}, Print Date ${String(today.getMonth() + 1).padStart(2, '0') + '/' + String(today.getDate()).padStart(2, '0') + '/' + today.getFullYear()}</h2>` + html;
                    @endif

                    var printer = new Printer(html);
                    printer.doPrint();
                }
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