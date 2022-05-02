@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : 'user.layout'))

@section('content')

<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Seal Positioning
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Assign a template to a state, or set custom seal position
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="content" style="text-align:left">
    <div class="text-center">
        <button type="button" class="btn btn-secondary dropdown-toggle mr-5" id="state-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Select State
        </button>
        <div class="dropdown-menu" aria-labelledby="state-dropdown" style="height: 300px; overflow: auto;">
            <a class="dropdown-item" href="javascript:stateChange('STD')">STD</a>
            <a class="dropdown-item" href="javascript:stateChange('AL')">AL</a>
            <a class="dropdown-item" href="javascript:stateChange('AZ')">AZ</a>
            <a class="dropdown-item" href="javascript:stateChange('AR')">AR</a>
            <a class="dropdown-item" href="javascript:stateChange('CA')">CA</a>
            <a class="dropdown-item" href="javascript:stateChange('CO')">CO</a>
            <a class="dropdown-item" href="javascript:stateChange('CT')">CT</a>
            <a class="dropdown-item" href="javascript:stateChange('DE')">DE</a>
            <a class="dropdown-item" href="javascript:stateChange('FL')">FL</a>
            <a class="dropdown-item" href="javascript:stateChange('GA')">GA</a>
            <a class="dropdown-item" href="javascript:stateChange('HI')">HI</a>
            <a class="dropdown-item" href="javascript:stateChange('ID')">ID</a>
            <a class="dropdown-item" href="javascript:stateChange('IL')">IL</a>
            <a class="dropdown-item" href="javascript:stateChange('IN')">IN</a>
            <a class="dropdown-item" href="javascript:stateChange('IA')">IA</a>
            <a class="dropdown-item" href="javascript:stateChange('KS')">KS</a>
            <a class="dropdown-item" href="javascript:stateChange('KY')">KY</a>
            <a class="dropdown-item" href="javascript:stateChange('LA')">LA</a>
            <a class="dropdown-item" href="javascript:stateChange('ME')">ME</a>
            <a class="dropdown-item" href="javascript:stateChange('MD')">MD</a>
            <a class="dropdown-item" href="javascript:stateChange('MA')">MA</a>
            <a class="dropdown-item" href="javascript:stateChange('MI')">MI</a>
            <a class="dropdown-item" href="javascript:stateChange('MN')">MN</a>
            <a class="dropdown-item" href="javascript:stateChange('MS')">MS</a>
            <a class="dropdown-item" href="javascript:stateChange('MO')">MO</a>
            <a class="dropdown-item" href="javascript:stateChange('MT')">MT</a>
            <a class="dropdown-item" href="javascript:stateChange('NE')">NE</a>
            <a class="dropdown-item" href="javascript:stateChange('NV')">NV</a>
            <a class="dropdown-item" href="javascript:stateChange('NH')">NH</a>
            <a class="dropdown-item" href="javascript:stateChange('NJ')">NJ</a>
            <a class="dropdown-item" href="javascript:stateChange('NM')">NM</a>
            <a class="dropdown-item" href="javascript:stateChange('NY')">NY</a>
            <a class="dropdown-item" href="javascript:stateChange('NC')">NC</a>
            <a class="dropdown-item" href="javascript:stateChange('ND')">ND</a>
            <a class="dropdown-item" href="javascript:stateChange('OH')">OH</a>
            <a class="dropdown-item" href="javascript:stateChange('OK')">OK</a>
            <a class="dropdown-item" href="javascript:stateChange('OR')">OR</a>
            <a class="dropdown-item" href="javascript:stateChange('PA')">PA</a>
            <a class="dropdown-item" href="javascript:stateChange('RI')">RI</a>
            <a class="dropdown-item" href="javascript:stateChange('SC')">SC</a>
            <a class="dropdown-item" href="javascript:stateChange('SD')">SD</a>
            <a class="dropdown-item" href="javascript:stateChange('TN')">TN</a>
            <a class="dropdown-item" href="javascript:stateChange('TX')">TX</a>
            <a class="dropdown-item" href="javascript:stateChange('UT')">UT</a>
            <a class="dropdown-item" href="javascript:stateChange('VT')">VT</a>
            <a class="dropdown-item" href="javascript:stateChange('VA')">VA</a>
            <a class="dropdown-item" href="javascript:stateChange('WA')">WA</a>
            <a class="dropdown-item" href="javascript:stateChange('WV')">WV</a>
            <a class="dropdown-item" href="javascript:stateChange('WI')">WI</a>
            <a class="dropdown-item" href="javascript:stateChange('WY')">WY</a>
        </div>
        <input type="file" name="file" id="file" class="inputfile" accept=".pdf, .PDF|PDF Files/*"/>
        <label for="file" class="btn btn-secondary mb-0">
            <i class="fa fa-upload"></i>&nbsp;
            <span>Choose a PDF...</span>
        </label>
    </div>
    
    <div class="block mb-0">
        <div class="block-content text-center">
            <div class="btn-group push">
                <button type="button" class="btn btn-warning dropdown-toggle mr-5" id="template-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Copy From A Template
                </button>
                <div class="dropdown-menu" aria-labelledby="template-dropdown" style="max-height: 300px; overflow: auto;" id="template-list">
                    <!-- <a class="dropdown-item" href="javascript:stateChange('AL')">AL</a> -->
                </div>
            </div>
            <div class="btn-group push">
                <button type="button" class="btn btn-secondary draggable mr-2" id="sealImage" style="z-index: 1;">
                    <i class="far fa-file-image"></i>
                    Seal Image
                </button>
                <button type="button" class="btn btn-secondary draggable mr-2" id="sealText" style="z-index: 1;">
                    <i class="fa fa-font"></i>
                    Seal Text
                </button>
                <button type="button" class="btn btn-secondary draggable mr-2" id="sealSupplement" style="z-index: 1;">
                    <i class="fa fa-font"></i>
                    Supplemental Seal Text
                </button>
                <button type="button" class="btn btn-secondary mr-5 draggable mr-2" id="eSign" style="z-index: 1;">
                    <i class="fa fa-file-signature"></i>
                    eSignature
                </button>
            </div>
            <div class="btn-group push mr-2">
                <button type="button" class="btn btn-primary" onclick="saveContent()">
                    <i class="fa fa-save"></i>
                    Save
                </button>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: center;" id="canvasContainer">
        <canvas id="canvasPane" width="900" height="900" style="border: 1px solid black;"></canvas>
        <div class="ml-5">
            <div class="form-group mt-5">
                <label for="page-width">Page Width (Inches)</label>
                <input type="text" class="form-control" id="page-width" disabled="">
            </div>
            <div class="form-group">
                <label for="page-height">Page Height (Inches)</label>
                <input type="text" class="form-control" id="page-height" disabled="">
            </div>
            <div id="object-dimension-pane" style="display: none;">
                <div class="form-group mt-5">
                    <label for="object-left">Selected Object</label>
                    <input type="text" class="form-control" id="object-name" disabled="">
                </div>
                <div class="form-group">
                    <label for="object-left">Left (Inches)</label>
                    <input type="text" class="form-control" id="object-left" style="border: 1px solid #82b54b;">
                </div>
                <div class="form-group">
                    <label for="object-top">Top (Inches)</label>
                    <input type="text" class="form-control" id="object-top" style="border: 1px solid #82b54b;">
                </div>
                <div class="form-group">
                    <label for="object-width">Width (Inches)</label>
                    <input type="text" class="form-control" id="object-width" style="border: 1px solid #82b54b;">
                </div>
                <div class="form-group">
                    <label for="object-height">Height (Inches)</label>
                    <input type="text" class="form-control" id="object-height" style="border: 1px solid #82b54b;">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery.ui.min.js') }}"></script>
<script src="{{ asset('js/fabric.min.js') }}"></script>
@include('clientadmin.sealpos.script')
@endsection