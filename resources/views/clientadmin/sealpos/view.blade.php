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
                    Move your seal and texts to your correct position
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="content" style="text-align:left">
    <div class="text-center">
        <input type="file" name="file" id="file" class="inputfile" accept=".pdf, .PDF|PDF Files/*"/>
        <label for="file" class="btn btn-secondary">
            <i class="fa fa-upload"></i>&nbsp;
            <span>Choose a PDF...</span>
        </label>
    </div>
    
    <div class="block mb-0">
        <div class="block-content text-center">
            <div class="btn-group push">
                <button type="button" class="btn btn-secondary draggable" id="sealImageBtn" style="z-index: 1;">
                    <i class="far fa-file-image"></i>
                    Seal Image
                </button>
                <button type="button" class="btn btn-secondary mr-5 draggable" id="sealTextBtn" style="z-index: 1;">
                    <i class="fa fa-font"></i>
                    Seal Text
                </button>
            </div>
            <div class="btn-group push">
            <button type="button" class="btn btn-primary" onclick="saveContent()">
                <i class="fa fa-save"></i>
                Save
            </button>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-around;" id="canvasContainer">
        <canvas id="canvasPane" width="900" height="900" style="border: 1px solid black;"></canvas>
    </div>
</div>

<script src="{{ asset('js/jquery.ui.min.js') }}"></script>
<script src="{{ asset('js/fabric.min.js') }}"></script>
@include('clientadmin.sealpos.script')
@endsection