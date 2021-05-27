<!-- header section -->
<form id="inputform-first">
<!-- sample static color cell section -->
<table id="sample-static-color-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
    <colgroup>
        <col width="80">
        <col width="480">
        <col width="160">
        <col width="70">
        <col width="300">
        <col width="80">
    </colgroup>
<tbody>
    <tr class="h13">
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td><h6 style="margin: 0;">Click on the FC tab(s) above to enter framing condition data.</h6></td>
        <td class="iw400-left-bdr">Data input types</td>
        <td class="w400-yellow-bdr"><div style="overflow:hidden"></div></td>
        <td class="iw400" style="text-align:left !important;">Any user input accepted</td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td colspan="3"><div style="overflow:hidden"></div></td>
        <td class="w400-green-bdr"><div style="overflow:hidden"></div></td>
        <td class="iw400" style="text-align:left !important;">Restricted, pull down values only accepted</td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td colspan="3"><div style="overflow:hidden"></div></td>
        <td class="w400-blue-bdr" class="h13"><div style="overflow:hidden"></div></td>
        <td class="iw400" style="text-align:left !important;">Inactive cell</td>
        <td ><div style="overflow:hidden"></div></td>
    </tr>
</tbody> 
</table>

<!-- Project information table section -->
<table id="project-info-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
    <colgroup>
        <col width="80">
        <col width="160">
        <col width="200">
        <col width="300">
        <col width="800">
    </colgroup>
<tbody>
    <tr class="h13">
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
    </tr>
    @if ($projectId!=-1)
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Project Manager</td>
        <td class="w400-yellow-bdr">
            <select id="option-user-id" tabindex="6">
                <option value="">Select User</option>
                @foreach ($companyMembers as $item)
                <option value="{{$item->id}}" {{ $item->id == $userId ? 'selected' : ''}}>
                    {{ $item->username }}
                </option>
                @endforeach
            </select>
        </td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    @endif
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Project Number</td>
        <td class="w400-yellow-bdr"><input type="text" id="txt-project-number" tabindex="6" value="624"></input></td>
        <td class="iw400" id="project-id-comment" colspan="2" style="text-align: left;"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Project Name</td>
        <td class="w400-yellow-bdr"><input type="text" id="txt-project-name" tabindex="7" value="Tim Smith"></input></td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Street Address</td>
        <td class="w400-yellow-bdr"><input type="text" id="txt-street-address" tabindex="8" value="15 Maple Shade Lane"></input></td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">City</td>
        <td class="w400-yellow-bdr"><input type="text" id="txt-city" tabindex="9" value="Somerville"></input></td>
        <td class="iw400" id="txt-city-comment" colspan="2" style="text-align: left;">&nbsp;&nbsp;Good Massachusetts town name</td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">State</td>
        <td class="w400-green-bdr">
            <select id="option-state" tabindex="10">
            </select>
        </td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Zip</td>
        <td class="w400-yellow-bdr"><input type="text" id="txt-zip"  tabindex="11" value="12345"></input></td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
</tbody> 
</table>

<!-- Number Conditions table section -->
<table id="number-conditions-info-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
    <colgroup>
        <col width="80">
        <col width="160">
        <col width="160">
        <col width="160">
        <col width="80">
    </colgroup>
<tbody>
    <tr class="h13">
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr"># of Framing Conditions</td>
        <td class="w400-green-bdr">
            <select id="option-number-of-conditions" tabindex="12">
                <option data-value="n:1" selected="">1</option>
                <option data-value="n:2">2</option>
                <option data-value="n:3">3</option>
                <option data-value="n:4">4</option>
                <option data-value="n:5">5</option>
                <option data-value="n:6">6</option>
                <option data-value="n:7">7</option>
                <option data-value="n:8">8</option>
                <option data-value="n:9">9</option>
                <option data-value="n:10">10</option>
            </select>
        </td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
</tbody>
</table>

<!-- Personal Information table section -->
<table id="personal-info-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
    <colgroup>
        <col width="80">
        <col width="160">
        <col width="200">
        <col width="300">
        <col width="80">
    </colgroup>
<tbody>
    <tr class="h13">
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Name of Field Personnel</td>
        <td class="w400-yellow-bdr"><input type="text" id="txt-name-of-field-personnel" tabindex="13" value="Neraldo Doda"></input></td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Date of Field Visit</td>
        <td class="w400-yellow-bdr">
            <input type="date" id="date-of-field-visit" tabindex="14">
        </td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Date of Plan Set</td>
        <td class="w400-yellow-bdr">
            <input type="date" id="date-of-plan-set" tabindex="15">
        </td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
</tbody> 
</table>

<!-- Build Age table section -->
<table id="build-age-info-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
    <colgroup>
        <col width="80">
        <col width="160">
        <col width="70">
        <col width="300">
        <col width="80">
    </colgroup>
<tbody>
    <tr class="h13">
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Building Age</td>
        <td class="w400-yellow-bdr"><input type="text" id="txt-building-age" value="1910" tabindex="16"></input></td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
</tbody>
</table>

<!-- Equipment Section table section -->
<table id="equipment-info-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
    <colgroup>
        <col width="80">
        <col width="160">
        <col width="200">
        <col width="200">
        <col width="80">
        <col width="80">
        <col width="80">
        <col width="80">
        <col width="80">
        <col width="80">
    </colgroup>
<tbody>
    <tr class="h13">
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-bdr" colspan="6">Equipment section</td>
        <td class="iw400-bdr" colspan="4">Product Selection Filters</td>
    </tr>
    <tr class="h13">
        <td ><div style="overflow:hidden"></div></td>
        <td class="iw400-bdr">Description</td>
        <td class="iw400-bdr">Manufacturer</td>
        <td class="iw400-bdr">Model</td>
        <td class="iw400-bdr">Value</td>
        <td class="iw400-bdr">Unit</td>
        <td class="iw400-bdr">Quantity</td>
        <td class="iw400-bdr">Mixed</td>
        <td class="iw400-bdr">Standard</td>
        <td class="iw400-bdr">Favorite</td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">PV module</td>
        <td class="w400-green-bdr">
            <select id="option-module-type" tabindex="17">
            </select>
        </td>
        <td class="w400-green-bdr">
            <select id="option-module-subtype" tabindex="18">
            </select>
        </td>
        <td class="w400-bdr" id="option-module-option1"></td>
        <td class="w400-bdr" id="option-module-option2">watts</td>
        <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="option-module-quantity" tabindex="19" value="0"></td>
        <td class="text-center">
            <div class="custom-control custom-radio custom-control-dark">
                <input type="radio" class="custom-control-input" id="module-mixed" name="module-settings" onclick="updateModuleSetting(0)" checked>
                <label class="custom-control-label" for="module-mixed"></label>
            </div>
        </td>
        <td class="text-center">
            <div class="custom-control custom-radio custom-control-info">
                <input type="radio" class="custom-control-input" id="module-standard" name="module-settings" onclick="updateModuleSetting(1)">
                <label class="custom-control-label" for="module-standard"></label>
            </div>
        </td>
        <td class="text-center right-bdr">
            <div class="custom-control custom-radio custom-control-success">
                <input type="radio" class="custom-control-input" id="module-favorite" name="module-settings" onclick="updateModuleSetting(2)">
                <label class="custom-control-label" for="module-favorite"></label>
            </div>
        </td>
        <input class="w400-bdr" id="pv-module-length" hidden>
        <input class="w400-bdr" id="pv-module-width" hidden>
        <input class="w400-bdr" id="pv-module-custom" hidden>
        <input class="w400-bdr" id="pv-module-crc32" hidden>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">PV inverter</td>
        <td class="w400-green-bdr">
            <select id="option-inverter-type" tabindex="19">
            </select>
        </td>
        <td class="w400-green-bdr">
            <select id="option-inverter-subtype" tabindex="20">
            </select>
        </td>
        <td class="w400-bdr" id="option-inverter-option1"></td>
        <td class="w400-bdr" id="option-inverter-option2"></td>
        <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="option-inverter-quantity" tabindex="20" value="0"></td>
        <td class="text-center">
            <div class="custom-control custom-radio custom-control-dark">
                <input type="radio" class="custom-control-input" id="inverter-mixed" name="inverter-settings" onclick="updateInverterSetting(0)" checked>
                <label class="custom-control-label" for="inverter-mixed"></label>
            </div>
        </td>
        <td class="text-center">
            <div class="custom-control custom-radio custom-control-info">
                <input type="radio" class="custom-control-input" id="inverter-standard" name="inverter-settings" onclick="updateInverterSetting(1)">
                <label class="custom-control-label" for="inverter-standard"></label>
            </div>
        </td>
        <td class="text-center right-bdr">
            <div class="custom-control custom-radio custom-control-success">
                <input type="radio" class="custom-control-input" id="inverter-favorite" name="inverter-settings" onclick="updateInverterSetting(2)">
                <label class="custom-control-label" for="inverter-favorite"></label>
            </div>
        </td>
        <input class="w400-bdr" id="inverter-custom" hidden>
        <input class="w400-bdr" id="inverter-crc32" hidden>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Stanchion</td>
        <td class="w400-green-bdr">
            <select id="option-stanchion-type" tabindex="21">
            </select>
        </td>
        <td class="w400-green-bdr">
            <select id="option-stanchion-subtype" tabindex="22">
            </select>
        </td>
        <td class="w400-bdr" id="option-stanchion-option1"></td>
        <td class="w400-bdr" id="option-stanchion-option2"></td>
        <td><div style="overflow:hidden"></div></td>
        <td class="text-center">
            <div class="custom-control custom-radio custom-control-dark">
                <input type="radio" class="custom-control-input" id="stanchion-mixed" name="stanchion-settings" onclick="updateStanchionSetting(0)" checked>
                <label class="custom-control-label" for="stanchion-mixed"></label>
            </div>
        </td>
        <td class="text-center">
            <div class="custom-control custom-radio custom-control-info">
                <input type="radio" class="custom-control-input" id="stanchion-standard" name="stanchion-settings" onclick="updateStanchionSetting(1)">
                <label class="custom-control-label" for="stanchion-standard"></label>
            </div>
        </td>
        <td class="text-center right-bdr">
            <div class="custom-control custom-radio custom-control-success">
                <input type="radio" class="custom-control-input" id="stanchion-favorite" name="stanchion-settings" onclick="updateStanchionSetting(2)">
                <label class="custom-control-label" for="stanchion-favorite"></label>
            </div>
        </td>
        <input class="w400-bdr" id="stanchion-custom" hidden>
        <input class="w400-bdr" id="stanchion-crc32" hidden>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Rail Support System</td>
        <td class="w400-green-bdr">
            <select id="option-railsupport-type" tabindex="23">
            </select>
        </td>
        <td class="w400-green-bdr">
            <select id="option-railsupport-subtype" tabindex="24">
            </select>
        </td>
        <td class="w400-bdr" id="option-railsupport-option1"></td>
        <td class="w400-bdr" id="option-railsupport-option2"></td>
        <td class="bottom-bdr"><div style="overflow:hidden"></div></td>
        <td class="text-center bottom-bdr">
            <div class="custom-control custom-radio custom-control-dark">
                <input type="radio" class="custom-control-input" id="rail-mixed" name="rail-settings" onclick="updateRailSetting(0)" checked>
                <label class="custom-control-label" for="rail-mixed"></label>
            </div>
        </td>
        <td class="text-center bottom-bdr">
            <div class="custom-control custom-radio custom-control-info">
                <input type="radio" class="custom-control-input" id="rail-standard" name="rail-settings" onclick="updateRailSetting(1)">
                <label class="custom-control-label" for="rail-standard"></label>
            </div>
        </td>
        <td class="text-center right-bdr bottom-bdr">
            <div class="custom-control custom-radio custom-control-success">
                <input type="radio" class="custom-control-input" id="rail-favorite" name="rail-settings" onclick="updateRailSetting(2)">
                <label class="custom-control-label" for="rail-favorite"></label>
            </div>
        </td>
        <input class="w400-bdr" id="railsupport-custom" hidden>
        <input class="w400-bdr" id="railsupport-crc32" hidden>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td style="padding: 10px;">
            <input type="button" name="rs-save" id="rs-save" class="btn btn-hero-primary {{ $projectState > 2 ? 'disabled' : '' }}" tabindex="25" value="Save" style="width:100%;"></input>
        </td>
        <td style="padding: 10px;">
            <input type="button" name="rs-datacheck" id="rs-datacheck" class="btn btn-hero-primary {{ $projectState > 2 ? 'disabled' : '' }}" tabindex="26" value="Data Check" style="width:100%;"></input>
        </td>
        <td style="padding: 10px;">
            <input type="button" name="rs-submit" id="rs-submit" class="btn btn-hero-primary {{ $projectState != 3 ? 'disabled' : '' }}" tabindex="27" value="Resubmit" style="width:100%;">
        </td>
        @if($projectState > 3)
        <td style="padding: 10px;">
            <input type="button" name="rs-initialize" id="rs-initialize" class="btn btn-hero-primary" tabindex="28" value="Initialize" style="width:100%;"></input>
        </td>
        @else
        <td><div style="overflow:hidden"></div></td>
        @endif
    </tr>
</tbody>
</table>

<!-- Site Check section -->
<table id="site-check-table" cellspacing="0" cellpadding="0" style="border-spacing:0; display:none;" >    
    <colgroup>
        <col width="80">
        <col width="160">
        <col width="70">
        <col width="500">
    </colgroup>
<tbody>
    <tr class="h13">
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td colspan="3" style="text-align:center;"><h4 style="margin:0">Data Input Check</h4></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td colspan="3" style="text-align:center; color: red;" id="requiredNotes"> *************** Roof Framing Changes are Required *************** </td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-bdr" colspan="3">Site</td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Exposure Category</td>
        <td class="w400-bdr" rowspan="" id="exposureUnit">B</input></td>
        <td class="w400-bdr" style="text-align: left; font-style: italic;" id="exposureContent">Exposure Category (ASCE 7-16, Sect 26.7.3, pp 266)</td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr" >Occupancy Category</td>
        <td class="w400-bdr" rowspan="" id="occupancyUnit">II</input></td>
        <td class="w400-bdr" style="text-align: left; font-style: italic;" id="occupancyContent">Occupancy Category / Risk Category (ASCE 7-16, Table 1.5.1, pp 4)</td>
    </tr>
</tbody>
</table>

<!-- Code Check section -->
<table id="code-check-table" cellspacing="0" cellpadding="0" style="border-spacing:0; display:none;" >    
    <colgroup>
        <col width="80">
        <col width="160">
        <col width="140">
        <col width="160">
        <col width="140">
        <col width="130">
    </colgroup>
<tbody>
    <tr class="h13">
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-bdr" colspan="5">Code Check</td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">IBC</td>
        <td class="w400-bdr" id="IBC">2018</input></td>
        <td class="iw400-right-bdr">State Res Code</td>
        <td class="w400-bdr" colspan="2" id="stateCode"><div style="overflow:hidden">FBC 2020</div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">ASCE</td>
        <td class="w400-bdr" id="ASCE">ASCE 7-16</input></td>
        <td class="iw400-right-bdr">NEC</td>
        <td class="w400-bdr" id="NEC">2017</input></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
</tbody>
</table>

<!-- Environmental Check section -->
<table id="environment-check-table" cellspacing="0" cellpadding="0" style="border-spacing:0; display:none;" >    
    <colgroup>
        <col width="80">
        <col width="160">
        <col width="70">
        <col width="500">
    </colgroup>
<tbody>
    <tr class="h13">
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-bdr" colspan="3">Environmental</td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Wind Loading</td>
        <td class="w400-bdr" rowspan="" id="windLoadingValue">139</input></td>
        <td class="w400-bdr" style="text-align: center; font-style: italic;" id="windLoadingContent">mph - Orange County, FL Wind Speeds Risk Category II map</td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Snow Loading</td>
        <td class="w400-bdr" rowspan="" id="snowLoadingValue">0</input></td>
        <td class="w400-bdr" style="text-align: center; font-style: italic;" id="snowLoadingContent">psf - Ground Snow Load, 'pg' (ASCE 7-16 Table 7.2-1, Page 52-53)</td>
    </tr>
</tbody>
</table>

<!-- Electrical Check section -->
<table id="electric-check-table" cellspacing="0" cellpadding="0" style="border-spacing:0; display:none;" >    
    <colgroup>
        <col width="80">
        <col width="160">
        <col width="70">
        <col width="70">
    </colgroup>
<tbody>
    <tr class="h13">
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
        <td><div style="overflow:hidden"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-bdr" colspan="3">Electrical</td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Total DC Watts</td>
        <td class="w400-bdr" rowspan="" id="DCWatts">8160</input></td>
        <td class="w400-bdr" style="text-align: left;">w</td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Total Inverter Amperage</td>
        <td class="w400-bdr" rowspan="" id="InverterAmperage">24</input></td>
        <td class="w400-bdr" style="text-align: left;">A</td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Min OCPD rating</td>
        <td class="w400-bdr" rowspan="" id="OCPDRating">30</input></td>
        <td class="w400-bdr" style="text-align: left;">w</td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Recommended OCPD</td>
        <td class="w400-bdr" rowspan="" id="RecommendOCPD">30</input></td>
        <td class="w400-bdr" style="text-align: left;">A</td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Min Cu 75 deg C wire</td>
        <td class="w400-bdr" rowspan="" id="MinCu">#10</input></td>
        <td class="w400-bdr" style="text-align: left;">AWG</td>
    </tr>
</tbody>
</table>
<div>
    <!-- Error table -->
    <table cellspacing="0" cellpadding="0" style="border-spacing:0; display:inline-block; vertical-align: top;" >    
        <colgroup>
            <col width="80">
            <col width="160">
            <col width="200">
        </colgroup>
    <tbody>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" style="display: none;" id="collartie-header">
            <td><div style="overflow:hidden"></div></td>
            <td class="iw400-bdr" colspan="2">Required Framing Changes</td>
            <td><div style="overflow:hidden"></div></td>
        </tr>
        <tr class="h13" style="display: none;" id="collartie-headers">
            <td><div style="overflow:hidden"></div></td>
            <td class="iw400-bdr">Framing Condition # and Name</td>
            <td class="iw400-bdr">Collar Tie Height (ft)</td>
        </tr>
        @for($j = 1; $j <= 10; $j ++)
        <tr class="h13" style="display: none;" id="collartie-{{$j}}">
            <td><div style="overflow:hidden"></div></td>
            <td class="iw400-bdr" id="collartie-title-{{$j}}" style="color:red;">{{$j}}: MP{{$j}}</td>
            <td class="iw400-bdr" id="collartie-height-{{$j}}" style="color:red;">0.00</td>
        </tr>
        @endfor
    </tbody>
    </table>

    <table cellspacing="0" cellpadding="0" style="border-spacing:0; display:inline-block" >    
        <colgroup>
            <col width="30">
            <col width="400">
        </colgroup>
        <tbody>
            <tr class="h13">
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13" style="display:none" id="structural-notes">
                <td><div style="overflow:hidden"></div></td>
                <td class="iw400-bdr" id="structural-notes" style="align:left">
                    Rafters upon which stanchions will be placed, require collar ties or knee walls. Collar tie / knee wall locations either exist or will be added at the heights indicated herein. All new collar tiles shall be 2x6 material and shall be fastened at each end with (8) 12D common nails, (4) per side. If knee walls are to be used, stud framing to match rafter locations. Install top and bottom plate. All material to be 2x4 fastened w/10D common nails.
                </td>
            </tr>
        </tbody>
    </table>
</div>

<input type="text" value="{{ $projectId }}" id="projectId" hidden>
<input type="text" value="{{ $projectState }}" id="projectState" hidden>
<input type="text" value="{{ $offset }}" id="companyOffset" hidden>
</form>