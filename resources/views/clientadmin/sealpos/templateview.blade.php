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
                    You can create some seal templates
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="content" style="text-align:left">
    <div class="text-center">
        <input type="file" name="file" id="file" class="inputfile" accept=".pdf, .PDF|PDF Files/*"/>
        <label for="file" class="btn btn-secondary mb-0">
            <i class="fa fa-upload"></i>&nbsp;
            <span>Choose a PDF...</span>
        </label>
    </div>
    
    <div class="block mb-0">
        <div class="block-content text-center">
            
            <div class="btn-group push">
                <button type="button" class="btn btn-secondary draggable" id="sealImage" style="z-index: 1;">
                    <i class="far fa-file-image"></i>
                    Seal Image
                </button>
                <button type="button" class="btn btn-secondary draggable" id="sealText" style="z-index: 1;">
                    <i class="fa fa-font"></i>
                    Seal Text
                </button>
                <button type="button" class="btn btn-secondary draggable" id="sealSupplement" style="z-index: 1;">
                    <i class="fa fa-font"></i>
                    Supplemental Seal Text
                </button>
                <button type="button" class="btn btn-secondary mr-5 draggable" id="eSign" style="z-index: 1;">
                    <i class="fa fa-file-signature"></i>
                    eSignature
                </button>
            </div>
            <div class="btn-group push">
                <button type="button" class="btn btn-info" onclick="saveTemplate()">
                    <i class="fa fa-save"></i>
                    Save Template
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
                <input type="hidden" value="{{ $id }}" id="templateId">
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery.ui.min.js') }}"></script>
<script src="{{ asset('js/fabric.min.js') }}"></script>
@include('clientadmin.sealpos.templatescript')
@endsection