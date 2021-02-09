@extends('admin.layout')

@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Project Input File Management
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    RFD Input File Backup / Restore Page
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Backup Page Content -->
<div class="content" style="text-align:left">
    <div class="row" style="justify-content: space-around;">
        <div class="col-md-4">
            <h2 class="content-heading pt-0">Company Filter</h2>
            <select class="form-control" id="company" name="company">
                <option value="-1">All</option>
                @foreach ($companyList as $company)
                    <option value="{{$company->id}}">{{ $company->company_name }}</option>
                @endforeach
            </select>

            <h2 class="content-heading mt-3">Date Filter</h2>
            <div class="input-daterange input-group" data-date-format="mm/dd/yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                <input type="text" class="form-control backupInput" id="date-from" name="date-from" placeholder="From" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                <div class="input-group-prepend input-group-append">
                    <span class="input-group-text font-w600">
                        <i class="fa fa-fw fa-arrow-right"></i>
                    </span>
                </div>
                <input type="text" class="form-control backupInput" id="date-to" name="date-to" placeholder="To" data-week-start="1" data-autoclose="true" data-today-highlight="true">
            </div>

            <h2 class="content-heading mt-3">Project Number Filter</h2>
            <div class="input-group" >
                <input type="number" class="form-control backupInput" id="projectid-from" placeholder="From" >
                <div class="input-group-prepend input-group-append">
                    <span class="input-group-text font-w600">
                        <i class="fa fa-fw fa-arrow-right"></i>
                    </span>
                </div>
                <input type="number" class="form-control backupInput" id="projectid-to" placeholder="To" >
            </div>
            <div class="mt-4" style="display: flex; justify-content: space-between;"> 
                <button type="button" class="btn btn-hero-primary" onclick="showProcess(true)">Backup</button> 
                <button type="button" class="btn btn-danger" onclick="showProcess(false)">Restore</button>
            </div>
        </div>
        
    </div>
</div>

<div id="backupModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
    <div class="block-header bg-primary-dark">
        <h3 class="block-title">Progress</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                <i class="fa fa-fw fa-times"></i>
            </button>
        </div>
    </div>
      <div class="modal-body">
        <div>
            <label for="comment">File List:</label>
            <textarea id="fileListBox"></textarea>
        </div>
        <div class="input-group mt-4">
            <div class="input-group-prepend">
                <button type="button" class="btn btn-dark">Current</button>
            </div>
            <input type="text" class="form-control" id="curFileName" name="example-group3-input1" placeholder="File Name" style="border: 1px solid grey;">
        </div>
        <div class="col-12 col-md-12 mt-4" id="loadingAnim">
            <i class="fa fa-3x fa-circle-notch fa-spin"></i>
        </div>
        <div class="mt-4">
            <label for="comment">Total Progress:</label>
            <div class="progress push">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" id="totalProgress" style="width: 30%;" aria-valuenow="5" aria-valuemin="5" aria-valuemax="100">
                    <span class="font-size-sm font-w600" id="progressValue">0%</span>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" onclick="startProcess()" id="startBtn">Start</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" id="closeBtn">Close</button>
      </div>
    </div>

  </div>
</div>

@include('admin.backupinput.script')
@endsection