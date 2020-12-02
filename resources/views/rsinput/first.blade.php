<!-- header section -->
<form id="inputform-first">
<!-- <table id="rs02_header" cellspacing="0" cellpadding="0" style="border-spacing:0;">
    <colgroup>
        <col width="80">
        <col width="600">
        <col width="80">
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
            <td><div style="overflow:hidden"></td>
            <td class="w700" ><div style="overflow:hidden"></td>
            <td ><div style="overflow:hidden"></td>
            <td class="iw400" >Multiple Framing Data Input</td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h16"> 
            <td><div style="overflow:hidden"></td>
            <td class="w700" style="font-size: 20px;">iRoof™ Structural Analysis Data Input Pages</td>
            <td ><div style="overflow:hidden"></td>
            <td class="iw400">Rev 2.0.3</td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h16"> 
            <td><div style="overflow:hidden"></td>
            <td class="w700" >Common Data Entry Page</td>
            <td ><div style="overflow:hidden"></td>
            <td class="iw400"><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr style="height:52pt;">
            <td>&nbsp;</td>
            <td class="iw400-bdr" style="height:52pt;" colspan="3">
                Copyright © 2020 Richard Pantel.
All Rights Reserved<span>&nbsp; </span>No parts of this
data input form or related calculation reports may be copied in format,
content or intent, or reproduced in any form or by any electronic or mechanical
means, including information storage and retrieval systems, without
permission in writing from the author.<span>&nbsp;
</span>Further, dis-assembly or reverse engineering of this data input form
or related calculation reports is strictly prohibited. The author's contact
information is: RPantel@Princeton-Engineering.com, web-site:
www.Princeton-Engineering.com; tel: 908-507-5500
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400" colspan="2">This form is for inputing multiple framing data, 
                <font class="w700">NOT</font><font class="w400"> for trussed roofs</font>
            </td>
            <td><div style="overflow:hidden"></div></td>
        </tr>
    </tbody>
</table> -->

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

<!-- company information table section -->
<!-- <table id="company-info-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
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
        <td class="iw400-right-bdr">Company Name</td>
        <td class="w400-left-bdr"><input type="text" id="txt-company-name" tabindex="1" value="{{ $companyName }}" disabled></input></td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Company Number</td>
        <td class="w400-left-bdr"><input type="text" id="txt-company-number" tabindex="2" value="{{ $companyNumber }}" disabled></input></td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">User ID</td>
        <td class="w400-yellow-bdr">
            <select id="option-user-id" tabindex="3" disabled>
                @foreach ($companyMembers as $member)
                    @if ($member['id'] == Auth::user()->id)
                        <option data-companyid="{{ $member['usernumber'] }}" data-userid="{{ $member['id'] }}" selected>
                            {{ Auth::user()->companyid. '.' . $member['usernumber'] }}
                        </option>
                    @else
                        <option data-companyid="{{ $member['usernumber'] }}" data-userid="{{ $member['id'] }}">
                            {{ Auth::user()->companyid. '.' . $member['usernumber'] }}
                        </option>
                    @endif
                @endforeach
            </select>
        </td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">User Name</td>
        <td class="w400-left-bdr"><input type="text" id="txt-user-name" tabindex="4" value="{{ Auth::user()->username }}" disabled></input></td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">User Email</td>
        <td class="w400-left-bdr"><input type="text" id="txt-user-email" tabindex="5" value="{{ Auth::user()->email }}" disabled></input></td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
</tbody> 
</table> -->

<!-- Project information table section -->
<table id="project-info-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
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
        <td class="iw400-right-bdr">Project Number</td>
        <td class="w400-yellow-bdr"><input type="text" id="txt-project-number" tabindex="6" value="624"></input></td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
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
        <td class="iw400" id="txt-city-comment" colspan="2" style="text-align: left;">Good Massachusetts town name</td>
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
            <select id="option-number-of-conditions" tabindex="24">
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
        <td class="w400-yellow-bdr"><input type="text" id="txt-name-of-field-personnel" tabindex="12" value="Neraldo Doda"></input></td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Date of Field Visit</td>
        <td class="w400-yellow-bdr">
            <input type="date" id="date-of-field-visit" tabindex="13">
        </td>
        <td><div style="overflow:hidden"></div></td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Date of Plan Set</td>
        <td class="w400-yellow-bdr">
            <input type="date" id="date-of-plan-set" tabindex="14">
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
        <td class="w400-yellow-bdr"><input type="text" id="txt-building-age" value="1910" tabindex="15"></input></td>
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
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-bdr" colspan="5">Equipment section</td>
        <td><div style="overflow:hidden"></div></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">PV module</td>
        <td class="w400-green-bdr">
            <select id="option-module-type" tabindex="16">
            </select>
        </td>
        <td class="w400-green-bdr">
            <select id="option-module-subtype" tabindex="17">
            </select>
        </td>
        <td class="w400-bdr" id="option-module-option1"></td>
        <td class="w400-bdr" id="option-module-option2"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">PV inverter</td>
        <td class="w400-green-bdr">
            <select id="option-inverter-type" tabindex="18">
            </select>
        </td>
        <td class="w400-green-bdr">
            <select id="option-inverter-subtype" tabindex="19">
            </select>
        </td>
        <td class="w400-bdr" id="option-inverter-option1"></td>
        <td class="w400-bdr" id="option-inverter-option2"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Stanchion</td>
        <td class="w400-green-bdr">
            <select id="option-stanchion-type" tabindex="20">
            </select>
        </td>
        <td class="w400-green-bdr">
            <select id="option-stanchion-subtype" tabindex="21">
            </select>
        </td>
        <td class="w400-bdr" id="option-stanchion-option1"></td>
        <td class="w400-bdr" id="option-stanchion-option2"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td class="iw400-right-bdr">Rail Support System</td>
        <td class="w400-green-bdr">
            <select id="option-railsupport-type" tabindex="22">
            </select>
        </td>
        <td class="w400-green-bdr">
            <select id="option-railsupport-subtype" tabindex="23">
            </select>
        </td>
        <td class="w400-bdr" id="option-railsupport-option1"></td>
        <td class="w400-bdr" id="option-railsupport-option2"></td>
    </tr>
    <tr class="h13">
        <td><div style="overflow:hidden"></div></td>
        <td style="padding: 10px;">
            <input type="button" name="rs-save" id="rs-save" class="btn btn-hero-primary" tabindex="190" value="Save" style="width:100%;"></input>
        </td>
        <td style="padding: 10px;">
            <input type="button" name="rs-datacheck" id="rs-datacheck" class="btn btn-hero-primary" tabindex="191" value="Data Check" style="width:100%;"></input>
        </td>
        <td style="padding: 10px;">
            <input type="button" name="rs-submit" id="rs-submit" class="btn btn-hero-primary" tabindex="192" value="Submit" style="width:100%;">
        </td>
        <td><div style="overflow:hidden"></td>
    </tr>
</tbody>
</table>

</form>