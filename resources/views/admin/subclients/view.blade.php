@extends('admin.layout')

@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Sub Clients Management Panel
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Manage sub clients here
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<div class="content" style="text-align:left">
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Sub-Client List</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" 
                    data-toggle='modal' data-target='#modal-block-normal'
                    onclick="showAddClient()">
                    <i class="fa fa-plus"></i> Add Client
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table id="clients" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">ID</th>
                            <th style="width:25%">Company Name</th>
                            <th style="width:15%">Name</th>
                            <th style="width:10%;">Number</th>
                            <th style="width:10%;">Tel No</th>
                            <th style="width:15%;">Address</th>
                            <th style="width:10%;">Website</th>
                            <th style="min-width:150px;">Action</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="searchHead"> 
                                <select placeholder="Search Company" class="searchBox" id="companyFilter">
                                    <option value="">All</option>
                                    @foreach($companyList as $company)
                                        <option value="{{ $company['company_name'] }}">{{ $company['company_name'] }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Name" class="searchBox" id="nameFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Number" class="searchBox" id="numberFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Phone" class="searchBox" id="phoneFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Address" class="searchBox" id="addressFilter"> </th>
                            <th class="searchHead"> <input type="text" placeholder="Search Website" class="searchBox" id="siteFilter"> </th>
                            <th></th>
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
            <form class="js-validation" onsubmit="return false;" method="POST" id="clientForm">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Sub Client Info</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content" style="max-height: 700px;overflow: auto;">
                        <div class="row items-push">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="company">Company <span class="text-danger">*</span></label><br/>
                                    <select class="form-control" id="company" name="company" style="border: 1px solid pink;">
                                        @foreach ($companyList as $company)
                                            <option value="{{$company->id}}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="number">Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="number" name="number" placeholder="Enter A Number.." style="border: 1px solid pink;">
                                    <input type="hidden" class="form-control" id="id" name="id">
                                </div>
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter A Name.." style="border: 1px solid pink;">
                                </div>
                                <div class="form-group">
                                    <label for="telno">Tel No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="telno" name="telno" placeholder="212-999-0000" style="border: 1px solid pink;">
                                </div>
                                <div class="form-group">
                                    <label for="address1">Street Address 1 <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="address1" name="address1" placeholder="Street Address.." style="border: 1px solid pink;">
                                </div>
                                <div class="form-group">
                                    <label for="address1">Street Address 2 <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="address2" name="address2" placeholder="Secondary Street Address(Optional)" style="border: 1px solid pink;">
                                </div>
                                <div class="form-group">
                                    <label for="city">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City.." style="border: 1px solid pink;">
                                </div>
                                <div class="form-group">
                                    <label for="state">State <span class="text-danger">*</span></label>
                                    <select id="state" style="border: 1px solid pink;">
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
                                    <label for="zip">Zip <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip.." style="border: 1px solid pink;">
                                </div>
                                <div class="form-group">
                                    <label for="contact_name">Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Person name.." style="border: 1px solid pink;">
                                </div>
                                <div class="form-group">
                                    <label for="country_code">Country Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="country_code" name="country_code" placeholder="Code.." style="border: 1px solid pink;">
                                </div>
                                <div class="form-group">
                                    <label for="website">Website <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="website" name="website" placeholder="http://example.com" style="border: 1px solid pink;">
                                </div>
                                <div class="form-group">
                                    <label for="logolink">Company Logo <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" style="border: 1px solid pink; margin-top: 0px;" class="form-control" id="logolink" name="logolink" placeholder="None Uploaded..." disabled>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-dark" id="import-open" onclick="onUploadOpen()">Upload</button>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control" id="logofile" name="logofile" hidden>
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
        var table = $('#clients').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "orderCellsTop": true,
            "pageLength" : 50,
            "ajax":{
                    "url": "{{ url('getSubClients') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
            "columns": [
                { "data": "id" },
                { "data": "company" },
                { "data": "name" },
                { "data": "number" },
                { "data": "telno" },
                { "data": "address" },
                { "data": "website" },
                { "data": "actions", "orderable": false }
            ]	 
        });

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $("#companyFilter, #nameFilter, #numberFilter, #phoneFilter, #addressFilter, #siteFilter").on('keyup change', function() {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });
    });
</script>

<script src="{{ asset('js/jquery.form.js') }}"></script>
@include('admin.subclients.script')
@endsection