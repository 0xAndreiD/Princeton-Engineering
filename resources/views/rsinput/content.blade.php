<form id="inputform-{{ $conditionId }}">
<!-- Section info table section -->
<div class="form-group rfdTypePane">
    <div class="row">
        <div class="ml-1">
            <div class="custom-control custom-radio custom-control-success mb-1">
                <input type="radio" class="custom-control-input rfdTypeSelect" id="trussFlagOption-{{ $conditionId }}-1" name="trussFlag-{{ $conditionId }}" onchange="fcChangeType({{ $conditionId }}, 0)" checked value="0">
                <label class="custom-control-label rfdTypeSelect" for="trussFlagOption-{{ $conditionId }}-1">Stick Framing Data Input</label>
            </div>
            <div class="custom-control custom-radio custom-control-success mb-1 ">
                <input type="radio" class="custom-control-input rfdTypeSelect" id="trussFlagOption-{{ $conditionId }}-2" name="trussFlag-{{ $conditionId }}" onchange="fcChangeType({{ $conditionId }}, 1)" value="1">
                <label class="custom-control-label rfdTypeSelect" for="trussFlagOption-{{ $conditionId }}-2">Truss Framing Data Input</label>
            </div>
        </div>
        <div class="ml-4">
            <div class="custom-control custom-radio custom-control-success mb-1 ">
                <input type="radio" class="custom-control-input rfdTypeSelect" id="trussFlagOption-{{ $conditionId }}-3" name="trussFlag-{{ $conditionId }}" onchange="fcChangeType({{ $conditionId }}, 2)" value="2">
                <label class="custom-control-label rfdTypeSelect" for="trussFlagOption-{{ $conditionId }}-3">IBC 5% Data Input - Only Areas with No Snow</label>
            </div>
            <div class="custom-control custom-radio custom-control-success mb-1 GroundMount-Option" style="display: none;">
                <input type="radio" class="custom-control-input rfdTypeSelect" id="trussFlagOption-{{ $conditionId }}-4" name="trussFlag-{{ $conditionId }}" onchange="fcChangeType({{ $conditionId }}, 3)" value="3">
                <label class="custom-control-label rfdTypeSelect" for="trussFlagOption-{{ $conditionId }}-4">Ground Mount</label>
            </div>
        </div>
    </div>
    @if(Auth::user()->userrole != 4)
    <div>
        <button type="button" class="btn btn-info mr-1" id="duplicate-{{ $conditionId }}"><i class="far fa-clone mr-1"></i>Duplicate</button>
        <button type="button" class="btn btn-danger mr-5" id="delete-{{ $conditionId }}"><i class="fa fa-trash mr-1"></i>Delete</button>
    </div>
    @endif
</div>
<p class="txt-collar-warning" id="collartie-warning-{{ $conditionId }}">Framing modification required.  Add collar tie / knee wall at xx.yy ft.</p>
<div class="row">
    <!-- Section info table section -->
    <table id="section-info-table-{{ $conditionId }}" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
        <colgroup>
            <col width="80">
            <col width="160">
            <col width="70">
            <col width="80">
            <col width="300">
            <col width="70">
            <col width="70">
            <col width="70">
            <col width="70">
            <col width="250">
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
            <td class="iw400-bdr">Section</td>
            <td class="iw400-bdr">Item</td>
            <td class="iw400-bdr" colspan="2">Description</td>
            <td class="iw400-bdr">Units</td>
            <td class="iw400-bdr" colspan="2">Data Entry</td>
            <td class="iw400-bdr">Dec. Feet</td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle" rowspan="12" id="label-A-1-{{ $conditionId }}">Roof Data Input</td>
            <td class="w400-bdr">A-1</td>
            <td class="iw400-right-bdr" colspan="2">Framing Condition Number</td>
            <td class="iw400-bdr"></td>
            <td class="w400-green-bdr" colspan="2">{{ $conditionId }}</td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-2</td>
            <td class="iw400-right-bdr" colspan="2" id="label-A-2-{{ $conditionId }}">Roof Average Height</td>
            <td class="iw400-bdr">ft | in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="af-2-{{ $conditionId }}" tabindex="26" value="30.00"></input></td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ai-2-{{ $conditionId }}" tabindex="26" value="0.00"></input></td>
            <td class="td-dec-feet right-bdr"><input type="text" class="txt-calced" id="a-2-{{ $conditionId }}" tabindex="-1" value="30.00" readonly></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-3</td>
            <td class="iw400-right-bdr" colspan="2" id="label-A-3-{{ $conditionId }}">Plan View Length of Building Section</td>
            <td class="iw400-bdr">ft | in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="af-3-{{ $conditionId }}"  tabindex="27" value="31.17"></input></td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ai-3-{{ $conditionId }}"  tabindex="27" value="0.00"></input></td>
            <td class="td-dec-feet right-bdr"><input type="text" class="txt-calced" id="a-3-{{ $conditionId }}"  tabindex="-1" value="31.17" readonly></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-4</td>
            <td class="iw400-right-bdr" colspan="2" id="label-A-4-{{ $conditionId }}">Plan View Width of Building Section</td>
            <td class="iw400-bdr">ft | in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="af-4-{{ $conditionId }}"  tabindex="28" value="14.25"></input></td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ai-4-{{ $conditionId }}"  tabindex="28" value="0.00"></input></td>
            <td class="td-dec-feet right-bdr"><input type="text" class="txt-calced" id="a-4-{{ $conditionId }}"  tabindex="-1" value="14.25" readonly></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-5</td>
            <td class="iw400-right-bdr" colspan="2">Name of Array Section</td>
            <td class="iw400-bdr">text</td>
            <td class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="a-5-{{ $conditionId }}"  tabindex="29" value="MP1"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-IBC-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-6</td>
            <td class="iw400-right-bdr" colspan="2">Orientation</td>
            <td class="iw400-bdr">select</td>
            <td class="w400-green-bdr" colspan="2">
                <select id="a-6-{{ $conditionId }}" tabindex="30">
                    <option data-value="Landscape" selected="">Landscape</option>
                    <option data-value="Portrait">Portrait</option>
                </select>
            </td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide" id="tr-7-{{ $conditionId }}">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-7</td>
            <td class="iw400-bdr" style="vertical-align : middle;" rowspan="4">Enter any two values</td>
            <td class="iw400-right-bdr" id="label-A-7-{{ $conditionId }}">Roof Slope</td>
            <td class="iw400-bdr">deg</td>
            <td id="value-7-{{ $conditionId }}" class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="a-7-{{ $conditionId }}" tabindex="31" value=""></input></td>
            <td id="calced-7-{{ $conditionId }}" class="calcedCell right-bdr">
                <p style="margin-bottom:0; color:red; font-weight:500; text-align:left; display:none" id="warning-stick-roof-degree-{{ $conditionId }}">Zero is invalid</p>
            </td>
            
            <input type="text" class="txt-center-align" id="ac-7-{{ $conditionId }}" value="" hidden>
            <!-- <td class="w400-bdr"><input type="checkbox" id="aa-7-1" checked></input></td> -->
        </tr>
        <tr class="h13 class-truss-hide" id="tr-8-{{ $conditionId }}">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-8</td>
            <td class="iw400-right-bdr" id="label-A-8-{{ $conditionId }}">Diagonal Rafter Length from Plate to Ridge</td>
            <td class="iw400-bdr">ft | in</td>
            <td id="valuef-8-{{ $conditionId }}" class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="af-8-{{ $conditionId }}"  tabindex="32" value=""></input></td>
            <td id="valuei-8-{{ $conditionId }}" class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ai-8-{{ $conditionId }}"  tabindex="32" value=""></input></td>
            <td id="value-8-{{ $conditionId }}" class="td-dec-feet right-bdr"><input type="text" class="txt-calced" id="a-8-{{ $conditionId }}"  tabindex="-1" value="" readonly></input></td>
            <td id="calced-8-{{ $conditionId }}" class="calcedCell "></td>
            <input type="text" class="txt-center-align" id="ac-8-{{ $conditionId }}" value="" hidden>
            <!-- <td class="w400-bdr" id="tc-8-1" style="pointer-events: none;"><input type="text" class="txt-center-align" id="ac-8-1"></td>
            <td class="w400-bdr"><input type="checkbox" id="aa-8-1"></input></td> -->
        </tr>
        <tr class="h13 class-truss-hide" id="tr-9-{{ $conditionId }}">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-9</td>
            <td class="iw400-right-bdr" id="label-A-9-{{ $conditionId }}">Rise from Rafter Plate to Top Ridge</td>
            <td class="iw400-bdr">ft | in</td>
            <td id="valuef-9-{{ $conditionId }}" class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="af-9-{{ $conditionId }}"  tabindex="33" value=""></input></td>
            <td id="valuei-9-{{ $conditionId }}" class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ai-9-{{ $conditionId }}"  tabindex="33" value=""></input></td>
            <td id="value-9-{{ $conditionId }}" class="td-dec-feet right-bdr"><input type="text" class="txt-calced" id="a-9-{{ $conditionId }}"  tabindex="-1" value="" readonly></input></td>
            <td id="calced-9-{{ $conditionId }}" class="calcedCell"></td>
            <input type="text" class="txt-center-align" id="ac-9-{{ $conditionId }}" value="" hidden>
            <!-- <td class="w400-bdr" id="tc-9-1" style="pointer-events: none;"><input type="text" class="txt-center-align" id="ac-9-1"></td>
            <td class="w400-bdr"><input type="checkbox" id="aa-9-1" checked></input></td> -->
        </tr>
        <tr class="h13 class-truss-hide" id="tr-10-{{ $conditionId }}">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-10</td>
            <td class="iw400-right-bdr" id="label-A-10-{{ $conditionId }}" >Horiz Len from Outside of Rafter Plate to Ridge</td>
            <td class="iw400-bdr">ft | in</td>
            <td id="valuef-10-{{ $conditionId }}" class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="af-10-{{ $conditionId }}"  tabindex="34" value=""></input></td>
            <td id="valuei-10-{{ $conditionId }}" class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ai-10-{{ $conditionId }}"  tabindex="34" value=""></input></td>
            <td id="value-10-{{ $conditionId }}" class="td-dec-feet right-bdr"><input type="text" class="txt-calced" id="a-10-{{ $conditionId }}"  tabindex="-1" value="" readonly></input></td>
            <td id="calced-10-{{ $conditionId }}" class="calcedCell"></td>
            <input type="text" class="txt-center-align" id="ac-10-{{ $conditionId }}" value="" hidden>
            <input type="text" class="txt-center-align" id="calc-algorithm-{{ $conditionId }}" value="" hidden>
            <!-- <td class="w400-bdr" id="tc-10-1" style="pointer-events: none; display: table-cell !important;"><input type="text" class="txt-center-align" id="ac-10-1"></td>
            <td class="w400-bdr"><input type="checkbox" id="aa-10-1"></input></td> -->
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-11</td>
            <td class="iw400-right-bdr" id="label-A-11-{{ $conditionId }}" colspan="2">Diagonal Overhang Length past Rafter Plate</td>
            <td class="iw400-bdr">ft | in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="af-11-{{ $conditionId }}"  tabindex="35" value="0.83"></input></td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ai-11-{{ $conditionId }}"  tabindex="35" value="0.00"></input></td>
            <td class="td-dec-feet right-bdr"><input type="text" class="txt-calced right-bdr" id="a-11-{{ $conditionId }}"  tabindex="-1" value="0.83" readonly></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-IBC-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-12</td>
            <td class="iw400-right-bdr" colspan="2">Roof Shape</td>
            <td class="iw400-bdr">select</td>
            <td class="w400-green-bdr" colspan="2">
                <select id="a-12-{{ $conditionId }}" tabindex="30">
                </select>
            </td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;" rowspan="5" id="label-B-1-{{ $conditionId }}">Rafter Data Input</td>
            <td class="w400-bdr">B-1</td>
            <td class="iw400-right-bdr" colspan="2">Rafter width **</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="b-1-{{ $conditionId }}"  tabindex="36" value="1.50"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">B-2</td>
            <td class="iw400-right-bdr" colspan="2">Rafter Height **</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="b-2-{{ $conditionId }}"  tabindex="37" value="5.50"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle; display: none;" rowspan="3" id="title-B-3-{{ $conditionId }}">Truss Data Input</td>
            <td class="w400-bdr">B-3</td>
            <td class="iw400-right-bdr" id="label-B-3-{{ $conditionId }}" colspan="2">Joist Spacing - Center to Center</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="b-3-{{ $conditionId }}"  tabindex="38" value="16.00"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-IBC-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">B-4</td>
            <td class="iw400-right-bdr" id="label-B-4-{{ $conditionId }}" colspan="2">Rafter Material</td>
            <td class="iw400-bdr">select</td>
            <td class="w400-green-bdr" colspan="2">
                <select id="b-4-{{ $conditionId }}">
                    <option data-value="Douglas-fir" selected="">Douglas-fir</option>
                    <option data-value="Hemlock">Hemlock</option>
                    <option data-value="Pine">Pine</option>
                    <option data-value="Spruce">Spruce</option>
                </select>
            </td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-IBC-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">B-5</td>
            <td class="iw400-right-bdr" colspan="2">Max stanchion spacing</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="b-5-{{ $conditionId }}"  tabindex="38" value="48.00"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide class-IBC-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;" rowspan="4">Collar Tie / Knee Wall Information</td>
            <td class="w400-bdr">C-1</td>
            <td class="iw400-right-bdr" colspan="2">Collar Tie Description</td>
            <td class="iw400-bdr">text</td>
            <td class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="c-1-{{ $conditionId }}"  tabindex="39" value=""></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide class-IBC-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">C-2</td>
            <td class="iw400-right-bdr" colspan="2">Dist. from Top of Collar Tie to Attic Deck</td>
            <td class="iw400-bdr">ft | in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="cf-2-{{ $conditionId }}"  tabindex="40" value=""></input></td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ci-2-{{ $conditionId }}"  tabindex="40" value=""></input></td>
            <td class="td-dec-feet right-bdr"><input type="text" class="txt-calced" id="c-2-{{ $conditionId }}"  tabindex="-1" value="" readonly></input></td>
            <td><div id="c-2-warn-{{ $conditionId }}" class="warnCell">Warning - Height above high end of rafter</td>
        </tr>
        <tr class="h13 class-truss-hide class-IBC-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">C-3</td>
            <td class="iw400-right-bdr" colspan="2">Tie Spacing - Center to Center</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="c-3-{{ $conditionId }}"  tabindex="41" value=""></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide class-IBC-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">C-4</td>
            <td class="iw400-right-bdr" colspan="2">Knee Wall Height</td>
            <td class="iw400-bdr">ft | in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="cf-4-{{ $conditionId }}"  tabindex="41" value=""></input></td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ci-4-{{ $conditionId }}"  tabindex="41" value=""></input></td>
            <td class="td-dec-feet right-bdr"><input type="text" class="txt-calced" id="c-4-{{ $conditionId }}"  tabindex="-1" value="" readonly></input></td>
            <td><div id="c-4-warn-{{ $conditionId }}" class="warnCell">Warning - Height above high end of rafter</td>
        </tr>
        <tr class="h13 class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;" rowspan="3">Roof Deck and Surface</td>
            <td class="w400-bdr">D-1</td>
            <td class="iw400-right-bdr" colspan="2">Plywood Thickness</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="d-1-{{ $conditionId }}"  tabindex="42" value="0.50"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">D-2</td>
            <td class="iw400-right-bdr" colspan="2">Shingle Type</td>
            <td class="iw400-bdr">select</td>
            <td class="w400-green-bdr" colspan="2">
                <select id="d-2-{{ $conditionId }}" tabindex="43">
                    <option data-value="Standard" selected="">Standard</option>
                    <option data-value="Architecture">Architectural - Heavy</option>
                    <option data-value="Metal Deck">Metal Deck</option>
                    <option data-value="Roof Tile">Roof Tile</option>
                </select>
            </td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">D-3</td>
            <td class="iw400-right-bdr" colspan="2"># Shingle Layers</td>
            <td class="iw400-bdr">#</td>
            <td class="w400-yellow-bdr" colspan="2">
                <select id="d-3-{{ $conditionId }}" tabindex="44">
                    <option data-value="1" selected="">1</option>
                    <option data-value="2">2</option>
                    <option data-value="3">3</option>
                </select>
            </td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-IBC-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;" rowspan="2">Location</td>
            <td class="w400-bdr">E-1</td>
            <td class="iw400-right-bdr" colspan="2">Uphill Distance from Eave to Low Edge of Module</td>
            <td class="iw400-bdr">ft | in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ef-1-{{ $conditionId }}" tabindex="45" value="4.25"></input></td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ei-1-{{ $conditionId }}" tabindex="45" value="0.00"></input></td>
            <td class="td-dec-feet right-bdr"><input type="text" class="txt-calced" id="e-1-{{ $conditionId }}" tabindex="-1" value="4.25" readonly></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-IBC-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">E-2</td>
            <td class="iw400-right-bdr" colspan="2">Uphill Distance from Eave to Lowest Support</td>
            <td class="iw400-bdr">ft | in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ef-2-{{ $conditionId }}" tabindex="46" value="4.25"></input></td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="ei-2-{{ $conditionId }}" tabindex="46" value="0.00"></input></td>
            <td class="td-dec-feet right-bdr"><input type="text" class="txt-calced" id="e-2-{{ $conditionId }}" tabindex="-1" value="4.25" readonly></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr">Number of Modules</td>
            <td class="w400-bdr">F-1</td>
            <td class="iw400-right-bdr" id="label-F-1-{{ $conditionId }}" colspan="2">Maximum # Modules along Rafter</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2" id="input-f-1-{{ $conditionId }}">
                <select id="f-1-{{ $conditionId }}" tabindex="47" onchange="maxModuleNumChange({{ $conditionId }})">
                    <option data-value="1">1</option>
                    <option data-value="2" selected="">2</option>
                    <option data-value="3">3</option>
                    <option data-value="4">4</option>
                    <option data-value="5">5</option>
                    <option data-value="6">6</option>
                    <option data-value="7">7</option>
                    <option data-value="8">8</option>
                    <option data-value="9">9</option>
                    <option data-value="10">10</option>
                    <option data-value="11">11</option>
                    <option data-value="12">12</option>
                </select>
            </td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-IBC-hide">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" id="label-G-1-{{ $conditionId }}" rowspan="2">Module Geometry</td>
            <td class="w400-bdr">G-1</td>
            <td class="iw400-right-bdr" colspan="2">Uphill Gap Between Modules</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="g-1-{{ $conditionId }}" tabindex="48" value="1"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-IBC-hide class-GroundMount-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">G-2</td>
            <td class="iw400-right-bdr" colspan="2">Module relative tilt</td>
            <td class="iw400-bdr">deg</td>
            <td class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="g-2-{{ $conditionId }}" tabindex="48" value="0.00"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-1-{{ $conditionId }}">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;" rowspan="2" id="Module-Left-Text-{{ $conditionId }}">Rotate Module Orientation</td>
            <td class="w400-bdr">H-1</td>
            <td class="iw400-right-bdr" colspan="2">Module 1</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-1-{{ $conditionId }}" tabindex="49"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-2-{{ $conditionId }}">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-2</td>
            <td class="iw400-right-bdr" colspan="2">Module 2</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-2-{{ $conditionId }}" tabindex="50"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-3-{{ $conditionId }}" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-3</td>
            <td class="iw400-right-bdr" colspan="2">Module 3</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-3-{{ $conditionId }}" tabindex="51"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-4-{{ $conditionId }}" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-4</td>
            <td class="iw400-right-bdr" colspan="2">Module 4</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-4-{{ $conditionId }}" tabindex="52"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-5-{{ $conditionId }}" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-5</td>
            <td class="iw400-right-bdr" colspan="2">Module 5</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-5-{{ $conditionId }}" tabindex="53"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-6-{{ $conditionId }}" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-6</td>
            <td class="iw400-right-bdr" colspan="2">Module 6</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-6-{{ $conditionId }}" tabindex="54"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-7-{{ $conditionId }}" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-7</td>
            <td class="iw400-right-bdr" colspan="2">Module 7</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-7-{{ $conditionId }}" tabindex="55"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-8-{{ $conditionId }}" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-8</td>
            <td class="iw400-right-bdr" colspan="2">Module 8</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-8-{{ $conditionId }}" tabindex="55"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-9-{{ $conditionId }}" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-9</td>
            <td class="iw400-right-bdr" colspan="2">Module 9</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-9-{{ $conditionId }}" tabindex="55"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-10-{{ $conditionId }}" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-10</td>
            <td class="iw400-right-bdr" colspan="2">Module 10</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-10-{{ $conditionId }}" tabindex="55"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-11-{{ $conditionId }}" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-11</td>
            <td class="iw400-right-bdr" colspan="2">Module 11</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-11-{{ $conditionId }}" tabindex="55"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-12-{{ $conditionId }}" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-12</td>
            <td class="iw400-right-bdr" colspan="2">Module 12</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="checkbox" id="h-12-{{ $conditionId }}" tabindex="55"></input></td>
            <td class="right-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;">Notes</td>
            <td class="w400-bdr">I-1</td>
            <td class="iw400-right-bdr" colspan="2"></td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr" colspan="2"><input type="text" class="txt-center-align" id="i-1-{{ $conditionId }}" tabindex="56"></input></td>
            <td class="right-bdr bottom-bdr"><div style="overflow:hidden"></td>
        </tr>
        <tr class="class-truss-hide">
            <td><div style="overflow:hidden"></td>
            <td colspan="11" rowspan="19" style="position: relative;" class="iw400-bdr">
                <canvas class="px-4" id="stick-canvas-{{ $conditionId }}" style="z-index:2; background:aliceblue" width="900px" height="500px"></canvas>
                <div class="axisCheckBox"><input type="checkbox" id="stick-axis-{{ $conditionId }}" tabindex="106"><label for="stick-axis-{{ $conditionId }}">Show axis</label></div>
                <div class="alertModuleFlow" id="stick-module-alert-{{ $conditionId }}">Warning - Modules extend past ridge</div>
            </td>
        </tr>
    </tbody>
    </table>

    <!-- blank table -->
    <table id="sample-static-color-table-{{ $conditionId }}" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
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
    </tbody> 
    </table>

    <div class="trussInputPane" id="trussInput-{{ $conditionId }}">
        <!-- Truss Data Input table section -->
        <table id="truss-data-input-table-{{ $conditionId }}" cellspacing="0" cellpadding="0" style="margin-left:80px; border-spacing:0; border: 1px solid black" >    
            <colgroup>
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="40">
                <col width="40">
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="40">
                <col width="40">
                <col width="80">
            </colgroup>
        <tbody>
            <tr class="h13">
                <td class="iw400-bdr" colspan="13" >Truss Data Input</td>
            </tr>
            <tr class="h13">
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
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
                <td class="w400-yellow-bdr" colspan="3">
                    <select id="option-roof-slope-{{ $conditionId }}">
                        <option data-value="Roof slope (degrees)" selected="">Roof slope (degrees)</option>
                        <option data-value="Top ridge height above floor plane">Top ridge height above floor plane</option>
                    </select>
                </td>
                <td class="w400-yellow-bdr" colspan="2">
                    <input type="text" id="txt-roof-degree-{{ $conditionId }}" class="txt-center-align" tabindex="57" value="45.00"></input>
                </td>
                <td class="iw400" style="color:red; font-weight:500; text-align:left"  colspan="8">
                    <p id="warning-roof-degree-{{ $conditionId }}" style="display:none">Zero is not acceptable</p>
                </td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" id="txt-roof-slope-another-{{ $conditionId }}" style="text-align:center !important;">Top ridge height above floor plane</td>
                <td class="w400-bdr" id="td-unknown-degree1-{{ $conditionId }}" colspan="2">10.25</td>
                <td colspan="8"><div style="overflow:hidden"></div></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:center !important;">Calculated roof plane length</td>
                <td class="w400-bdr" id="td-calculated-roof-plane-length-{{ $conditionId }}" colspan="2">16.97</td>
                <td class="iw400" colspan="8" style="text-align:left !important;">ft (based upon floor plane and ridge height values)</td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:center !important;">Diff between measured and calculated</td>
                <td class="w400-bdr" style="color:red; font-weight:700" id="td-diff-between-measured-and-calculated-{{ $conditionId }}" colspan="2">2.47</td>
                <td class="iw400" colspan="8" style="text-align:left !important;">ft Please check all values</td>
            </tr>
            <tr class="h13">
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
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
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
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
                <td class="iw400-bdr" colspan="6">Roof Plane</td>
                <td><div style="overflow:hidden"></td>
                <td  class="iw400-bdr" colspan="6">Floor Plane</td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;">Member type</td>
                <td class="w400-green-bdr" colspan="2">
                    <select id="option-roof-member-type-{{ $conditionId }}" tabindex="58">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="right-bdr"><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;">Member type</td>
                <td class="w400-green-bdr" colspan="2">
                    <select id="option-floor-member-type-{{ $conditionId }}" tabindex="67">
                        <option data-value="2x4" selected>2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="right-bdr"><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;">Length of roof plane (ft | in)</td>
                <td class="w400-yellow-bdr">
                    <input type="text" class="txt-center-align" id="txt-length-of-roof-plane-f-{{ $conditionId }}" tabindex="59" value="14.50"></input>
                </td>
                <td class="w400-yellow-bdr">
                    <input type="text" class="txt-center-align" id="txt-length-of-roof-plane-i-{{ $conditionId }}" tabindex="59" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr">
                    <input type="text" class="txt-calced" id="txt-length-of-roof-plane-{{ $conditionId }}" tabindex="-1" value="14.50"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;">Length of floor plane (ft | in)</td>
                <td class="w400-yellow-bdr">
                    <input type="text" class="txt-center-align" id="txt-length-of-floor-plane-f-{{ $conditionId }}" tabindex="68" value="12.00"></input>
                </td>
                <td class="w400-yellow-bdr">
                    <input type="text" class="txt-center-align" id="txt-length-of-floor-plane-i-{{ $conditionId }}" tabindex="68" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr">
                    <input type="text" class="txt-calced" id="txt-length-of-floor-plane-{{ $conditionId }}" tabindex="-1" value="12.00"></input>
                </td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;">Number of segments along roof plane</td>
                <td class="w400-green-bdr" colspan="2">
                    <select id="option-number-segments1-{{ $conditionId }}" style="text-align:center" tabindex="60" class="{{ $conditionId }}-option-number-segments1">
                        <option data-value="1">1</option>
                        <option data-value="2">2</option>
                        <option data-value="3">3</option>
                        <option data-value="4" selected="">4</option>
                        <option data-value="5">5</option>
                        <option data-value="6">6</option>
                    </select>            
                </td>
                <td class="right-bdr"><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;">Number of segments along floor plane</td>
                <td class="w400-green-bdr" colspan="2">
                    <select id="option-number-segments2-{{ $conditionId }}" style="text-align:center" tabindex="69" class="{{ $conditionId }}-option-number-segments2">
                        <option data-value="1">1</option>
                        <option data-value="2">2</option>
                        <option data-value="3" selected="">3</option>
                        <option data-value="4">4</option>
                        <option data-value="5">5</option>
                        <option data-value="6">6</option>
                    </select>            
                </td>
                <td class="right-bdr"><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment1-caption-{{ $conditionId }}">Segment 1 length (ft | in)</td>
                <td class="w400-yellow-bdr" id="td-roof-segment1-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment1-length-f-{{ $conditionId }}" tabindex="61" value="4.50"></input>
                </td>
                <td class="w400-yellow-bdr" id="td-roof-segment1-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment1-length-i-{{ $conditionId }}" tabindex="61" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-roof-segment1-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-roof-segment1-length-{{ $conditionId }}" tabindex="-1" value="4.50"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment1-caption-{{ $conditionId }}">Segment 6 length (ft | in)</td>
                <td class="w400-yellow-bdr" id="td-floor-segment1-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment1-length-f-{{ $conditionId }}" tabindex="70" value="4.00"></input>
                </td>
                <td class="w400-yellow-bdr" id="td-floor-segment1-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment1-length-i-{{ $conditionId }}" tabindex="70" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-floor-segment1-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-floor-segment1-length-{{ $conditionId }}" tabindex="-1" value="4.00"></input>
                </td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment2-caption-{{ $conditionId }}">Segment 2 length (ft | in)</td>
                <td class="w400-yellow-bdr" id="td-roof-segment2-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment2-length-f-{{ $conditionId }}" tabindex="62" value="3.00"></input>
                </td>
                <td class="w400-yellow-bdr" id="td-roof-segment2-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment2-length-i-{{ $conditionId }}" tabindex="62" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-roof-segment2-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-roof-segment2-length-{{ $conditionId }}" tabindex="-1" value="3.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment2-caption-{{ $conditionId }}">Segment 7 length (ft | in)</td>
                <td class="w400-yellow-bdr" id="td-floor-segment2-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment2-length-f-{{ $conditionId }}" tabindex="71" value="4.00"></input>
                </td>
                <td class="w400-yellow-bdr" id="td-floor-segment2-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment2-length-i-{{ $conditionId }}" tabindex="71" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-floor-segment2-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-floor-segment2-length-{{ $conditionId }}" tabindex="-1" value="4.00"></input>
                </td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment3-caption-{{ $conditionId }}">Segment 3 length (ft | in)</td>
                <td class="w400-yellow-bdr" id="td-roof-segment3-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment3-length-f-{{ $conditionId }}" tabindex="63" value="4.00"></input>
                </td>
                <td class="w400-yellow-bdr" id="td-roof-segment3-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment3-length-i-{{ $conditionId }}" tabindex="63" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-roof-segment3-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-roof-segment3-length-{{ $conditionId }}" tabindex="-1" value="4.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment3-caption-{{ $conditionId }}">Segment 8 length (ft | in)</td>
                <td class="w400-yellow-bdr" id="td-floor-segment3-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment3-length-f-{{ $conditionId }}" tabindex="72" value="4.00"></input>
                </td>
                <td class="w400-yellow-bdr" id="td-floor-segment3-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment3-length-i-{{ $conditionId }}" tabindex="72" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-floor-segment3-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-floor-segment3-length-{{ $conditionId }}" tabindex="-1" value="4.00"></input>
                </td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment4-caption-{{ $conditionId }}">Segment 4 length (ft | in)</td>
                <td class="w400-yellow-bdr" id="td-roof-segment4-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment4-length-f-{{ $conditionId }}" tabindex="64" value="3.00"></input>
                </td>
                <td class="w400-yellow-bdr" id="td-roof-segment4-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment4-length-i-{{ $conditionId }}" tabindex="64" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-roof-segment4-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-roof-segment4-length-{{ $conditionId }}" tabindex="-1" value="3.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment4-caption-{{ $conditionId }}"></td>
                <td class="w400-blue-bdr" id="td-floor-segment4-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment4-length-f-{{ $conditionId }}" tabindex="73" value="3.00"></input>
                </td>
                <td class="w400-blue-bdr" id="td-floor-segment4-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment4-length-i-{{ $conditionId }}" tabindex="73" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-floor-segment4-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-floor-segment4-length-{{ $conditionId }}" tabindex="73" value="3.00"></input>
                </td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment5-caption-{{ $conditionId }}"></td>
                <td class="w400-blue-bdr" id="td-roof-segment5-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment5-length-f-{{ $conditionId }}" tabindex="65" value="3.00"></input>
                </td>
                <td class="w400-blue-bdr" id="td-roof-segment5-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment5-length-i-{{ $conditionId }}" tabindex="65" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-roof-segment5-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-roof-segment5-length-{{ $conditionId }}" tabindex="-1" value="3.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment5-caption-{{ $conditionId }}"></td>
                <td class="w400-blue-bdr" id="td-floor-segment5-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment5-length-f-{{ $conditionId }}" tabindex="74" value="3.00"></input>
                </td>
                <td class="w400-blue-bdr" id="td-floor-segment5-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment5-length-i-{{ $conditionId }}" tabindex="74" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-floor-segment5-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-floor-segment5-length-{{ $conditionId }}" tabindex="-1" value="3.00"></input>
                </td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment6-caption-{{ $conditionId }}"></td>
                <td class="w400-blue-bdr" id="td-roof-segment6-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment6-length-f-{{ $conditionId }}" tabindex="66" value="3.00"></input>
                </td>
                <td class="w400-blue-bdr" id="td-roof-segment6-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-roof-segment6-length-i-{{ $conditionId }}" tabindex="66" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-roof-segment6-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-roof-segment6-length-{{ $conditionId }}" tabindex="-1" value="3.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment6-caption-{{ $conditionId }}"></td>
                <td class="w400-blue-bdr" id="td-floor-segment6-length-f-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment6-length-f-{{ $conditionId }}" tabindex="75" value="3.00"></input>
                </td>
                <td class="w400-blue-bdr" id="td-floor-segment6-length-i-{{ $conditionId }}">
                    <input type="text" class="txt-center-align" id="txt-floor-segment6-length-i-{{ $conditionId }}" tabindex="75" value="0.00"></input>
                </td>
                <td class="td-dec-feet right-bdr" id="td-floor-segment6-length-{{ $conditionId }}">
                    <input type="text" class="txt-calced" id="txt-floor-segment6-length-{{ $conditionId }}" tabindex="-1" value="3.00"></input>
                </td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;">Sum of lengths entered</td>
                <td class="w400-bdr" id="td-sum-of-length-entered-{{ $conditionId }}" colspan="2">14.50</td>
                <td class="right-bdr"><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;">Total length entered</td>
                <td class="w400-bdr" id="td-total-length-entered-{{ $conditionId }}" colspan="2">12.00</td>
                <td class="right-bdr"><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;border-bottom: 1.00pt solid windowtext;">Check sum of chord lengths</td>
                <td class="w400-bdr" style="font-weight:800; width: 180px; word-break: break-word;" id="td-checksum-of-segment1-{{ $conditionId }}" colspan="2">OK</td>
                <td class="right-bdr bottom-bdr"><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;border-bottom: 1.00pt solid windowtext;">Check sum of chord lengths</td>
                <td class="w400-bdr" style="font-weight:800; width: 180px; word-break: break-word;" id="td-checksum-of-segment2-{{ $conditionId }}" colspan="2">OK</td>
                <td class="right-bdr bottom-bdr"><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
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
                <td class="iw400-bdr" colspan="13" style="border: 2pt solid windowtext;">Truss Segments</td>
            </tr>
            <tr class="h13">
                <td class="iw400-bdr" style="border: 2pt solid windowtext;" colspan="2">Roof Plane</td>
                <td class="iw400-bdr" style="border: 2pt solid windowtext;" colspan="3">Floor Plane</td>
                <td class="iw400-bdr" style="border: 2pt solid windowtext;" colspan="8">Diagonals</td>
            </tr>
            <tr class="h13">
                <td class="w400-bdr" style="border-left: 2pt solid windowtext;">Mem #</td>
                <td class="w400-bdr">Mem Type</td>
                <td class="w400-bdr" style="border-left: 2pt solid windowtext;">Mem #</td>
                <td class="w400-bdr" colspan="2">Mem Type</td>
                <td class="w400-bdr" style="border-left: 2pt solid windowtext;">Remove</td>
                <td class="w400-bdr">Mem #</td>
                <td class="w400-bdr">Mem Type</td>
                <td class="w400-bdr" style="border-left: 2pt solid windowtext;">Remove</td>
                <td class="w400-bdr">Mem #</td>
                <td class="w400-bdr" colspan="2">Mem Type</td>
                <td class="w400-bdr" style="border-right: 2pt solid windowtext;">Reverse</td>
            </tr>
            <tr class="h13">
                <td class="w400-bdr" id="td-truss-roof-segment1-{{ $conditionId }}" style="border-left: 2pt solid windowtext;">1</td>
                <td class="w400-bdr" id="td-truss-roof-segment1-type-{{ $conditionId }}">2x6</td>
                <td class="w400-bdr" id="td-truss-floor-segment1-{{ $conditionId }}" style="border-left: 2pt solid windowtext;">5</td>
                <td class="w400-bdr" id="td-truss-floor-segment1-type-{{ $conditionId }}" colspan="2">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-1-{{ $conditionId }}" tabindex="76"></input></td>
                <td class="w400-bdr" id="td-diag-1-1-{{ $conditionId }}">8</td>
                <td class="w400-green-bdr" id="td-diag-1-1-type-{{ $conditionId }}">
                    <select id="option-diagonals-mem1-1-type-{{ $conditionId }}" tabindex="77">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-1-{{ $conditionId }}" tabindex="88"></input></td>
                <td class="w400-bdr" id="td-diag-2-1-{{ $conditionId }}">11</td>
                <td class="w400-green-bdr" id="td-diag-2-1-type-{{ $conditionId }}" colspan="2">
                    <select id="option-diagonals-mem2-1-type-{{ $conditionId }}" tabindex="89">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-1-reverse-{{ $conditionId }}" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-1-{{ $conditionId }}" tabindex="90"></input></td>
            </tr>
            <tr class="h13">
                <td class="w400-bdr" id="td-truss-roof-segment2-{{ $conditionId }}" style="border-left: 2pt solid windowtext;">2</td>
                <td class="w400-bdr" id="td-truss-roof-segment2-type-{{ $conditionId }}">2x6</td>
                <td class="w400-bdr" id="td-truss-floor-segment2-{{ $conditionId }}" style="border-left: 2pt solid windowtext;">6</td>
                <td class="w400-bdr" id="td-truss-floor-segment2-type-{{ $conditionId }}" colspan="2">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-2-{{ $conditionId }}" tabindex="78"></input></td>
                <td class="w400-bdr" id="td-diag-1-2-{{ $conditionId }}">9</td>
                <td class="w400-green-bdr" id="td-diag-1-2-type-{{ $conditionId }}">
                    <select id="option-diagonals-mem1-2-type-{{ $conditionId }}" tabindex="79">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-2-{{ $conditionId }}" tabindex="91"></input></td>
                <td class="w400-bdr" id="td-diag-2-2-{{ $conditionId }}">12</td>
                <td class="w400-green-bdr" id="td-diag-2-2-type-{{ $conditionId }}" colspan="2">
                    <select id="option-diagonals-mem2-2-type-{{ $conditionId }}" tabindex="92">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-2-reverse-{{ $conditionId }}" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-2-{{ $conditionId }}" tabindex="93"></input></td>
            </tr>
            <tr class="h13">
                <td class="w400-bdr" id="td-truss-roof-segment3-{{ $conditionId }}" style="border-left: 2pt solid windowtext;">3</td>
                <td class="w400-bdr" id="td-truss-roof-segment3-type-{{ $conditionId }}">2x6</td>
                <td class="w400-bdr" id="td-truss-floor-segment3-{{ $conditionId }}" style="border-left: 2pt solid windowtext;">7</td>
                <td class="w400-bdr" id="td-truss-floor-segment3-type-{{ $conditionId }}" colspan="2">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-3-{{ $conditionId }}" tabindex="80"></input></td>
                <td class="w400-bdr" id="td-diag-1-3-{{ $conditionId }}">10</td>
                <td class="w400-green-bdr" id="td-diag-1-3-type-{{ $conditionId }}">
                    <select id="option-diagonals-mem1-3-type-{{ $conditionId }}" tabindex="81">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-3-{{ $conditionId }}" tabindex="94"></input></td>
                <td class="w400-bdr" id="td-diag-2-3-{{ $conditionId }}">13</td>
                <td class="w400-green-bdr" id="td-diag-2-3-type-{{ $conditionId }}" colspan="2">
                    <select id="option-diagonals-mem2-3-type-{{ $conditionId }}" tabindex="95">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-3-reverse-{{ $conditionId }}" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-3-{{ $conditionId }}" tabindex="96"></input></td>
            </tr>
            <tr class="h13">
                <td class="w400-bdr" id="td-truss-roof-segment4-{{ $conditionId }}" style="border-left: 2pt solid windowtext;">4</td>
                <td class="w400-bdr" id="td-truss-roof-segment4-type-{{ $conditionId }}">2x6</td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment4-{{ $conditionId }}" style="border-left: 2pt solid windowtext;"></td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment4-type-{{ $conditionId }}" colspan="2">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-4-{{ $conditionId }}" tabindex="82"></input></td>
                <td class="w400-blue-bdr" id="td-diag-1-4-{{ $conditionId }}"></td>
                <td class="w400-blue-bdr" id="td-diag-1-4-type-{{ $conditionId }}">
                    <select id="option-diagonals-mem1-4-type-{{ $conditionId }}" tabindex="83">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-4-{{ $conditionId }}" tabindex="97"></input></td>
                <td class="w400-blue-bdr" id="td-diag-2-4-{{ $conditionId }}"></td>
                <td class="w400-blue-bdr" id="td-diag-2-4-type-{{ $conditionId }}" colspan="2">
                    <select id="option-diagonals-mem2-4-type-{{ $conditionId }}" tabindex="98">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-4-reverse-{{ $conditionId }}" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-4-{{ $conditionId }}" tabindex="99"></input></td>
            </tr>
            <tr class="h13">
                <td class="w400-blue-bdr" id="td-truss-roof-segment5-{{ $conditionId }}" style="border-left: 2pt solid windowtext;"></td>
                <td class="w400-blue-bdr" id="td-truss-roof-segment5-type-{{ $conditionId }}">2x6</td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment5-{{ $conditionId }}" style="border-left: 2pt solid windowtext;"></td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment5-type-{{ $conditionId }}" colspan="2">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-5-{{ $conditionId }}" tabindex="84"></input></td>
                <td class="w400-blue-bdr" id="td-diag-1-5-{{ $conditionId }}"></td>
                <td class="w400-blue-bdr" id="td-diag-1-5-type-{{ $conditionId }}">
                    <select id="option-diagonals-mem1-5-type-{{ $conditionId }}" tabindex="85">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-5-{{ $conditionId }}" tabindex="100"></input></td>
                <td class="w400-blue-bdr" id="td-diag-2-5-{{ $conditionId }}"></td>
                <td class="w400-blue-bdr" id="td-diag-2-5-type-{{ $conditionId }}" colspan="2">
                    <select id="option-diagonals-mem2-5-type-{{ $conditionId }}" tabindex="101">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-5-reverse-{{ $conditionId }}" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-5-{{ $conditionId }}" tabindex="102"></input></td>
            </tr>
            <tr class="h13" style="border-bottom: 2pt solid windowtext;">
                <td class="w400-blue-bdr" id="td-truss-roof-segment6-{{ $conditionId }}" style="border-left: 2pt solid windowtext;"></td>
                <td class="w400-blue-bdr" id="td-truss-roof-segment6-type-{{ $conditionId }}">2x6</td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment6-{{ $conditionId }}" style="border-left: 2pt solid windowtext;"></td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment6-type-{{ $conditionId }}" colspan="2">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-6-{{ $conditionId }}" tabindex="86"></input></td>
                <td class="w400-blue-bdr" id="td-diag-1-6-{{ $conditionId }}"></td>
                <td class="w400-blue-bdr" id="td-diag-1-6-type-{{ $conditionId }}">
                    <select id="option-diagonals-mem1-6-type-{{ $conditionId }}" tabindex="87">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-6-{{ $conditionId }}" tabindex="103"></input></td>
                <td class="w400-blue-bdr" id="td-diag-2-6-{{ $conditionId }}"></td>
                <td class="w400-blue-bdr" id="td-diag-2-6-type-{{ $conditionId }}" colspan="2">
                    <select id="option-diagonals-mem2-6-type-{{ $conditionId }}" tabindex="104">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-6-reverse-{{ $conditionId }}" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-6-{{ $conditionId }}" tabindex="105"></input></td>
            </tr>
            <tr class="h13">
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <!-- <tr class="h13">
                <td class="iw400" colspan="11" style="text-align:left;">Loading includes member self weight and roofing materials  w loading = wind on exposed areas</td>
            </tr> -->
            <td colspan="13" rowspan="19" style="position: relative;" class="iw400-bdr">
                <canvas class="px-4" id="canvas-{{ $conditionId }}" style="z-index:2; background:aliceblue" width="900px" height="500px"></canvas>
                <div class="axisCheckBox"><input type="checkbox" id="truss-axis-{{ $conditionId }}" tabindex="106"><label for="truss-axis-{{ $conditionId }}">Show axis</label></div>
                <div class="alertModuleFlow" id="truss-module-alert-{{ $conditionId }}">Warning - Modules extend past ridge</div>
            </td>
            </tbody>
        </table>
    </div>

</div>
</form>