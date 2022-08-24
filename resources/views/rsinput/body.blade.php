@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : (Auth::user()->userrole == 4 ? 'reviewer.layout' : ((Auth::user()->userrole == 6) ? 'consultant.layout' : 'user.layout'))))

@section('content')
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    iRoof™ Structural Analysis Data Input Pages
                </h1>
                <h2 class="h5 text-white-75 mb-0" id="subPageTitle">
                    Site and Equipment Data Input
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="rfdContainer">
    <div class="rfdTab">
        <button class="tablinks" onclick="openRfdTab(event, 'tab_first')" id="defaultOpen">Common Data Entry</button>
        @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $AutoCAD == 1)
        <button class="tablinks" onclick="openRfdTab(event, 'electric')" style="display: block;" id="fcTab-1">Electric</button>
        @endif
        <button class="tablinks" onclick="openRfdTab(event, 'fc-1')" style="display: block;" id="fcTab-1">FC 1</button>
        <button class="tablinks" onclick="openRfdTab(event, 'fc-2')" style="display: none;" id="fcTab-2">FC 2</button>
        <button class="tablinks" onclick="openRfdTab(event, 'fc-3')" style="display: none;" id="fcTab-3">FC 3</button>
        <button class="tablinks" onclick="openRfdTab(event, 'fc-4')" style="display: none;" id="fcTab-4">FC 4</button>
        <button class="tablinks" onclick="openRfdTab(event, 'fc-5')" style="display: none;" id="fcTab-5">FC 5</button>
        <button class="tablinks" onclick="openRfdTab(event, 'fc-6')" style="display: none;" id="fcTab-6">FC 6</button>
        <button class="tablinks" onclick="openRfdTab(event, 'fc-7')" style="display: none;" id="fcTab-7">FC 7</button>
        <button class="tablinks" onclick="openRfdTab(event, 'fc-8')" style="display: none;" id="fcTab-8">FC 8</button>
        <button class="tablinks" onclick="openRfdTab(event, 'fc-9')" style="display: none;" id="fcTab-9">FC 9</button>
        <button class="tablinks" onclick="openRfdTab(event, 'fc-10')" style="display: none;" id="fcTab-10">FC 10</button>
        <button class="tablinks" onclick="openRfdTab(event, 'tab_override')" id="overrideTab">Overrides</button>
        @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->allow_permit > 0)
        <button class="tablinks" onclick="openRfdTab(event, 'tab_permit')" id="permitTab">Permit</button>
        <button class="tablinks" onclick="openRfdTab(event, 'tab_PIL')" id="pilTab">PIL</button>
        @endif
        <button class="tablinks" onclick="openRfdTab(event, 'tab_upload')" id="uploadTab">Files</button>
    </div>


    <div id="tab_first" class="rfdTabContent domChange">
        @include('rsinput.first')
    </div>
    @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || $AutoCAD == 1)
    <div id="electric" class="rfdTabContent domChange">
        @include('rsinput.electric')
    </div>
    @endif
    @for($i = 1; $i <= 10; $i ++)
        @php
            $conditionId = $i;    
        @endphp
    
    <div id="fc-{{ $conditionId }}" class="rfdTabContent domChange">
        @include('rsinput.content')
    </div>
    @endfor
    <div id="tab_override" class="rfdTabContent domChange">
        @include('rsinput.override')
    </div>
    @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->allow_permit > 0)
        <div id="tab_permit" class="rfdTabContent">
            @include('rsinput.permit')
        </div>
        <div id="tab_PIL" class="rfdTabContent">
            @include('rsinput.pil')
        </div>
    @endif
    <!-- <div id="ucc_f100" class="pdfForm">
        {{-- @include('rsinput.ucc_f100_cpa') --}}
    </div>
    <div id="ucc_f110" class="pdfForm">
        {{-- @include('rsinput.ucc_f110_bldg') --}}
    </div>
    <div id="ucc_f120" class="pdfForm">
        {{-- @include('rsinput.ucc_f120_elec') --}}
    </div>
    <div id="pa_ucc3" class="pdfForm">
        {{-- @include('rsinput.pa_ucc3') --}}
    </div>
    <div id="ucc_f140" class="pdfForm">
        {{-- @include('rsinput.ucc_f140_fire') --}}
    </div> -->

    <div id="tab_upload" class="rfdTabContent">
        @include('rsinput.upload')
    </div>

</div>

@include('rsinput.ceccheck')

<script src="{{ asset('/js/plugins/jquery.knob.js') }}"></script>
<script src="{{ asset('/js/plugins/jquery.ui.widget.js') }}"></script>
<script src="{{ asset('/js/plugins/jquery.iframe-transport.js') }}"></script>
<script src="{{ asset('/js/plugins/jquery.fileupload.js') }}"></script>
<script src="{{ asset('/js/plugins/jstree/jstree.min.js') }}"></script>
<script src="{{ asset('/js/plugins/minipdf.js') }}"></script>
<script src="{{ asset('/js/plugins/pako.min.js') }}"></script>
<script src="{{ asset('/js/plugins/pdfform.js') }}"></script>
<script src="{{ asset('/js/pdf-lib.min.js') }}"></script>

@include('rsinput.script_obfuscate')

@endsection