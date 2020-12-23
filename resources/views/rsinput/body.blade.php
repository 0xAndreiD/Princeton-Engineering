@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1) ? 'clientadmin.layout' : 'user.layout'))

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
    </div>

    <div id="tab_first" class="rfdTabContent">
        @include('rsinput.first')
    </div>
    @for($i = 1; $i <= 10; $i ++)
        @php
            $conditionId = $i;    
        @endphp
    
    <div id="fc-{{ $conditionId }}" class="rfdTabContent">
        @include('rsinput.content')
    </div>
    @endfor
    <div id="tab_override" class="rfdTabContent">
        @include('rsinput.override')
    </div>
</div>

@include('rsinput.script_obfuscate')

@endsection