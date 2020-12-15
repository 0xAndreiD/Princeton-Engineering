<form id="inputform-{{ $conditionId }}">
<!-- Section info table section -->
<div class="form-group rfdTypePane">
    <div class="custom-control custom-radio custom-control-success mb-1">
        <input type="radio" class="custom-control-input rfdTypeSelect" id="trussFlagOption-{{ $conditionId }}-1" name="trussFlag-{{ $conditionId }}" onchange="fcChangeType({{ $conditionId }}, 0)" checked value="0">
        <label class="custom-control-label rfdTypeSelect" for="trussFlagOption-{{ $conditionId }}-1">Stick Framing Data Input</label>
    </div>
    <div class="custom-control custom-radio custom-control-success mb-1 ">
        <input type="radio" class="custom-control-input rfdTypeSelect" id="trussFlagOption-{{ $conditionId }}-2" name="trussFlag-{{ $conditionId }}" onchange="fcChangeType({{ $conditionId }}, 1)" value="1">
        <label class="custom-control-label rfdTypeSelect" for="trussFlagOption-{{ $conditionId }}-2">Truss Framing Data Input</label>
    </div>
</div>
<div class="row">
    <!-- Section info table section -->
    <table id="section-info-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
        <colgroup>
            <col width="80">
            <col width="160">
            <col width="70">
            <col width="320">
            <col width="70">
            <col width="100">
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
            <td class="iw400-bdr">Description</td>
            <td class="iw400-bdr">Units</td>
            <td class="iw400-bdr">Data Entry</td>
            <td><div style="overflow:hidden"></div></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle" rowspan="11" id="label-A-1">Roof Data Input</td>
            <td class="w400-bdr">A-1</td>
            <td class="iw400-right-bdr">Framing Condition Number</td>
            <td class="iw400-bdr"></td>
            <td class="w400-green-bdr">{{ $conditionId }}</td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-2</td>
            <td class="iw400-right-bdr">Roof Average Height</td>
            <td class="iw400-bdr">ft</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="a-2-1" tabindex="26" value="30.00"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-3</td>
            <td class="iw400-right-bdr">Plan View Length of Building Section</td>
            <td class="iw400-bdr">ft</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="a-3-1"  tabindex="27" value="31.17"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-4</td>
            <td class="iw400-right-bdr">Plan View Width of Building Section</td>
            <td class="iw400-bdr">ft</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="a-4-1"  tabindex="28" value="14.25"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-5</td>
            <td class="iw400-right-bdr">Name of Array Section</td>
            <td class="iw400-bdr">text</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="a-5-1"  tabindex="29" value="MP1"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-6</td>
            <td class="iw400-right-bdr">Orientation</td>
            <td class="iw400-bdr">select</td>
            <td class="w400-green-bdr">
                <select id="a-6-1" tabindex="30">
                    <option data-value="Landscape" selected="">Landscape</option>
                    <option data-value="Portrait">Portrait</option>
                </select>
            </td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide" id="tr-7-1">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-7</td>
            <td class="iw400-right-bdr">Roof Slope</td>
            <td class="iw400-bdr">deg</td>
            <td id="value-7-1" class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="a-7-1"  tabindex="31" value="33.00"></input></td>
            <td id="calced-7-1" class="calcedCell"></td>
            <!-- <td class="w400-bdr" id="tc-7-1" style="pointer-events: none;"><input type="text" class="txt-center-align" id="ac-7-1" value="33.00"></td>
            <td class="w400-bdr"><input type="checkbox" id="aa-7-1" checked></input></td> -->
        </tr>
        <tr class="h13 class-truss-hide" id="tr-8-1">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-8</td>
            <td class="iw400-right-bdr">Diagonal Rafter Length from Plate to Ridge</td>
            <td class="iw400-bdr">ft</td>
            <td id="value-8-1" class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="a-8-1"  tabindex="32" value="13.42"></input></td>
            <td id="calced-8-1" class="calcedCell"></td>
            <!-- <td class="w400-bdr" id="tc-8-1" style="pointer-events: none;"><input type="text" class="txt-center-align" id="ac-8-1"></td>
            <td class="w400-bdr"><input type="checkbox" id="aa-8-1"></input></td> -->
        </tr>
        <tr class="h13 class-truss-hide" id="tr-9-1">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-9</td>
            <td class="iw400-right-bdr" id="label-A-9">Rise from Rafter Plate to Top Ridge</td>
            <td class="iw400-bdr">ft</td>
            <td id="value-9-1" class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="a-9-1"  tabindex="33" value="5.00"></input></td>
            <td id="calced-9-1" class="calcedCell"></td>
            <!-- <td class="w400-bdr" id="tc-9-1" style="pointer-events: none;"><input type="text" class="txt-center-align" id="ac-9-1"></td>
            <td class="w400-bdr"><input type="checkbox" id="aa-9-1" checked></input></td> -->
        </tr>
        <tr class="h13 class-truss-hide" id="tr-10-1">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-10</td>
            <td class="iw400-right-bdr" id="label-A-10">Horiz Len from Outside of Rafter Plate to Ridge</td>
            <td class="iw400-bdr">ft</td>
            <td id="value-10-1" class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="a-10-1"  tabindex="34" value="11.25"></input></td>
            <td id="calced-10-1" class="calcedCell"></td>
            <!-- <td class="w400-bdr" id="tc-10-1" style="pointer-events: none; display: table-cell !important;"><input type="text" class="txt-center-align" id="ac-10-1"></td>
            <td class="w400-bdr"><input type="checkbox" id="aa-10-1"></input></td> -->
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">A-11</td>
            <td class="iw400-right-bdr" id="label-A-11">Diagonal Overhang Length past Rafter Plate</td>
            <td class="iw400-bdr">ft</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="a-11-1"  tabindex="35" value="0.83"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;" rowspan="4" id="label-B-1">Rafter Data Input</td>
            <td class="w400-bdr">B-1</td>
            <td class="iw400-right-bdr">Rafter width **</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="b-1-1"  tabindex="36" value="1.50"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">B-2</td>
            <td class="iw400-right-bdr">Rafter Height **</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="b-2-1"  tabindex="37" value="5.50"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle; display: none;" rowspan="2" id="title-B-3">Truss Data Input</td>
            <td class="w400-bdr">B-3</td>
            <td class="iw400-right-bdr" id="label-B-3">Joist Spacing - Center to Center</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="b-3-1"  tabindex="38" value="16.00"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">B-4</td>
            <td class="iw400-right-bdr" id="label-B-4">Rafter Material</td>
            <td class="iw400-bdr">select</td>
            <td class="w400-green-bdr">
                <select id="b-4-1">
                    <option data-value="Douglas-fir" selected="">Douglas-fir</option>
                    <option data-value="Hemlock">Hemlock</option>
                    <option data-value="Pine">Pine</option>
                    <option data-value="Spruce">Spruce</option>
                </select>
            </td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;" rowspan="4">Collar Tie / Knee Wall Information</td>
            <td class="w400-bdr">C-1</td>
            <td class="iw400-right-bdr">Collar Tie Description</td>
            <td class="iw400-bdr">text</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="c-1-1"  tabindex="39" value="2x6 collar tie"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">C-2</td>
            <td class="iw400-right-bdr">Dist. from Top of Collar Tie to Attic Deck</td>
            <td class="iw400-bdr">ft</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="c-2-1"  tabindex="40" value="3.08"></input></td>
            <td><div id="c-2-warn" class="warnCell">Warning - Height above high end of rafter</td>
        </tr>
        <tr class="h13 class-truss-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">C-3</td>
            <td class="iw400-right-bdr">Tie Spacing - Center to Center</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="c-3-1"  tabindex="41" value="32.00"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13 class-truss-hide">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">C-4</td>
            <td class="iw400-right-bdr">Knee Wall Height</td>
            <td class="iw400-bdr">ft</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="c-4-1"  tabindex="41" value="2.00"></input></td>
            <td><div id="c-4-warn" class="warnCell">Warning - Height above high end of rafter</td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;" rowspan="3">Roof Deck and Surface</td>
            <td class="w400-bdr">D-1</td>
            <td class="iw400-right-bdr">Plywood Thickness</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="d-1-1"  tabindex="42" value="0.50"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">D-2</td>
            <td class="iw400-right-bdr">Shingle Type</td>
            <td class="iw400-bdr">select</td>
            <td class="w400-green-bdr">
                <select id="d-2-1" tabindex="43">
                    <option data-value="Standard" selected="">Standard</option>
                    <option data-value="Architecture">Architectural - Heavy</option>
                    <option data-value="Metal Deck">Metal Deck</option>
                    <option data-value="Roof Tile">Roof Tile</option>
                </select>
            </td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">D-3</td>
            <td class="iw400-right-bdr"># Shingle Layers</td>
            <td class="iw400-bdr">#</td>
            <td class="w400-yellow-bdr">
                <select id="d-3-1" tabindex="44">
                    <option data-value="1" selected="">1</option>
                    <option data-value="2">2</option>
                    <option data-value="3">3</option>
                </select>
            </td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;" rowspan="2">Location</td>
            <td class="w400-bdr">E-1</td>
            <td class="iw400-right-bdr">Uphill Distance from Eave to Low Edge of Module</td>
            <td class="iw400-bdr">ft</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="e-1-1" tabindex="45" value="4.25"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">E-2</td>
            <td class="iw400-right-bdr">Uphill Distance from Eave to Lowest Support</td>
            <td class="iw400-bdr">ft</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="e-2-1" tabindex="46" value="4.25"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr">Number of Modules</td>
            <td class="w400-bdr">F-1</td>
            <td class="iw400-right-bdr" id="label-F-1">Maximum # Modules along Rafter</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr">
                <select id="f-1-1" tabindex="47" onchange="maxModuleNumChange({{ $conditionId }})">
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
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr">Uphill Gap</td>
            <td class="w400-bdr">G-1</td>
            <td class="iw400-right-bdr">Uphill Gap Between Modules</td>
            <td class="iw400-bdr">in</td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="g-1-1" tabindex="48" value="1"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-1">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;" rowspan="2" id="Module-Left-Text">Rotate Module Orientation</td>
            <td class="w400-bdr">H-1</td>
            <td class="iw400-right-bdr">Module 1</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-1-1" tabindex="49"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-2">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-2</td>
            <td class="iw400-right-bdr">Module 2</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-2-1" tabindex="50"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-3" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-3</td>
            <td class="iw400-right-bdr">Module 3</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-3-1" tabindex="51"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-4" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-4</td>
            <td class="iw400-right-bdr">Module 4</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-4-1" tabindex="52"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-5" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-5</td>
            <td class="iw400-right-bdr">Module 5</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-5-1" tabindex="53"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-6" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-6</td>
            <td class="iw400-right-bdr">Module 6</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-6-1" tabindex="54"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-7" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-7</td>
            <td class="iw400-right-bdr">Module 7</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-7-1" tabindex="55"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-8" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-8</td>
            <td class="iw400-right-bdr">Module 8</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-8-1" tabindex="55"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-9" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-9</td>
            <td class="iw400-right-bdr">Module 9</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-9-1" tabindex="55"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-10" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-10</td>
            <td class="iw400-right-bdr">Module 10</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-10-1" tabindex="55"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-11" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-11</td>
            <td class="iw400-right-bdr">Module 11</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-11-1" tabindex="55"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13" id="Module-12" style="display: none;">
            <td><div style="overflow:hidden"></td>
            <td class="w400-bdr">H-12</td>
            <td class="iw400-right-bdr">Module 12</td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="checkbox" id="h-12-1" tabindex="55"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td class="iw400-bdr" style="vertical-align : middle;">Notes</td>
            <td class="w400-bdr">I-1</td>
            <td class="iw400-right-bdr"></td>
            <td class="iw400-bdr"></td>
            <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" id="i-1-1" tabindex="56"></input></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="class-truss-hide">
            <td><div style="overflow:hidden"></td>
            <td colspan="11" rowspan="19" style="position: relative;" class="iw400-bdr">
                <canvas class="px-4" id="stick-canvas-{{ $conditionId }}" style="z-index:2; background:aliceblue" width="900px" height="500px"></canvas>
                <div class="axisCheckBox"><input type="checkbox" id="stick-axis" tabindex="106"><label for="stick-axis">Show axis</label></div>
                <div class="alertModuleFlow" id="stick-module-alert">Warning - Modules extend past ridge</div>
            </td>
        </tr>
    </tbody>
    </table>

    <!-- blank table -->
    <table id="sample-static-color-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
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
        <table id="truss-data-input-table" cellspacing="0" cellpadding="0" style="margin-left:80px; border-spacing:0; border: 1px solid black" >    
            <colgroup>
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="80">
                <col width="80">
            </colgroup>
        <tbody>
            <tr class="h13">
                <td class="iw400-bdr" colspan="11" >Truss Data Input</td>
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
            </tr>
            <tr class="h13">
                <td class="w400-yellow-bdr" colspan="3">
                    <select id="option-roof-slope">
                        <option data-value="Roof slope (degrees)" selected="">Roof slope (degrees)</option>
                        <option data-value="Top ridge height above floor plane">Top ridge height above floor plane</option>
                    </select>
                </td>
                <td class="w400-yellow-bdr">
                    <input type="text" id="txt-roof-degree" class="txt-center-align" tabindex="57" value="45.00"></input>
                </td>
                <td colspan="7"><div style="overflow:hidden"></div></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" id="txt-roof-slope-another" style="text-align:center !important;">Top ridge height above floor plane</td>
                <td class="w400-bdr" id="td-unknown-degree1" >10.25</td>
                <td colspan="7"><div style="overflow:hidden"></div></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:center !important;">Calculated roof plane length</td>
                <td class="w400-bdr" id="td-calculated-roof-plane-length">16.97</td>
                <td class="iw400" colspan="7" style="text-align:left !important;">ft (based upon floor plane and ridge height values)</td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:center !important;">Differnce between measured and calculated</td>
                <td class="w400-bdr" style="color:red; font-weight:700" id="td-diff-between-measured-and-calculated">2.47</td>
                <td class="iw400" colspan="7" style="text-align:left !important;">ft Please check all values</td>
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
            </tr>
            <tr class="h13">
                <td class="iw400-bdr" colspan="4">Roof Plane</td>
                <td><div style="overflow:hidden"></td>
                <td  class="iw400-bdr" colspan="4">Floor Plane</td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;">Member type</td>
                <td class="w400-green-bdr">
                    <select id="option-roof-member-type" tabindex="58">
                        <option data-value="2x4">2x4</option>
                        <option data-value="2x6" selected="">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;">Member type</td>
                <td class="w400-green-bdr">
                    <select id="option-floor-member-type" tabindex="67">
                        <option data-value="2x4">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12" selected>2x12</option>
                    </select>
                </td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;">Length of roof plane</td>
                <td class="w400-yellow-bdr">
                    <input type="text" class="txt-center-align" id="txt-length-of-roof-plane" tabindex="59" value="14.50"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;">Length of floor plane</td>
                <td class="w400-yellow-bdr">
                    <input type="text" class="txt-center-align" id="txt-length-of-floor-plane" tabindex="68" value="12.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;">Number of segments along roof plane</td>
                <td class="w400-green-bdr">
                    <select id="option-number-segments1" style="text-align:center" tabindex="60" class="{{ $conditionId }}-option-number-segments1">
                        <option data-value="1">1</option>
                        <option data-value="2">2</option>
                        <option data-value="3">3</option>
                        <option data-value="4" selected="">4</option>
                        <option data-value="5">5</option>
                        <option data-value="6">6</option>
                    </select>            
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;">Number of segments along floor plane</td>
                <td class="w400-green-bdr">
                    <select id="option-number-segments2" style="text-align:center" tabindex="69" class="{{ $conditionId }}-option-number-segments2">
                        <option data-value="1">1</option>
                        <option data-value="2">2</option>
                        <option data-value="3" selected="">3</option>
                        <option data-value="4">4</option>
                        <option data-value="5">5</option>
                        <option data-value="6">6</option>
                    </select>            
                </td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment1-caption">Segment 1 length</td>
                <td class="w400-yellow-bdr" id="td-roof-segment1-length">
                    <input type="text" class="txt-center-align" id="txt-roof-segment1-length" tabindex="61" value="4.50"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment1-caption">Segment 6 length</td>
                <td class="w400-yellow-bdr" id="td-floor-segment1-length">
                    <input type="text" class="txt-center-align" id="txt-floor-segment1-length" tabindex="70" value="4.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment2-caption">Segment 2 length</td>
                <td class="w400-yellow-bdr" id="td-roof-segment2-length">
                    <input type="text" class="txt-center-align" id="txt-roof-segment2-length" tabindex="62" value="3.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment2-caption">Segment 7 length</td>
                <td class="w400-yellow-bdr" id="td-floor-segment2-length">
                    <input type="text" class="txt-center-align" id="txt-floor-segment2-length" tabindex="71" value="4.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment3-caption">Segment 3 length</td>
                <td class="w400-yellow-bdr" id="td-roof-segment3-length">
                    <input type="text" class="txt-center-align" id="txt-roof-segment3-length" tabindex="63" value="4.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment3-caption">Segment 8 length</td>
                <td class="w400-yellow-bdr" id="td-floor-segment3-length">
                    <input type="text" class="txt-center-align" id="txt-floor-segment3-length" tabindex="72" value="4.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment4-caption">Segment 4 length</td>
                <td class="w400-yellow-bdr" id="td-roof-segment4-length">
                    <input type="text" class="txt-center-align" id="txt-roof-segment4-length" tabindex="64" value="3.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment4-caption"></td>
                <td class="w400-blue-bdr" id="td-floor-segment4-length">
                    <input type="text" class="txt-center-align" id="txt-floor-segment4-length" tabindex="73" value="3.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment5-caption"></td>
                <td class="w400-blue-bdr" id="td-roof-segment5-length">
                    <input type="text" class="txt-center-align" id="txt-roof-segment5-length" tabindex="65" value="3.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment5-caption"></td>
                <td class="w400-blue-bdr" id="td-floor-segment5-length">
                    <input type="text" class="txt-center-align" id="txt-floor-segment5-length" tabindex="74" value="3.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;" id="td-roof-segment6-caption"></td>
                <td class="w400-blue-bdr" id="td-roof-segment6-length">
                    <input type="text" class="txt-center-align" id="txt-roof-segment6-length" tabindex="66" value="3.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;" id="td-floor-segment6-caption"></td>
                <td class="w400-blue-bdr" id="td-floor-segment6-length">
                    <input type="text" class="txt-center-align" id="txt-floor-segment6-length" tabindex="75" value="3.00"></input>
                </td>
                <td><div style="overflow:hidden"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;">Sum of lengths entered</td>
                <td class="w400-bdr" id="td-sum-of-length-entered">14.50</td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;">Total length entered</td>
                <td class="w400-bdr" id="td-total-length-entered">12.00</td>
                <td class="iw400" style="text-align:left !important;"></td>
                <td><div style="overflow:hidden"></td>
            </tr>
            <tr class="h13">
                <td class="iw400" colspan="3" style="text-align:right !important;border-bottom: 1.00pt solid windowtext;">Check sum of chord lengths</td>
                <td class="w400-bdr" style="font-weight:800" id="td-checksum-of-segment1">OK</td>
                <td><div style="overflow:hidden"></td>
                <td class="iw400" colspan="3" style="text-align:right !important; border-left: 1.00pt solid windowtext;border-bottom: 1.00pt solid windowtext;">Check sum of chord lengths</td>
                <td class="w400-bdr" style="font-weight:800" id="td-checksum-of-segment2">OK</td>
                <td class="iw400" style="text-align:left !important;"></td>
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
            </tr>

            <tr class="h13">
                <td class="iw400-bdr" colspan="11" style="border: 2pt solid windowtext;">Truss Segments</td>
            </tr>
            <tr class="h13">
                <td class="iw400-bdr" style="border: 2pt solid windowtext;" colspan="2">Roof Plane</td>
                <td class="iw400-bdr" style="border: 2pt solid windowtext;" colspan="2">Floor Plane</td>
                <td class="iw400-bdr" style="border: 2pt solid windowtext;" colspan="7">Diagonals</td>
            </tr>
            <tr class="h13">
                <td class="w400-bdr" style="border-left: 2pt solid windowtext;">Mem #</td>
                <td class="w400-bdr">Mem Type</td>
                <td class="w400-bdr" style="border-left: 2pt solid windowtext;">Mem #</td>
                <td class="w400-bdr">Mem Type</td>
                <td class="w400-bdr" style="border-left: 2pt solid windowtext;">Remove</td>
                <td class="w400-bdr">Mem #</td>
                <td class="w400-bdr">Mem Type</td>
                <td class="w400-bdr" style="border-left: 2pt solid windowtext;">Remove</td>
                <td class="w400-bdr">Mem #</td>
                <td class="w400-bdr">Mem Type</td>
                <td class="w400-bdr" style="border-right: 2pt solid windowtext;">Reverse</td>
            </tr>
            <tr class="h13">
                <td class="w400-bdr" id="td-truss-roof-segment1" style="border-left: 2pt solid windowtext;">1</td>
                <td class="w400-bdr" id="td-truss-roof-segment1-type">2x6</td>
                <td class="w400-bdr" id="td-truss-floor-segment1" style="border-left: 2pt solid windowtext;">5</td>
                <td class="w400-bdr" id="td-truss-floor-segment1-type">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-1" tabindex="76"></input></td>
                <td class="w400-bdr" id="td-diag-1-1">8</td>
                <td class="w400-green-bdr" id="td-diag-1-1-type">
                    <select id="option-diagonals-mem1-1-type" tabindex="77">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-1" tabindex="88"></input></td>
                <td class="w400-bdr" id="td-diag-2-1">11</td>
                <td class="w400-green-bdr" id="td-diag-2-1-type">
                    <select id="option-diagonals-mem2-1-type" tabindex="89">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-1-reverse" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-1" tabindex="90"></input></td>
            </tr>
            <tr class="h13">
                <td class="w400-bdr" id="td-truss-roof-segment2" style="border-left: 2pt solid windowtext;">2</td>
                <td class="w400-bdr" id="td-truss-roof-segment2-type">2x6</td>
                <td class="w400-bdr" id="td-truss-floor-segment2" style="border-left: 2pt solid windowtext;">6</td>
                <td class="w400-bdr" id="td-truss-floor-segment2-type">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-2" tabindex="78"></input></td>
                <td class="w400-bdr" id="td-diag-1-2">9</td>
                <td class="w400-green-bdr" id="td-diag-1-2-type">
                    <select id="option-diagonals-mem1-2-type" tabindex="79">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-2" tabindex="91"></input></td>
                <td class="w400-bdr" id="td-diag-2-2">12</td>
                <td class="w400-green-bdr" id="td-diag-2-2-type">
                    <select id="option-diagonals-mem2-2-type" tabindex="92">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-2-reverse" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-2" tabindex="93"></input></td>
            </tr>
            <tr class="h13">
                <td class="w400-bdr" id="td-truss-roof-segment3" style="border-left: 2pt solid windowtext;">3</td>
                <td class="w400-bdr" id="td-truss-roof-segment3-type">2x6</td>
                <td class="w400-bdr" id="td-truss-floor-segment3" style="border-left: 2pt solid windowtext;">7</td>
                <td class="w400-bdr" id="td-truss-floor-segment3-type">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-3" tabindex="80"></input></td>
                <td class="w400-bdr" id="td-diag-1-3">10</td>
                <td class="w400-green-bdr" id="td-diag-1-3-type">
                    <select id="option-diagonals-mem1-3-type" tabindex="81">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-3" tabindex="94"></input></td>
                <td class="w400-bdr" id="td-diag-2-3">13</td>
                <td class="w400-green-bdr" id="td-diag-2-3-type">
                    <select id="option-diagonals-mem2-3-type" tabindex="95">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-3-reverse" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-3" tabindex="96"></input></td>
            </tr>
            <tr class="h13">
                <td class="w400-bdr" id="td-truss-roof-segment4" style="border-left: 2pt solid windowtext;">4</td>
                <td class="w400-bdr" id="td-truss-roof-segment4-type">2x6</td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment4" style="border-left: 2pt solid windowtext;"></td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment4-type">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-4" tabindex="82"></input></td>
                <td class="w400-blue-bdr" id="td-diag-1-4"></td>
                <td class="w400-blue-bdr" id="td-diag-1-4-type">
                    <select id="option-diagonals-mem1-4-type" tabindex="83">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-4" tabindex="97"></input></td>
                <td class="w400-blue-bdr" id="td-diag-2-4"></td>
                <td class="w400-blue-bdr" id="td-diag-2-4-type">
                    <select id="option-diagonals-mem2-4-type" tabindex="98">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-4-reverse" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-4" tabindex="99"></input></td>
            </tr>
            <tr class="h13">
                <td class="w400-blue-bdr" id="td-truss-roof-segment5" style="border-left: 2pt solid windowtext;"></td>
                <td class="w400-blue-bdr" id="td-truss-roof-segment5-type">2x6</td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment5" style="border-left: 2pt solid windowtext;"></td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment5-type">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-5" tabindex="84"></input></td>
                <td class="w400-blue-bdr" id="td-diag-1-5"></td>
                <td class="w400-blue-bdr" id="td-diag-1-5-type">
                    <select id="option-diagonals-mem1-5-type" tabindex="85">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-5" tabindex="100"></input></td>
                <td class="w400-blue-bdr" id="td-diag-2-5"></td>
                <td class="w400-blue-bdr" id="td-diag-2-5-type">
                    <select id="option-diagonals-mem2-5-type" tabindex="101">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-5-reverse" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-5" tabindex="102"></input></td>
            </tr>
            <tr class="h13" style="border-bottom: 2pt solid windowtext;">
                <td class="w400-blue-bdr" id="td-truss-roof-segment6" style="border-left: 2pt solid windowtext;"></td>
                <td class="w400-blue-bdr" id="td-truss-roof-segment6-type">2x6</td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment6" style="border-left: 2pt solid windowtext;"></td>
                <td class="w400-blue-bdr" id="td-truss-floor-segment6-type">2x12</td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-1-6" tabindex="86"></input></td>
                <td class="w400-blue-bdr" id="td-diag-1-6"></td>
                <td class="w400-blue-bdr" id="td-diag-1-6-type">
                    <select id="option-diagonals-mem1-6-type" tabindex="87">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" style="border-left: 2pt solid windowtext;"><input type="checkbox" id="diag-2-6" tabindex="103"></input></td>
                <td class="w400-blue-bdr" id="td-diag-2-6"></td>
                <td class="w400-blue-bdr" id="td-diag-2-6-type">
                    <select id="option-diagonals-mem2-6-type" tabindex="104">
                        <option data-value="2x4" selected="">2x4</option>
                        <option data-value="2x6">2x6</option>
                        <option data-value="2x8">2x8</option>
                        <option data-value="2x10">2x10</option>
                        <option data-value="2x12">2x12</option>
                    </select>
                </td>
                <td class="w400-green-bdr" id="td-diag-2-6-reverse" style="border-right: 2pt solid windowtext;"><input type="checkbox" id="diag-2-reverse-6" tabindex="105"></input></td>
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
            </tr>
            <!-- <tr class="h13">
                <td class="iw400" colspan="11" style="text-align:left;">Loading includes member self weight and roofing materials  w loading = wind on exposed areas</td>
            </tr> -->
            <td colspan="11" rowspan="19" style="position: relative;" class="iw400-bdr">
                <canvas class="px-4" id="canvas-{{ $conditionId }}" style="z-index:2; background:aliceblue" width="900px" height="500px"></canvas>
                <div class="axisCheckBox"><input type="checkbox" id="truss-axis" tabindex="106"><label for="truss-axis">Show axis</label></div>
                <div class="alertModuleFlow" id="truss-module-alert">Warning - Modules extend past ridge</div>
            </td>
            </tbody>
        </table>
    </div>

</div>
</form>