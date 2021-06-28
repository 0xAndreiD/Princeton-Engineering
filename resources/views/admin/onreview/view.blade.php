@extends('admin.layout')

@section('content')
<style>
.jstree-node.jstree-leaf > .jstree-icon.jstree-ocl {
    display: none;
}
</style>

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
                <div class="block-content review-block-content pt-3 pb-3" style="min-height: 154px;" id="envSection">
                    <div class="flexRow">
                        <input type='checkbox' id="exposure"> <label for="exposure" class="mb-0 ml-2 text-left" id="exposure-text">Exposure: </label>
                    </div>
                    <div class="flexRow">
                        <input type='checkbox' id="occupancy"> <label for="occupancy" class="mb-0 ml-2 text-left" id="occupancy-text">Occupancy: </label>
                    </div>
                    <div class="flexRow">
                        <input type='checkbox' id="wind"> <label for="wind" class="mb-0 ml-2 text-left" id="wind-text">Wind: </label>
                    </div>
                    <div class="flexRow">
                        <input type='checkbox' id="snow"> <label for="snow" class="mb-0 ml-2 text-left" id="snow-text">Snow: </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Codes</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3" style="min-height: 154px;" id="codeSection">
                    <div class="flexRow">
                        <input type='checkbox' id="IBC"> <label for="IBC" class="mb-0 ml-2 text-left" id="IBC-text">IBC: </label>
                    </div>
                    <div class="flexRow">
                        <input type='checkbox' id="ASCE"> <label for="ASCE" class="mb-0 ml-2 text-left" id="ASCE-text">ASCE: </label>
                    </div>
                    <div class="flexRow">
                        <input type='checkbox' id="NEC"> <label for="NEC" class="mb-0 ml-2 text-left" id="NEC-text">NEC: </label>
                    </div>
                    <div class="flexRow">
                        <input type='checkbox' id="StateCode"> <label for="StateCode" class="mb-0 ml-2 text-left" id="StateCode-text">State Code: </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Electrical</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3" id="elecSection">
                    <div class="row">
                        <div class="col-md-1">
                            <span style="width: 100px;" class="font-w600">Module</span>
                        </div>
                        <div class="col-md-8 flexRow">
                            <input type='checkbox' id="Module"> <label for="Module" class="mb-0 ml-2 mr-5 text-left" id="Module-text">MFR / Model: </label>
                        </div>
                        <div class="col-md-3 flexRow">
                            <input type='checkbox' id="ModuleQuantity"> <label for="ModuleQuantity" class="mb-0 ml-2 text-left" id="ModuleQuantity-text">Quantity: </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <span style="width: 100px;" class="font-w600">Inverter</span>
                        </div>
                        <div class="col-md-8 flexRow">
                            <input type='checkbox' id="Inverter"> <label for="Inverter" class="mb-0 ml-2 mr-5 text-left" id="Inverter-text">MFR / Model: </label>
                        </div>
                        <div class="col-md-3 flexRow">
                            <input type='checkbox' id="InverterQuantity"> <label for="InverterQuantity" class="mb-0 ml-2 text-left" id="InverterQuantity-text">Quantity: </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <span style="width: 100px;" class="font-w600">Power</span>
                        </div>
                        <div class="col-md-4 flexRow">
                            <input type='checkbox' id="Power"> <label for="Power" class="mb-0 ml-2 mr-5 text-left" id="Power-text">Tot DC watts: </label>
                        </div>
                        <div class="col-md-4 flexRow">
                            <input type='checkbox' id="SumInvAmps"> <label for="SumInvAmps" class="mb-0 ml-2 text-left" id="SumInvAmps-text">Sum Inv Amps: </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <span style="width: 100px;" class="font-w600">OCPD</span>
                        </div>
                        <div class="col-md-4 flexRow">
                            <input type='checkbox' id="MinA"> <label for="MinA" class="mb-0 ml-2 mr-5 text-left" id="MinA-text">Min A: </label>
                        </div>
                        <div class="col-md-4 flexRow">
                            <input type='checkbox' id="RecommendedA"> <label for="RecommendedA" class="mb-0 ml-2 text-left" id="RecommendedA-text">Recommended A: </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <span style="width: 100px;" class="font-w600">Wire</span>
                        </div>
                        <div class="col-md-10 flexRow">
                            <input type='checkbox' id="MinCuWireSize"> <label for="MinCuWireSize" class="mb-0 ml-2 mr-5 text-left" id="MinCuWireSize-text">Min Cu Wire Size: </label>
                        </div>
                    </div>
                    <!-- <div class="flexAndCenter">
                        <span style="width: 100px;">Wire</span>
                        <input type='checkbox' id="MinCuWireSize"> <label for="MinCuWireSize" class="mb-0 ml-2 mr-5" id="MinCuWireSize-text">Min Cu Wire Size: </label>
                    </div> -->
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
                <div class="block-content review-block-content pt-3 pb-3" id="structSection">
                    <div class="flexRow">
                        <input type='checkbox' id="ColTieKneeWalls"> <label for="ColTieKneeWalls" class="mb-0 ml-2 text-left" id="ColTieKneeWalls-text">Collar Tie / Knee Walls: </label>
                    </div>
                    <div class="flexRow">
                        <input type='checkbox' id="StructNotes"> <label for="StructNotes" class="mb-0 ml-2 text-left" id="StructNotes-text">Structural Notes: </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Controls</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3 flexAndCenter" style="justify-content: space-between">
                    <a class="btn btn-outline-danger mr-1" style="width:100px;" href="{{ route('projectlist') }}">Cancel</a>
                    <button class="btn btn-outline-success mr-1" onclick="autoNote()">Auto Note</button>
                    <button class="btn btn-outline-warning mr-1" onclick="clearNote()">Clear Note</button>
                    <div class="flexRow">
                        <input type='checkbox' id="ControlCheck" onchange="checkboxAll()"> <label for="ControlCheck" class="mb-0 ml-2 text-left">Check / Uncheck ALL </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Document Control</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3 flexAndCenter" style="justify-content: space-around;">
                    <button class="btn btn-outline-primary mr-1" style="width:100px;" onclick="showReportDlg()">Reports</button>
                    <button class="btn btn-outline-info mr-1" onclick="showInDirDlg()">In Directory</a>
                    <button class="btn btn-outline-secondary mr-1" onclick="eSealUpload()">eSeal / Upload</button>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="block">
                <div class="block-header review-block-header pt-1 pb-1">
                    <h3 class="block-title">Flag to Clear</h3>
                </div>
                <div class="block-content review-block-content pt-3 pb-3">
                    <div class="flexRow">
                        <input type='checkbox' id="Review" onchange='togglePlanCheck({{$job["id"]}})' <?php echo $job['planCheck'] == 1 ? "checked" : ""; ?>> <label for="Review" class="mb-0 ml-2 text-left">Review</label>
                    </div>
                    <div class="flexRow">
                        <input type='checkbox' id="Asbuilt" onchange='toggleAsBuilt({{$job["id"]}})' <?php echo $job['asBuilt'] == 1 ? "checked" : ""; ?>> <label for="Asbuilt" class="mb-0 ml-2 text-left">As-built</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="post" id="submitChat">
        <div class="row flexAndCenter">
            <div class="col-md-1 text-center">
                <input type="submit" name="send" id="send" class="btn btn-outline-dark" value="Add Chat" />
            </div>
            <div class="col-md-11">
                <textarea id="addChatMsg" style="width: 100%; height: 100px; resize: none;" name="message" required></textarea>
            </div>
        </div>
        <input type="hidden" value="{{ $projectId }}" name="projectId" id="projectId">
    </form>    

    <div class="row mt-2">
        <div class="col-md-12">
            <textarea id="chatLog" style="width: 100%; height: 400px; resize: none; padding: 5px 20px;" readonly>
@for($i = 0; $i < count($messages); $i ++)
<?php echo "Comment " . (count($messages) - $i) . ": " . $messages[$i]['username'] . " on " . $messages[$i]['datetime'] . "\n"; ?>
    <?php echo $messages[$i]['text'] . "\n"; ?>

@endfor
            </textarea>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="treeDlg" tabindex="-1" role="dialog" aria-labelledby="modal-terms" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title" id="treeDlgTitle"></h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div id="filetree" style="height:300px;">
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.msgCount = {{ count($messages) }};
</script>
<script src="{{ asset('/js/plugins/jstree/jstree.min.js') }}"></script>

@include('admin.onreview.script')
@endsection