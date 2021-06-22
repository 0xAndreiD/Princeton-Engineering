@extends('admin.layout')

@section('content')

<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Online Review
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Review the job's datas through this page
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="content" style="text-align:left">
    <div class="row">
        <div class="col-md-2">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Environmental</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3">
                    <div class="flexAndCenter">
                        <input type='checkbox' id="exposure"> <label for="exposure" class="mb-0 ml-2" id="exposure-text">Exposure: </label>
                    </div>
                    <div class="flexAndCenter">
                        <input type='checkbox' id="occupancy"> <label for="occupancy" class="mb-0 ml-2" id="occupancy-text">Occupancy: </label>
                    </div>
                    <div class="flexAndCenter">
                        <input type='checkbox' id="wind"> <label for="wind" class="mb-0 ml-2" id="wind-text">Wind: </label>
                    </div>
                    <div class="flexAndCenter">
                        <input type='checkbox' id="snow"> <label for="snow" class="mb-0 ml-2" id="snow-text">Snow: </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Codes</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3">
                    <div class="flexAndCenter">
                        <input type='checkbox' id="IBC"> <label for="IBC" class="mb-0 ml-2" id="IBC-text">IBC: </label>
                    </div>
                    <div class="flexAndCenter">
                        <input type='checkbox' id="ASCE"> <label for="ASCE" class="mb-0 ml-2" id="ASCE-text">ASCE: </label>
                    </div>
                    <div class="flexAndCenter">
                        <input type='checkbox' id="NEC"> <label for="NEC" class="mb-0 ml-2" id="NEC-text">NEC: </label>
                    </div>
                    <div class="flexAndCenter">
                        <input type='checkbox' id="StateCode"> <label for="StateCode" class="mb-0 ml-2" id="StateCode-text">State Code: </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Electrical</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3">
                    <div class="flexAndCenter">
                        <span style="width: 100px;">Module</span>
                        <input type='checkbox' id="Module"> <label for="Module" class="mb-0 ml-2 mr-5" id="Module-text">MFR / Model: </label>
                        <input type='checkbox' id="ModuleQuantity"> <label for="ModuleQuantity" class="mb-0 ml-2" id="ModuleQuantity-text">Quantity: </label>
                    </div>
                    <div class="flexAndCenter">
                        <span style="width: 100px;">Inverter</span>
                        <input type='checkbox' id="Inverter"> <label for="Module" class="mb-0 ml-2 mr-5" id="Inverter-text">MFR / Model: </label>
                        <input type='checkbox' id="InverterQuantity"> <label for="InverterQuantity" class="mb-0 ml-2" id="InverterQuantity-text">Quantity: </label>
                    </div>
                    <div class="flexAndCenter">
                        <span style="width: 100px;">Power</span>
                        <input type='checkbox' id="Power"> <label for="Power" class="mb-0 ml-2 mr-5" id="Power-text">Tot DC watts: </label>
                        <input type='checkbox' id="SumInvAmps"> <label for="SumInvAmps" class="mb-0 ml-2" id="SumInvAmps-text">Sum Inv Amps: </label>
                    </div>
                    <div class="flexAndCenter">
                        <span style="width: 100px;">OCPD</span>
                        <input type='checkbox' id="MinA"> <label for="MinA" class="mb-0 ml-2 mr-5" id="MinA-text">Min A: </label>
                        <input type='checkbox' id="RecommendedA"> <label for="RecommendedA" class="mb-0 ml-2" id="RecommendedA-text">Recommended A: </label>
                    </div>
                    <div class="flexAndCenter">
                        <span style="width: 100px;">Wire</span>
                        <input type='checkbox' id="MinCuWireSize"> <label for="MinCuWireSize" class="mb-0 ml-2 mr-5" id="MinCuWireSize-text">Min Cu Wire Size: </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Structural</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3">
                    <div class="flexAndCenter">
                        <input type='checkbox' id="ColTieKneeWalls"> <label for="ColTieKneeWalls" class="mb-0 ml-2" id="ColTieKneeWalls-text">Collar Tie / Knee Walls: </label>
                    </div>
                    <div class="flexAndCenter">
                        <input type='checkbox' id="StructNotes"> <label for="StructNotes" class="mb-0 ml-2" id="StructNotes-text">Structural Notes: </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Controls</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3 flexAndCenter" style="justify-content: space-between">
                    <button class="btn btn-outline-danger mr-1" style="width:100px;">Exit</button>
                    <button class="btn btn-outline-success mr-1">Auto Note</button>
                    <button class="btn btn-outline-warning mr-1">Clear Note</button>
                    <div class="flexAndCenter">
                        <input type='checkbox' id="ControlCheck"> <label for="ControlCheck" class="mb-0 ml-2">Check / Uncheck ALL </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Document Control</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3 flexAndCenter" style="justify-content: space-around;">
                    <button class="btn btn-outline-primary mr-1" style="width:100px;">Reports</button>
                    <button class="btn btn-outline-info mr-1">In Directory</button>
                    <button class="btn btn-outline-secondary mr-1">eSeal / Upload</button>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Flag to Clear</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3">
                    <div class="flexAndCenter">
                        <input type='checkbox' id="Review"> <label for="Review" class="mb-0 ml-2">Review</label>
                    </div>
                    <div class="flexAndCenter">
                        <input type='checkbox' id="Asbuilt"> <label for="Asbuilt" class="mb-0 ml-2">As-built</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row flexAndCenter">
        <div class="col-md-1 text-center">
            <button class="btn btn-outline-dark">Add Chat</button>
        </div>
        <div class="col-md-11">
            <textarea id="addChatMsg" style="width: 100%; height: 100px; resize: none;"></textarea>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12">
            <textarea id="chatLog" style="width: 100%; height: 400px; resize: none;" readonly></textarea>
        </div>
    </div>
</div>

<input type="text" value="{{ $projectId }}" id="projectId" hidden>
    
@include('admin.onreview.script')
@endsection