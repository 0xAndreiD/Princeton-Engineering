@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1) ? 'clientadmin.layout' : 'user.layout'))

@section('content')
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    iRoof™ Structural Analysis Data Reference
                </h1>
                <h2 class="h5 text-white-75 mb-0" id="subPageTitle">
                    Site and Equipment Data Reference
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="rfdContainer">
    <div class="rfdTab">
        <!-- <button class="tablinks" onclick="openRfdTab(event, 'tab_first')" id="defaultOpen">Common Data Entry</button> -->
        <button class="tablinks" onclick="openTab(event, 'framing-graphic')" style="display: block;" id="framing-tab">Framing Graphic</button>
        <button class="tablinks" onclick="openTab(event, 'truss-graphic')" style="display: block;" id="truss-tab">Truss Graphic</button>
    </div>

    <div id="framing-graphic" class="pdfTabContent">
        <iframe src="{{ asset('pdf/Princeton Input Drawing 20210305-Standard.pdf') }}" type='application/pdf' frameborder="0" class="pdfViewer"></iframe>
    </div>

    <div id="truss-graphic" class="pdfTabContent">
        <iframe src="{{ asset('pdf/Princeton Input Drawing 20210305-Truss.pdf') }}" type='application/pdf' frameborder="0" class="pdfViewer"></iframe>
    </div>
    
</div>

@include('reference.script')
@endsection