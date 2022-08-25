@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : (Auth::user()->userrole == 4 ? 'reviewer.layout' : (Auth::user()->userrole == 5 ? 'printer.layout' : ((Auth::user()->userrole == 6) ? 'consultant.layout' : 'user.layout')))))

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
                            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5 )
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
                            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5)
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
                                <div style="display:flex">
                                    <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="planCheckFilter" style="transform: rotateZ(180deg);"> Review</span>
                                    <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="asBuiltFilter" style="transform: rotateZ(180deg);"> As-built</span>
                                    @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5 || Auth::user()->allow_permit > 0)
                                    <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="pilFilter" style="transform: rotateZ(180deg);"> PIL</span>
                                    @endif
                                    <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="printFilter" style="transform: rotateZ(180deg);">Print</span>
                                </div>
                                <span class="mx-2" style='writing-mode: tb-rl;width: 28px;transform: rotateZ(180deg); line-height: 1;'>Check Box <br />/ Status</span>
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
                                <span class="ml-1" style='writing-mode: vertical-lr;width: 22px;height: 61px;transform: rotateZ(180deg);'>Edit</span>
                                <span class="ml-2" style='writing-mode: tb-rl;width: 28px;transform: rotateZ(180deg);cursor: pointer;' class="badge dropdown-toggle job-dropdown" id="chatFilter" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Chat</span>
                                <div class="dropdown-menu"  aria-labelledby="chatFilter">
                                    <a class="dropdown-item chat-dropdown" id="chat-dropdown-" href="javascript:changeChatFilter('')">All</a>
                                    @foreach($users as $user)
                                        <a class="dropdown-item chat-dropdown" id="chat-dropdown-{{ $user['id'] }}" href="javascript:changeChatFilter({{ $user['id'] }})">{{ $user['username'] }}</a>
                                    @endforeach
                                </div>
                                @if(Auth::user()->userrole != 6 && Auth::user()->userrole != 7)
                                <div style="display:flex">
                                    <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="planCheckFilter" style="transform: rotateZ(180deg);"> Review</span>
                                    <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="asBuiltFilter" style="transform: rotateZ(180deg);"> As-built</span>
                                    @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5 || Auth::user()->allow_permit > 0)
                                    <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 22px;'><input type='checkbox' id="pilFilter" style="transform: rotateZ(180deg);"> PIL</span>
                                    @endif
                                    <span style='writing-mode: vertical-lr;display:flex;align-items:center;transform: rotateZ(180deg);width: 17px;'><input type='checkbox' id="printFilter" style="transform: rotateZ(180deg);">Print</span>
                                </div>
                                <span class="mx-2" style='writing-mode: tb-rl;width: 28px;transform: rotateZ(180deg); line-height: 1;'>Check Box <br />/ Status</span>
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

<div class="modal" id="printmodal" tabindex="-1" role="dialog" aria-labelledby="printmodal" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 1200px;">
        <div class="modal-content">
            <form class="js-validation" onsubmit="return false;" method="POST" id="printDataForm">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">iRoof Printing</h3>
                        <div class="block-options">
                            <!-- <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close"> -->
                            <button type="button" class="btn-block-option" aria-label="Close" onclick="exit()">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content" style="max-height: 700px; overflow: auto;">
                        <div class="row items-push">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="projectName">Project</label>
                                    <div style="width: 80%" class="d-flex">
                                        <input type="text" class="form-control" id="projectName" name="projectName" placeholder="Enter Project Name..." style="border: 1px solid #f1dfd2; width: 45%;" disabled>
                                        <div class="form-group addRequest-form" style="width: 25%">
                                            <label for="" style="width: 63%; margin-left: 20px">Add Print Request</label>
                                            <button class="btn btn-primary mr-1" id="" onclick="addPrintRequest()">+</button>
                                        </div>
                                        <div class="form-group currentRequest-form" style="width: 30%">
                                            <label for="currentPrintRequest" style="width: 190%;">Current Print Request</label>
                                            <select class="form-control" id="currentPrintRequest" name="currentPrintRequest" style="border: 1px solid #f1dfd2; text-align: left; text-align-last: left;"></select>
                                        </div>
                                        <input id="request_id" type="hidden" name="request_id"/>
                                    </div>
                                </div>
                                <div class="form-group printFile-form">
                                    <label for="filePrint">Select Files to Print</label>
                                    <textarea type="text" class="form-control" id="filePrint" name="filePrint" rows="3" style="border: 1px solid #f1dfd2;" onclick="openJobFiles()"></textarea>
                                </div>
                                <lable style="color: #636b6f;font-weight:600;">Printing Instructions</label>
                                <div class="pl-5">
                                    <div class="row align-items-center">
                                        <div class="col-4">
                                            <div class="form-group align-items-center">
                                                <label for="copies"># Copies </label>
                                                <select class="form-control" id="copies" name="copies" style="border: 1px solid #f1dfd2;">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                    <option value="19">19</option>
                                                    <option value="20">20</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group align-items-center">
                                                <label for="plan-set" style="width: 40%"><nobr># Sheets / Plan Set</nobr></label>
                                                <input type="text" class="form-control" id="plan-set" name="plan-set" placeholder="Enter Integer..." style="border: 1px solid #f1dfd2;">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group align-items-center">
                                                <label for="report" style="width: 40%"><nobr># Report Pages</nobr></label>
                                                <input type="text" class="form-control" id="report" name="report" placeholder="Enter Integer..." style="border: 1px solid #f1dfd2;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <div class="form-group sealtype-form">
                                                <label>Seal type </label>
                                                <div class="d-flex align-items-center">
                                                    <div class="custom-control custom-radio custom-control-dark mb-1">
                                                        <input type="radio" class="custom-control-input" id="rubber-stamp" name="seal-type" value="0">
                                                        <label class="custom-control-label mr-3" for="rubber-stamp">rubber stamp</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-dark mb-1">
                                                        <input type="radio" class="custom-control-input" id="embossed" name="seal-type" value="1">
                                                        <label class="custom-control-label mr-3" for="embossed">embossed</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group signature-form">
                                                <label for="issuedTo" style="width: 35%">Ink Signature </label>
                                                <div class="d-flex align-items-center">
                                                    <div class="custom-control custom-radio custom-control-dark mb-1">
                                                        <input type="radio" class="custom-control-input" id="yes" name="signature" value="1" >
                                                        <label class="custom-control-label mr-5" for="yes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-dark mb-1">
                                                        <input type="radio" class="custom-control-input" id="no" name="signature" value="0">
                                                        <label class="custom-control-label mr-5" for="no">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <div class="form-group username-form">
                                                <label for="issuedFrom">User Name </label>
                                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter Your Name..." value={{ Auth::user()->username }} style="border: 1px solid #f1dfd2;">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group useremail-form">
                                                <label for="issuedTo">Email Address </label>
                                                <input type="text" class="form-control" id="useremail" name="useremail" placeholder="Enter Your Email..." value={{ Auth::user()->email }} style="border: 1px solid #f1dfd2;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="company-info">
                                    <div class="form-group">
                                        <label for="jobCount">Addressee </label>
                                        <!-- <input type="text" class="form-control" id="jobCount" name="jobCount" placeholder="Enter Job Counts..." style="border: 1px solid #f1dfd2;"> -->
                                        <!-- <label for="company">Company </label><br/> -->
                                        <select class="form-control" id="company" name="company" style="border: 1px solid #f1dfd2;" onchange="selectCompany(this)">
                                            <option value="0">New Company</option>
                                            <!-- @foreach ($companyList as $company)
                                                <option>{{ $company->company_name }}</option>
                                            @endforeach -->
                                            @foreach ($printAddress as $address)
                                                <option value="{{$address->id}}">{{ $address->company_name }} -{{ $address->city}}, {{ $address->state}} -{{ $address->contact_name}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="id" name="id">
                                    </div>
                                    <div class="form-group companyName-form">
                                        <label for="company-name">Company Name </label>
                                        <input type="text" class="form-control" id="company-name" name="company-name" placeholder="Enter Company Name..." style="border: 1px solid #f1dfd2;">
                                    </div>
                                    <div class="form-group contactName-form">
                                        <label for="contact-name">Contact Name </label>
                                        <input type="text" class="form-control" id="contact-name" name="contact-name" placeholder="Enter Contact Name..." style="border: 1px solid #f1dfd2;">
                                    </div>
                                    <div class="form-group address1-form">
                                        <label for="address1">Address1 </label>
                                        <input type="text" class="form-control" id="address1" name="address1" placeholder="Enter Address1..." style="border: 1px solid #f1dfd2;">
                                    </div>
                                    <div class="form-group address2-form">
                                        <label for="address2">Address2 </label>
                                        <input type="text" class="form-control" id="address2" name="address2" placeholder="Enter Address2..." style="border: 1px solid #f1dfd2;">
                                    </div>
                                    <div class="row" style="width: 88%; margin-left: auto;">
                                        <div class="col-4">
                                            <div class="form-group city-form">
                                                <label for="city">City </label>
                                                <input type="text" class="form-control" id="city" name="city" placeholder="Enter City..." style="border: 1px solid #f1dfd2;">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group state-form">
                                                <label for="state">State </label>
                                                <!-- <input type="text" class="form-control" id="state" name="state" placeholder="Enter State..." style="border: 1px solid #f1dfd2;"> -->
                                                <select placeholder="State" class="form-control" id="state" style="border: 1px solid #f1dfd2;">
                                                    <option value="" hidden>Enter State...</option>
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
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group zip-form">
                                                <label for="zip">Zip </label>
                                                <input type="text" class="form-control" id="zip" name="zip" placeholder="Enter Zip Code..." style="border: 1px solid #f1dfd2;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center" style="margin-left:113px;">
                                        <div class="col-6">
                                            <div class="form-group phonenumber-form">
                                                <label for="phonenumber">Phone Number </label>
                                                <input type="text" class="form-control" id="phonenumber" name="phonenumber" placeholder="Enter Phone Number..." style="border: 1px solid #f1dfd2;">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group extension-form">
                                                <label for="extension">Extension </label>
                                                <input type="text" class="form-control" id="extension" name="extension" placeholder="Enter Extension..." style="border: 1px solid #f1dfd2;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-7">
                                        <div class="form-group deliver-form">
                                            <label style="width: 35%">Delivery method </label>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="custom-control custom-radio custom-control-dark mb-1">
                                                    <input type="radio" class="custom-control-input" id="mail" name="delivery-method" value="0" >
                                                    <label class="custom-control-label mr-3" for="mail">Mail</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-dark mb-1">
                                                    <input type="radio" class="custom-control-input" id="overnight" name="delivery-method" value="1" >
                                                    <label class="custom-control-label mr-3" for="overnight">Overnight</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-dark mb-1">
                                                    <input type="radio" class="custom-control-input" id="2nd-day" name="delivery-method" value="2" >
                                                    <label class="custom-control-label mr-3" for="2nd-day">2nd Day</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="form-group">
                                            <label for="fedex" style="width: 40%"><nobr>3rd Party FedEx #</nobr></label>
                                            <input type="text" class="form-control" id="fedex" name="fedex" placeholder="Enter EedEx..." style="border: 1px solid #f1dfd2; width: 60%">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="user-textarea-input">User's Notes </label>
                                    <textarea class="form-control" id="user-textarea-input" name="user-textarea-input" rows="4" placeholder="" spellcheck="false"></textarea>
                                </div>
                                <div class="form-group date-group">
                                    <div class="row align-items-center" style="width: 100%;">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="printed-date"><nobr>Printed Date</nobr></label>
                                                @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5)
                                                    <input type="text" class="form-control" id="printed-date" name="printed-date" placeholder="Printed Date" onfocus="(this.type='date')" style="border: 1px solid #f1dfd2; width: 100%;">
                                                @else
                                                    <input type="text" class="form-control" id="printed-date" name="printed-date" placeholder="Printed Date" onfocus="(this.type='date')" style="border: 1px solid #f1dfd2; width: 100%;" disabled>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="sent-date"><nobr>Sent Date</nobr></label>
                                                @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5)
                                                    <input type="text" class="form-control" id="sent-date" name="sent-date" placeholder="Sent Date" onfocus="(this.type='date')" style="border: 1px solid #f1dfd2; width: 100%;">
                                                @else
                                                    <input type="text" class="form-control" id="sent-date" name="sent-date" placeholder="Sent Date" onfocus="(this.type='date')" style="border: 1px solid #f1dfd2; width: 100%;" disabled>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tracking">Tracking</label>
                                                @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5)
                                                    <input type="text" class="form-control" id="tracking" name="tracking" placeholder="Tracking" style="border: 1px solid #f1dfd2; width: 100%;">
                                                @else
                                                    <input type="text" class="form-control" id="tracking" name="tracking" placeholder="Tracking" style="border: 1px solid #f1dfd2; width: 100%;" disabled>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="printer-textarea-input">Printer's Notes </label>
                                    <textarea class="form-control" id="printer-textarea-input" name="printer-textarea-input" rows="4" placeholder="" spellcheck="false"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-left bg-light">
                        <button type="submit" class="btn btn-sm btn-primary" onclick="savePrint()">Save</button>
                        <button type="button" class="btn btn-sm btn-light" onclick="submitPrint()">Submit</button>
                        <button type="button" class="btn btn-sm btn-light" onclick="exit()">Exit</button>
                        <div class="float-right">
                            <button type="button" class="btn btn-sm btn-light text-right" onclick="deletePrint()">Cancel Print Request</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="reportmodal" tabindex="-1" role="dialog" aria-labelledby="printmodal" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 1200px;">
        <div class="modal-content">
            <form class="js-validation" onsubmit="return false;" method="POST" id="fileForm">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title reportmodal-name">Print Files</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content" style="max-height: 700px; overflow: auto;">
                        <div class="row items-push">
                        <table class="sortable">
                            <thead>
                                <tr>
                                    <th style='width: 5%;text-align:center;'></th>
                                    <th style='width: 63%;'>Filename</th>
                                    <th style='width: 10%;'>Size</th>
                                    <th style='width: 17%;'>Date Modified</th>
                                </tr>
                            </thead>
                            <tbody id="file-table">
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-right bg-light">
                        <button type="button" class="btn btn-sm btn-light" onclick="confirmFiles()">OK</button>
                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Cancel</ >
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/pages/common.js') }}"></script>

<script>
    window.popUpWnds = [];

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
    var companyList = [
        @foreach($printAddress as $item)
        { id:"{{ $item['id'] }}", company_name: "{{ $item['company_name'] }}",contact_name:"{{ $item['contact_name'] }}", address1: "{{ $item['address1'] }}", address2: "{{ $item['address2'] }}", city: "{{ $item['city'] }}", state: "{{ $item['state'] }}", zip: "{{ $item['zip'] }}", phonenumber: "{{ $item['telno'] }}", extension: "{{ $item['extension'] }}"  },
        @endforeach
    ];

    var selectedFiles = new Array();

    var hasChanges = false;

    var jobPrintStatus = 0;

    var addRequest = false;

    var currentJob = "";

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

        $('#printmodal').on('hidden.bs.modal', function () {
            hasChanges = false;
        })

        $('#reportmodal').on('hidden.bs.modal', function () {
            $('#printmodal').modal('toggle');
        })

        $('#currentPrintRequest').on('change', function() {
            addRequest = false;
            let index = $(this).val();
            $("input:radio").prop("checked", false);

            $.post("togglePrintCheck", {id: $("#id").val()}, function(result){
                var jobStatus;
                if(result.data[index]){
                    jobPrintStatus = result.data[index].print_status;
                    if(result.data[index].print_status == 1) {
                        jobStatus = "SAVED";
                    } else if (result.data[index].print_status == 2) {
                        jobStatus = "SUBMITTED";
                    } else if (result.data[index].print_status == 3) {
                        jobStatus = "PRINTED";
                    } else if (result.data[index].print_status == 4) {
                        jobStatus = "COMPLETED";
                    }else if (result.data[index].print_status == 0) {
                        jobStatus = "NOT SAVED";
                    }
                    $("#projectName").val(result.job.clientProjectNumber + ". " + result.job.clientProjectName + " " + result.job.state + " (" + jobStatus +")" );

                    if(result.data[index].print_status > 1) {
                        $("#username").val(result.users[index].username);
                        $("#useremail").val(result.users[index].email);
                    } else {
                        $("#username").val("<?php echo Auth::user()->username?>");
                        $("#useremail").val("<?php echo Auth::user()->email?>");
                    }
    
                    $("#company").val(result.data[index].address_id);
                    if(result.address[index]) {
                        $("#company-name").val(result.address[index].company_name);
                        $("#contact-name").val(result.address[index].contact_name);
                        $("#address1").val(result.address[index].address1);
                        $("#address2").val(result.address[index].address2);
                        $("#zip").val(result.address[index].zip);
                        $("#city").val(result.address[index].city);
                        $("#state").val(result.address[index].state);
                        $("#phonenumber").val(result.address[index].telno);
                        $("#extension").val(result.address[index].extension);
                    } else {
                        $("#company-name").val("");
                        $("#contact-name").val("");
                        $("#address1").val("");
                        $("#address2").val("");
                        $("#zip").val("");
                        $("#city").val("");
                        $("#state").val("");
                        $("#phonenumber").val("");
                        $("#extension").val("");
                    }
        
                    $("#request_id").val(result.data[index].id);
                    $("#copies").val(result.data[index].copies);
                    $("#plan-set").val(result.data[index].plan_sheets);
                    $("#report").val(result.data[index].report_sheets);
                    if(result.data[index].seal_type == 0) {
                        $("#rubber-stamp").prop('checked', true);
                    } else if(result.data[index].seal_type == 1) {
                        $("#embossed").prop('checked', true);
                    }
                    
                    if(result.data[index].signature == true) {
                        $("#yes").prop('checked', true);
                    } else if(result.data[index].signature == false) {
                        $("#no").prop('checked', true);
                    }
                    
                    if(result.data[index].delivery_method == 2) {
                        $("#2nd-day").prop('checked', true);
                    } else if (result.data[index].delivery_method == 1) {
                        $("#overnight").prop('checked', true);
                    } else if (result.data[index].delivery_method == 0) {
                        $("#mail").prop('checked', true);
                    }
                            
                    $("#user-textarea-input").val(result.data[index].user_notes);
                    $("#printer-textarea-input").val(result.data[index].printer_notes);
                    $("#sent-date").val(result.data[index].sent);
                    $("#printed-date").val(result.data[index].printed);
                    $("#tracking").val(result.data[index].tracking);
                    $("#fedex").val(result.data[index].third_party_fedex);
                    if(result.data[index].selected_files != "null") {
                        let file_names = [];
                        file_names = result.data[index].selected_files.slice(1, result.data[index].selected_files.length - 1).split("\\n");
                        $('#filePrint').val(file_names.join("\n"));
                    }
                } else {
                    jobStatus = "NOT SAVED";
                    $("#projectName").val(result.job.clientProjectNumber + ". " + result.job.clientProjectName + " " + result.job.state + " (" + jobStatus +")" );

                    $("#company").val(0);
                    $("#company-name").val("");
                    $("#contact-name").val("");
                    $("#address1").val("");
                    $("#address2").val("");
                    $("#zip").val("");
                    $("#city").val("");
                    $("#state").val("");
                    $("#phonenumber").val("");
                    $("#extension").val("");

                    $("#request_id").val('');
                    $("#copies").val(1);
                    $("#plan-set").val("");
                    $("#report").val("");
                    $("#user-textarea-input").val("");
                    $("#printer-textarea-input").val("");
                    $("#printed-date").val("");
                    $("#sent-date").val("");
                    $("#tracking").val("");
                    $("#fedex").val("");
                    $("#filePrint").val("");
                    $("#username").val("<?php echo Auth::user()->username?>");
                    $("#useremail").val("<?php echo Auth::user()->email?>");
                    $("input:radio").attr("checked", false);
                    $("input:radio").prop("checked", false);
                    $("#printed-date").val("");
                    $("#sent-date").val("");
                    $("#tracking").val("");
                    $('#currentPrintRequest').val($('#currentPrintRequest option').length-1);
                    addRequest = true;
                }
            hasChanges = false;
            })
        })

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
                case 'printFilter':
                    if (filterJson[k] == 1) $("#printFilter").prop("checked", true);
                    else $("#printFilter").prop("checked", false);
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

        $("#created_from, #created_to, #submitted_from, #submitted_to, #planCheckFilter, #asBuiltFilter, #pilFilter, #printFilter").on('change', function() {
            let key = $(this).attr('id');
            if (key == 'planCheckFilter' || key == 'asBuiltFilter' || key == 'pilFilter' || key == 'printFilter') {
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

            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5)
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

            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5)
            table.column('11:visible').search(status).draw();
            @else
            table.column('8:visible').search(status).draw();
            @endif

            changeStateFilterLabel(status);
        }

        changeChatFilter = function(id){
            filterJson.chatFilter = id;
            localStorage.setItem('projectFilterJson', JSON.stringify(filterJson));

            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5)
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
                        @if(Auth::user()->userrole != 6 && Auth::user()->userrole != 7)
                        data.plancheck = $("#planCheckFilter")[0].checked ? 1 : 0;
                        data.asbuilt = $("#asBuiltFilter")[0].checked ? 1 : 0;
                        data.pil = $("#pilFilter").length > 0 && $("#pilFilter")[0].checked ? 1 : 0;
                        data.print = $("#printFilter")[0].checked ? 1 : 0;
                        @endif
                    }
                },
            "columns": [
                @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5)
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

        @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5)
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
            $("#printFilter").prop("checked", false);
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

        if($(obj)[0].checked == true && ($(obj).parents('tr').find(".asbuilt")[0].checked == true || ($(obj).parents('tr').find(".pilcheck").length > 0 && $(obj).parents('tr').find(".pilcheck")[0].checked == true || $(obj).parents('tr').find(".printcheck")[0].checked == true ))){
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

        if($(obj)[0].checked == true && ($(obj).parents('tr').find(".plancheck")[0].checked == true || ($(obj).parents('tr').find(".pilcheck").length > 0 && $(obj).parents('tr').find(".pilcheck")[0].checked == true || $(obj).parents('tr').find(".printcheck")[0].checked == true))){
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

        if($(obj)[0].checked == true && ($(obj).parents('tr').find(".plancheck")[0].checked == true || $(obj).parents('tr').find(".asbuilt")[0].checked == true || $(obj).parents('tr').find(".printcheck")[0].checked == true)){
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

    async function togglePrintCheck(obj, jobId){
        addRequest = false;
        if($(obj)[0].checked == true && ($(obj).parents('tr').find(".plancheck")[0].checked == true || $(obj).parents('tr').find(".asbuilt")[0].checked == true || ($(obj).parents('tr').find(".pilcheck").length > 0 && $(obj).parents('tr').find(".pilcheck")[0].checked == true))){
            swal.fire({ title: "Warning", text: "All review should be finished before printing.", icon: "warning", confirmButtonText: `OK` });
            $(obj)[0].checked = false;
            return;
        }
        $('#currentPrintRequest').empty();
        $("input:radio").prop("checked", false);
        
        $(".sealtype-form").css("color","#636b6f");
        $(".signature-form").css("color","#636b6f");
        $("#useremail").css("color","#636b6f");
        $(".deliver-form").css("color","#636b6f");
        $(".printFile-form").css("color","#636b6f");
        $(".printFile-form").find("textarea").css("border-color","#f1dfd2");
        $(".company-info input").css("border-color","#f1dfd2");
        $(".company-info label").css("color","#636b6f");
        $(".state-form").css("color","#636b6f");
        $(".state-form").find("select").css("border-color","#f1dfd2");


        $("#projects .selected").removeClass("selected");
        $(obj).parents('tr').addClass("selected");
        $("option:selected").removeAttr("selected");
        
        if($(obj)[0].checked == true){
            $(obj)[0].checked = false;
        } else {
            $(obj)[0].checked = true;
        }

        $("#printDataForm input").change(function () {
            hasChanges = true;
        })

        $("#printDataForm textarea").change(function () {
            hasChanges = true;
        })

        $("#printDataForm select").change(function () {
            hasChanges = true;
        })

        $.post("togglePrintCheck", {id: jobId}, function(result){
            if(result.job.eSeal.toString() == "0" && result.job.eSeal_asbuilt.toString() == "0" && result.job.eSeal_PIL.toString() == "0" && result.job.eSeal_Print.toString() == "0"){
                return;
            } else {
                $('#printmodal').modal('toggle');
                $("#printmodal .block-content").scrollTop(0)
                $(".reportmodal-name").html(result.job.clientProjectNumber + ". " + result.job.clientProjectName + " " + result.job.state)
                currentJob = result.job.clientProjectNumber + ". " + result.job.clientProjectName + " " + result.job.state;
                
                if (result.success && result.job){
                    let length = result.data.length;
                    jobPrintStatus = result.data[length-1].print_request;
                    // if(result.data[length-1].print_status > 1) {
                    //     $(".addRequest-form").css('display', 'flex');
                    //     $(".currentRequest-form").css('display', 'flex');
                    // } else {
                    //     $(".addRequest-form").css('display', 'none');
                    //     $(".currentRequest-form").css('display', 'none');
                    // }
                    result.data.forEach(data => {
                        let flag = 0;
                        if(data.print_status > 1 ) {
                            $(".addRequest-form").css('display', 'flex');
                            $(".currentRequest-form").css('display', 'flex');
                        } else {
                            flag ++;
                        }
                        if(flag == length) {
                            $(".addRequest-form").css('display', 'none');
                            $(".currentRequest-form").css('display', 'none');
                        }
                    })
                    result.users.forEach((onedata, index) => {
                        $("#currentPrintRequest").append(`<option value=${index}>${index+1}</option>`)
                    })
                    $("#currentPrintRequest").val(length-1);
                    var jobStatus;
                    if(result.data[length-1].print_status == 1) {
                        jobStatus = "SAVED";
                    } else if (result.data[length-1].print_status == 2) {
                        jobStatus = "SUBMITTED";
                    } else if (result.data[length-1].print_status == 3) {
                        jobStatus = "PRINTED";
                    } else if (result.data[length-1].print_status == 4) {
                        jobStatus = "COMPLETED";
                    }else if (result.data[length-1].print_status == 0) {
                        jobStatus = "NOT SAVED";
                    }
                    $("#projectName").val(result.job.clientProjectNumber + ". " + result.job.clientProjectName + " " + result.job.state + " (" + jobStatus +")" );
                    $("#id").val(result.job.id);
                    
                    $("#company").val(result.data[length-1].address_id);
                    if(result.address[length-1]) {
                        $("#company-name").val(result.address[length-1].company_name);
                        $("#contact-name").val(result.address[length-1].contact_name);
                        $("#address1").val(result.address[length-1].address1);
                        $("#address2").val(result.address[length-1].address2);
                        $("#zip").val(result.address[length-1].zip);
                        $("#city").val(result.address[length-1].city);
                        $("#state").val(result.address[length-1].state);
                        $("#phonenumber").val(result.address[length-1].telno);
                        $("#extension").val(result.address[length-1].extension);
                    } else {
                        $("#company-name").val("");
                        $("#contact-name").val("");
                        $("#address1").val("");
                        $("#address2").val("");
                        $("#zip").val("");
                        $("#city").val("");
                        $("#state").val("");
                        $("#phonenumber").val("");
                        $("#extension").val("");
                    }

                    if(result.data[length-1].print_status > 1) {
                        $("#username").val(result.users[length-1].username);
                        $("#useremail").val(result.users[length-1].email);
                    } else {
                        $("#username").val("<?php echo Auth::user()->username?>");
                        $("#useremail").val("<?php echo Auth::user()->email?>");
                    }
    
                    $("#request_id").val(result.data[length-1].id);
                    $("#copies").val(result.data[length-1].copies);
                    $("#plan-set").val(result.data[length-1].plan_sheets);
                    $("#report").val(result.data[length-1].report_sheets);
                    if(result.data[length-1].seal_type == 0) {
                        $("#rubber-stamp").prop('checked', true);
                    } else if(result.data[length-1].seal_type == 1) {
                        $("#embossed").prop('checked', true);
                    }
    
                    if(result.data[length-1].signature == true) {
                        $("#yes").prop('checked', true);
                    } else if (result.data[length-1].signature == false){
                        $("#no").prop('checked', true);
                    }

                    if(result.data[length-1].delivery_method == 2) {
                        $("#2nd-day").prop('checked', true);
                    } else if (result.data[length-1].delivery_method == 1) {
                        $("#overnight").prop('checked', true);
                    } else if (result.data[length-1].delivery_method == 0) {
                        $("#mail").prop('checked', true);
                    }

                    $("#user-textarea-input").val(result.data[length-1].user_notes);
                    $("#printer-textarea-input").val(result.data[length-1].printer_notes);
                    $("#sent-date").val(result.data[length-1].sent);
                    $("#printed-date").val(result.data[length-1].printed);
                    $("#tracking").val(result.data[length-1].tracking);
                    $("#fedex").val(result.data[length-1].third_party_fedex);
                    
                    if(result.data[length-1].selected_files) {
                        let file_names = [];
                        file_names = result.data[length-1].selected_files.slice(1, result.data[length-1].selected_files.length - 1).split("\\n");
                        $('#filePrint').val(file_names.join("\n"));
                    }
                } else {
                    $(".addRequest-form").css('display', 'none');
                    $(".currentRequest-form").css('display', 'none');
                    $("#id").val(result.job.id);
                    $("#projectName").val(result.job.clientProjectNumber + ". " + result.job.clientProjectName + " " + result.job.state +  " (NOT SAVED)" );

                    $("#company option[value='0']").attr('selected', 'selected');
                    $("#company-name").val("");
                    $("#contact-name").val("");
                    $("#address1").val("");
                    $("#address2").val("");
                    $("#zip").val("");
                    $("#city").val("");
                    $("#state").val("");
                    $("#phonenumber").val("");
                    $("#extension").val("");

                    $("#request_id").val("");
                    $("#username").val("<?php echo Auth::user()->username?>");
                    $("#useremail").val("<?php echo Auth::user()->email?>");
                    $("#copies").val(1);
                    $("#plan-set").val("");
                    $("#report").val("");
                    $("#user-textarea-input").val("");
                    $("#printer-textarea-input").val("");
                    $("#printed-date").val("");
                    $("#sent-date").val("");
                    $("#tracking").val("");
                    $("#fedex").val("");
                    $("#filePrint").val("");
                    $("input:radio").attr("checked", false);
                    $("input:radio").prop("checked", false);
                }
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
                    
                    @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4 || Auth::user()->userrole == 5)
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

    function selectCompany(selected) {
        var selectedCompanyId = selected.value;
        if(selectedCompanyId == "0") {
            $("#company-name").val("");
            $("#contact-name").val("");
            $("#address1").val("");
            $("#address2").val("");
            $("#zip").val("");
            $("#city").val("");
            $("#state").val("");
            $("#phonenumber").val("");
            $("#extension").val("");
        } else {
            companyList.map((item, index) => {
                if(selectedCompanyId == item.id){
                    $("#company-name").val(item.company_name);
                    $("#contact-name").val(item.contact_name);
                    $("#address1").val(item.address1);
                    $("#address2").val(item.address2);
                    $("#zip").val(item.zip);
                    $("#city").val(item.city);
                    $("#state").val(item.state);
                    $("#phonenumber").val(item.phonenumber);
                    $("#extension").val(item.extension);
                }
            })

        }
    }

    function openJobFiles(){
        // var selectedProjectID = $("#id").val();
        // var url = "/jobFiles?projectId=" + selectedProjectID;
        // window.popUpWnds.push(window.open(url,'targetWindow', `toolbar=no, location=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1000, height=500`));
        // return false;
        $('#printmodal').modal('toggle');
        $('#reportmodal').modal('toggle');
        $('#file-table').empty();
        const currentFiles = $('#filePrint').val().split("\n");
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        $.post("getPrintFiles", {projectId: $('#id').val()}, function(result){
            swal.close();
            if(result.success && result.files){
                result.files.forEach(file => {
                    const elem = $(`<tr class='file' style="cursor:pointer;">
                            <td style="width: 5%; text-align:center">
                                <input type='checkbox' class="selectFile" ${currentFiles.includes(file.filename) ? "checked" : ""}>
                            </td>
                            <td class="fileName" style="width: 63%">${file.filename}</td>
                            <td style="width: 10%" sorttable_customkey="${file.size}">${parseInt(file.size / 1024) > 999 ? parseInt(file.size / 1024).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : parseInt(file.size / 1024)} KB</td>
                                <td style="width: 17%" sorttable_customkey="${file.modifiedDate}">${file.modifiedDate.replace(/T/g, " ").replace(/Z/g, " ")}</td>
                        </tr>`);
                    $('#file-table').append(elem);
                    elem.click(function () {
                        addPrintFile(this);
                    });
                    $('.selectFile').on('click', function(e){
                        e.stopPropagation()
                    });
                });
                if(currentFiles == ""){
                    selectedFiles = [];
                } else {
                    selectedFiles = currentFiles;
                }
            } else {
                swal.fire({ title: "Warning", text: result.message, icon: "warning", confirmButtonText: `OK` });
            }
        })
    }

    function addPrintFile (elem) {
        if(!$(elem).find(".selectFile").prop("checked")) {
            $(elem).find(".selectFile").prop("checked", true);
        } else {
            $(elem).find(".selectFile").prop("checked", false);
        }
    }

    function confirmFiles () {
        const state = Array.from(document.querySelectorAll("#file-table .selectFile"))
                        .map((elem) => elem.checked);
        selectedFiles = Array.from(document.querySelectorAll("#file-table .fileName"))
                            .map((elem, i) => state[i] ? elem.innerText : null).filter(fileName => !!fileName);
        $('#reportmodal').modal('toggle');
        $('#filePrint').val(selectedFiles.join("\n"))
    }

    function savePrint () {
        let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
            }
        });
        
        let data = {};
        data.requestId = $("#request_id").val();
        data.jobid = $("#id").val();
        data.copies = $("#copies").val();
        // data.companyId = $("#company").val();
        data.address_id = $("#company").val();
        data.plan_sheets = $("#plan-set").val();
        data.report_sheets = $("#report").val();
        data.seal_type = $('input[name="seal-type"]:checked').val();
        if($('input[name="signature"]:checked').val() == "0") {
            data.signature = false;
        } else if ($('input[name="signature"]:checked').val() == "1"){
            data.signature = true;
        }
        data.userEmail = $("#useremail").val();
        data.delivery_method = $('input[name="delivery-method"]:checked').val();
        data.fedex = $("#fedex").val();
        data.tracking = $("#tracking").val();
        data.user_notes = $("#user-textarea-input").val();
        data.printer_notes = $("#printer-textarea-input").val();
        data.print_file = $("#filePrint").val();

        data.printed = $("#printed-date").val();
        data.sent = $("#sent-date").val();

        data.company_name=$("#company-name").val();
        data.contact_name = $("#contact-name").val();
        data.address1 = $("#address1").val();
        data.address2 = $("#address2").val();
        data.zip = $("#zip").val();
        data.city = $("#city").val();
        data.state = $("#state").val();
        data.telno = $("#phonenumber").val();
        data.extension = $("#extension").val();
        data.addRequest = addRequest;

        console.log('data: ', data);

        // if($("#company-name").val() == "") {
        //     toast.fire('Sorry', "Please fill the company name", 'error');
        //     return;
        // }

        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        $.post("savePrint", data, function(result){
            swal.close();
            if (result.success){
                data.requestId = $("#request_id").val(result.printInfo.id);
                toast.fire('Success', 'The print data is saved.', 'success');
                if(result.newRequest){
                    $(".selected").find(".eseal_print").css('background-color', '#e4d800');
                } else {
                    if(result.printInfo.printStatus < 2) {
                        $(".selected").find(".eseal_print").css('background-color', '#e4d800');
                    }
                }

                var jobStatus;
                    if(result.printInfo.print_status == 1) {
                        jobStatus = "SAVED";
                    } else if (result.printInfo.print_status == 2) {
                        jobStatus = "SUBMITTED";
                    } else if (result.printInfo.print_status == 3) {
                        jobStatus = "PRINTED";
                    } else if (result.printInfo.print_status == 4) {
                        jobStatus = "COMPLETED";
                    }else if (result.printInfo.print_status == 0) {
                        jobStatus = "NOT SAVED";
                    }
                    $("#projectName").val(result.job.clientProjectNumber + ". " + result.job.clientProjectName + " " + result.job.state + " (" + jobStatus +")" );
                
                hasChanges = false;
                addRequest = false;
                if(result.created) {
                    $('#company').append(`<option value="${result.updatedAddress.id}">
                                       ${result.updatedAddress.company_name}
                                  </option>`);
                    companyList.push({  id: result.updatedAddress.id, 
                                        company_name: result.updatedAddress.company_name, 
                                        contact_name: (result.updatedAddress.contact_name?result.updatedAddress.contact_name:""), 
                                        address1:(result.updatedAddress.address1?result.updatedAddress.address1:""), 
                                        address2: (result.updatedAddress.address2?result.updatedAddress.address2:""), 
                                        city:(result.updatedAddress.city?result.updatedAddress.city:""), 
                                        state:(result.updatedAddress.state?result.updatedAddress.state:""), 
                                        zip:(result.updatedAddress.zip?result.updatedAddress.zip:""), 
                                        phonenumber:(result.updatedAddress.telno?result.updatedAddress.telno:""), 
                                        extension:(result.updatedAddress.extension?result.updatedAddress.extension:"")
                                    })
                } else {
                    companyList.map((company) => {
                        if(company.id == result.updatedAddress.id) {
                            company.company_name = result.updatedAddress.company_name;
                            company.contact_name = result.updatedAddress.contact_name;
                            company.address1 = result.updatedAddress.address1;
                            company.address2 = result.updatedAddress.address2;
                            company.city = result.updatedAddress.city;
                            company.state = result.updatedAddress.state;
                            company.zip = result.updatedAddress.zip;
                            company.phonenumber = result.updatedAddress.telno;
                            company.extension = result.updatedAddress.extension;
                        }
                    })
                }
            } else {
                toast.fire('Error', result.message, 'error');
            }
        });
    }

    function submitPrint () {
        $(".sealtype-form").css("color","#636b6f");
        $(".signature-form").css("color","#636b6f");
        $("#useremail").css("color","#636b6f");
        $(".deliver-form").css("color","#636b6f");
        $(".printFile-form").css("color","#636b6f");
        $(".printFile-form").find("textarea").css("border-color","#f1dfd2");
        $(".company-info input").css("border-color","#f1dfd2");
        $(".company-info label").css("color","#636b6f");
        $(".state-form").css("color","#636b6f");
        $(".state-form").find("select").css("border-color","#f1dfd2");

        var blankField = 0;
        let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
            }
        });
        
        let data = {};
        data.requestId = $("#request_id").val();
        data.jobid = $("#id").val();
        data.copies = $("#copies").val();
        // data.companyId = $("#company").val();
        data.address_id = $("#company").val();
        data.plan_sheets = $("#plan-set").val();
        data.report_sheets = $("#report").val();

        if($('input[name="seal-type"]:checked').val()){
            data.seal_type = $('input[name="seal-type"]:checked').val();
        } else {
            blankField++;
            $(".sealtype-form").css("color","#e04f1a");
        }

        if($('input[name="signature"]:checked').val()){
            if($('input[name="signature"]:checked').val() == "0") {
                data.signature = false;
            } else if ($('input[name="signature"]:checked').val() == "1"){
                data.signature = true;
            }
        } else {
            blankField++;
            $(".signature-form").css("color","#e04f1a");
        }

        if($("#useremail").val() == ""){
            blankField++;
            $("#useremail").css("color","#e04f1a");
        } else {
            data.userEmail = $("#useremail").val();
        }

        if($('input[name="delivery-method"]:checked').val()){
            data.delivery_method = $('input[name="delivery-method"]:checked').val();
        } else {
            blankField++;
            $(".deliver-form").css("color","#e04f1a");
        }

        data.fedex = $("#fedex").val();
        data.tracking = $("#tracking").val();
        data.user_notes = $("#user-textarea-input").val();
        data.printer_notes = $("#printer-textarea-input").val();

        if($("#filePrint").val() == "") {
            blankField++;
            $(".printFile-form").css("color","#e04f1a");
            $(".printFile-form").find("textarea").css("border-color","#e04f1a");
        }

        data.print_file = $("#filePrint").val();
        data.printed = $("#printed-date").val();
        data.sent = $("#sent-date").val();

        data.company_name=$("#company-name").val();
        data.contact_name = $("#contact-name").val();
        data.address1 = $("#address1").val();
        data.address2 = $("#address2").val();
        data.zip = $("#zip").val();
        data.city = $("#city").val();
        data.state = $("#state").val();
        data.telno = $("#phonenumber").val();
        data.extension = $("#extension").val();
        data.addRequest = addRequest;

        console.log('data: ', data);

        if($("#company-name").val() == "") {
            blankField++;
            $(".companyName-form").css("color","#e04f1a");
            $(".companyName-form").find("input").css("border-color","#e04f1a");
        }
        if($("#contact-name").val() == "") {
            blankField++;
            $(".contactName-form").css("color","#e04f1a");
            $(".contactName-form").find("input").css("border-color","#e04f1a");
        }
        if($("#address1").val() == "") {
            blankField++;
            $(".address1-form").css("color","#e04f1a");
            $(".address1-form").find("input").css("border-color","#e04f1a");
        }
        if($("#zip").val() == "") {
            blankField++;
            $(".zip-form").css("color","#e04f1a");
            $(".zip-form").find("input").css("border-color","#e04f1a");
        }
        if($("#state").val() == "") {
            blankField++;
            $(".state-form").css("color","#e04f1a");
            $(".state-form").find("select").css("border-color","#e04f1a");
        }
        if($("#city").val() == "") {
            blankField++;
            $(".city-form").css("color","#e04f1a");
            $(".city-form").find("input").css("border-color","#e04f1a");
        }
        if($("#phonenumber").val() == "") {
            blankField++;
            $(".phonenumber-form").css("color","#e04f1a");
            $(".phonenumber-form").find("input").css("border-color","#e04f1a");
        }

        if(blankField > 0) {
            return;
        }

        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        $.post("submitPrint", data, function(result){
            swal.close();
            if (result.success){
                // toast.fire('Success', 'The print data is submitted.', 'success');
                if(result.status == 2) {
                    $(".selected").find(".eseal_print").css('background-color', '#ff9999');
                } else if(result.status == 3) {
                    $(".selected").find(".eseal_print").css('background-color', '#7cb9e8');
                } else if(result.status == 4) {
                    $(".selected").find(".eseal_print").css('background-color', '#00ff00');
                }

                if(result.available) {
                    $(".selected").find(".printcheck").prop("checked", true);
                } else {
                    $(".selected").find(".printcheck").prop("checked", false);
                }
                $(".selected").removeClass("selected");
                $('#printmodal').modal('toggle');
                hasChanges = false;
                addRequest = false;
                if(result.created) {
                    $('#company').append(`<option value="${result.updatedAddress.id}">
                                       ${result.updatedAddress.company_name}
                                  </option>`);
                    companyList.push({  id: result.updatedAddress.id, 
                                        company_name: result.updatedAddress.company_name, 
                                        contact_name: (result.updatedAddress.contact_name?result.updatedAddress.contact_name:""), 
                                        address1:(result.updatedAddress.address1?result.updatedAddress.address1:""), 
                                        address2: (result.updatedAddress.address2?result.updatedAddress.address2:""), 
                                        city:(result.updatedAddress.city?result.updatedAddress.city:""), 
                                        state:(result.updatedAddress.state?result.updatedAddress.state:""), 
                                        zip:(result.updatedAddress.zip?result.updatedAddress.zip:""), 
                                        phonenumber:(result.updatedAddress.telno?result.updatedAddress.telno:""), 
                                        extension:(result.updatedAddress.extension?result.updatedAddress.extension:"")
                                    })
                } else {
                    companyList.map((company) => {
                        if(company.id == result.updatedAddress.id) {
                            company.company_name = result.updatedAddress.company_name;
                            company.contact_name = result.updatedAddress.contact_name;
                            company.address1 = result.updatedAddress.address1;
                            company.address2 = result.updatedAddress.address2;
                            company.city = result.updatedAddress.city;
                            company.state = result.updatedAddress.state;
                            company.zip = result.updatedAddress.zip;
                            company.phonenumber = result.updatedAddress.telno;
                            company.extension = result.updatedAddress.extension;
                        }
                    })
                }
            } else {
                toast.fire('Error', result.message, 'error');
            }
        });
    }

    function deletePrint () {
        let toast = Swal.mixin({
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-success m-1',
                cancelButton: 'btn btn-danger m-1',
                input: 'form-control'
                }
            });
        
        @if(Auth::user()->userrole == 0 || Auth::user()->userrole == 1)
            if(jobPrintStatus > 2){
                return;
            }
        @endif

        toast.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this print data!',
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-danger m-1',
                    cancelButton: 'btn btn-secondary m-1'
                },
                confirmButtonText: 'DELETE PRINT REQUEST',
                cancelButtonText: 'CANCEL',
                html: false,
                preConfirm: e => {
                    return new Promise(resolve => {
                        setTimeout(() => {
                            resolve();
                        }, 50);
                    });
                }
                }).then(result => {
                    if (result.value) {
                        swal.fire({ title: "Please wait...", showConfirmButton: false });
                        swal.showLoading();
                        $.post("deletePrint", {requestId: $('#request_id').val(), jobId: $('#id').val()}, function(result){
                            swal.close();
                            if(result.success == true) {
                                toast.fire('Success', "", 'success');
                                if(result.status == 2) {
                                    $(".selected").find(".eseal_print").css('background-color', '#ff9999');
                                } else if(result.status == 3) {
                                    $(".selected").find(".eseal_print").css('background-color', '#7cb9e8');
                                } else if(result.status == 4) {
                                    $(".selected").find(".eseal_print").css('background-color', '#00ff00');
                                } else if(result.status == 1) {
                                    $(".selected").find(".eseal_print").css('background-color', '#e4d800');
                                } else {
                                    $(".selected").find(".eseal_print").css('background-color', '#000000');
                                }

                                if(result.available) {
                                    $(".selected").find(".printcheck").prop("checked", true);
                                } else {
                                    $(".selected").find(".printcheck").prop("checked", false);
                                }

                                $('#printmodal').modal('toggle');
                                $('#projects').DataTable().draw();
                            } else {
                                toast.fire('Error',"This data is not deleted", 'error');
                            }
                        })
                    } else if (result.dismiss === 'cancel') {
                        toast.fire('Cancelled', 'Data is safe', 'info');
                    }
                });
    }

    function exit () {
        let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
            }
        });
        if(hasChanges) {
            toast.fire({
                title: 'Save changes?',
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-danger m-1',
                    cancelButton: 'btn btn-secondary m-1',
                },
                confirmButtonText: 'SAVE CHANGES',
                cancelButtonText: 'DISCARD CHANGES',
                html: false,
                preConfirm: e => {
                    return new Promise(resolve => {
                        setTimeout(() => {
                            resolve();
                        }, 50);
                    });
                }
            }).then(result => {
                if (result.value) {

                } else if (result.dismiss === 'cancel') {
                    hasChanges = false;
                    $("#printmodal").modal('toggle');
                }
            });
        } else  {
            $("#printmodal").modal('toggle');
        }
    }

    function addPrintRequest () {
        $("#projectName").val(currentJob + " (NOT SAVED)");
        $("#company option[value='0']").attr('selected', 'selected');
        $("#company-name").val("");
        $("#contact-name").val("");
        $("#address1").val("");
        $("#address2").val("");
        $("#zip").val("");
        $("#city").val("");
        $("#state").val("");
        $("#phonenumber").val("");
        $("#extension").val("");

        $("#request_id").val('');
        $("#copies").val(1);
        $("#plan-set").val("");
        $("#report").val("");
        $("#user-textarea-input").val("");
        $("#printer-textarea-input").val("");
        $("#printed-date").val("");
        $("#sent-date").val("");
        $("#tracking").val("");
        $("#fedex").val("");
        $("#filePrint").val("");
        $("#username").val("<?php echo Auth::user()->username?>");
        $("#useremail").val("<?php echo Auth::user()->email?>");
        $("input:radio").attr("checked", false);
        $("input:radio").prop("checked", false);
        $("#printed-date").val("");
        $("#sent-date").val("");
        $("#tracking").val("");
        $('#currentPrintRequest').append(`<option value="${$('#currentPrintRequest option').length}">${$('#currentPrintRequest option').length+1}</option>`);
        $('#currentPrintRequest').val($('#currentPrintRequest option').length-1);
        addRequest = true;
    }

    function openActionWnd(obj, jobId) {
        $.ajax({
            url:"getActionType",
            type:'post',
            data:{jobId: jobId},
            success: function(res){
                if(res.success && res.type == 1){
                    openReviewTab(jobId);
                }else if(res.success && res.type == 2){
                    togglePrintCheck(obj, jobId);
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