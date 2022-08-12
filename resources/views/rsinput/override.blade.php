<div class="row">
<table id="section-info-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
    <colgroup>
        <col width="50">
        <col width="260">
        <col width="100">
        <col width="100">
        <col width="100">
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
            <td><h6 style="margin-bottom: 30px;">Custom Program Data Overrides</h6></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></div></td>
            <td class="iw400-bdr">Description</td>
            <td class="iw400-bdr">Value</td>
            <td class="iw400-bdr">Unit</td>
            <td class="iw400-bdr">Force Override</td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-right-bdr">Wind speed</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="wind-speed" value="0.0"></input></td>
            <td class="w400-bdr">mph</td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="wind-speed-override"></input></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-right-bdr">Ground Snow Load</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ground-snow" value="0.0"></input></td>
            <td class="w400-bdr">psf</td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="ground-snow-override"></input></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-right-bdr">IBC Years</td>
            <td class="w400-green-bdr">
                <select id="ibc-year">
                    @foreach($ibcOverrides as $ibc)
                        <option data-value="{{ $ibc->value }}" >{{ $ibc->value }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-right-bdr">ASCE 7- Years</td>
            <td class="w400-green-bdr">
                <select id="asce-year">
                    @foreach($asceOverrides as $asce)
                        <option data-value="{{ $asce->value }}" >{{ $asce->value }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-right-bdr">NEC Years</td>
            <td class="w400-green-bdr">
                <select id="nec-year">
                    @foreach($necOverrides as $nec)
                        <option data-value="{{ $nec->value }}" >{{ $nec->value }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-right-bdr">Wind Exposure Category</td>
            <td class="w400-green-bdr">
                <select id="wind-exposure">
                    @foreach($exposureOverrides as $exposure)
                        <option data-value="{{ $exposure->value }}" >{{ $exposure->value }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-right-bdr">Units</td>
            <td class="w400-green-bdr">
                <select id="override-unit">
                    <option data-value="Imperial" selected="">Imperial</option>
                    <option data-value="SI">SI</option>
                </select>
            </td>
        </tr>
    </tbody>
</table>
</div>
