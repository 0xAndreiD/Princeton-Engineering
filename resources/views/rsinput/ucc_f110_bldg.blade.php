<div class="row container" style="max-width:100%">
    <div class="col-3">
        <div class="row">
            <div class="col-12">
                <h5 class="mt-2 ml-2">Building Subcode Technical Section</h5>
                <h6 class="mt-2 ml-2">After you input the data below then click Update PDF button, don't input to the pdf directly.</h6>
            </div>
            @if(Auth::user()->userrole != 4)
            <div class="col-12">
                <div class="permitCtrlBtns mt-2">
                    <button class="mr-4 btn btn-danger" onclick="updateUccF110(2, 'ucc_f110_bldg.pdf')">
                        <i class="fa fa-file-pdf mr-1" aria-hidden="true"></i>Update PDF
                    </button>
                    <button class="mr-2 btn btn-info" onclick="savePermit(2, 'ucc_f110_bldg.pdf')">
                        <i class="far fa-save mr-1"></i>Save
                    </button>
                </div>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-12">
                <table id="permit-info-table" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
                    <tbody>
                        <tr class="h13">
                            <td><div style="overflow:hidden"></td>
                            <td><div style="overflow:hidden"></td>
                        </tr>
                        <tr class="h13">
                            <td colspan="2">
                                Head Info
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">BLOCK</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-block" id="txt-block" tabindex="1" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">LOT</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-lot" id="txt-lot" tabindex="2" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">QUALIFICATION CODE</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-qualifier" id="txt-qualifier" tabindex="3" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td colspan="2">
                                Name of Owner in Fee
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Owner's Tel</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-owner-tel" id="txt-owner-tel" tabindex="5" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Owner's EMAIL</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-owner-email" id="txt-owner-email" tabindex="6" value=""></input></td>
                        </tr>
                        
                        <tr class="h13">
                            <td colspan="2">
                                BUILDING/SITE CHARACTERISTICS
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Use Group, Present</td>
                            <td class="w400-green-bdr">
                                <select id="txt-use-group-present" class="permit txt-use-group-present" tabindex="10">
                                    <option value="Select Group" selected>Select Group</option>
                                    <option value="R-1">R-1</option>
                                    <option value="R-2">R-2</option>
                                    <option value="R-3">R-3</option>
                                    <option value="R-4">R-4</option>
                                    <option value="R-5">R-5</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Use Group, Proposed</td>
                            <td class="w400-green-bdr">
                                <select id="txt-use-group-proposed" class="permit txt-use-group-proposed" tabindex="10">
                                    <option value="Select Group" selected>Select Group</option>
                                    <option value="R-1">R-1</option>
                                    <option value="R-2">R-2</option>
                                    <option value="R-3">R-3</option>
                                    <option value="R-4">R-4</option>
                                    <option value="R-5">R-5</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">No of Stories</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-stories" id="txt-character-stories" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Height of Structure</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-height" id="txt-character-height" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Area — Largest Floor</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-area" id="txt-character-area" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">New Building Area</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-newarea" id="txt-character-newarea" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Volume of New Structure</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-volume" id="txt-character-volume" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Max. Live Load</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-maxlive" id="txt-character-maxlive" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Max. Occupancy Load</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-maxoccupancy" id="txt-character-maxoccupancy" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Constr.Class Present</td>
                            <td class="w400-green-bdr">
                                <select id="txt-const-group-present" class="permit txt-const-group-present" tabindex="10">
                                    <option value="Select Group" selected>Select Group</option>
                                    <option value="R-1">R-1</option>
                                    <option value="R-2">R-2</option>
                                    <option value="R-3">R-3</option>
                                    <option value="R-4">R-4</option>
                                    <option value="R-5">R-5</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Constr.Class Proposed</td>
                            <td class="w400-green-bdr">
                                <select id="txt-const-group-proposed" class="permit txt-const-group-proposed" tabindex="10">
                                    <option value="Select Group" selected>Select Group</option>
                                    <option value="R-1">R-1</option>
                                    <option value="R-2">R-2</option>
                                    <option value="R-3">R-3</option>
                                    <option value="R-4">R-4</option>
                                    <option value="R-5">R-5</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Industialized Building State Approved</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-approved" id="txt-character-approved" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Industialized Building HUD</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-hud" id="txt-character-hud" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Est.Cost of New Bldg</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-building-cost" id="txt-building-cost" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Est.Cost of Rehabilitation</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-rehabil-cost" id="txt-rehabil-cost" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Print name here</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-print-name" id="txt-print-name" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Technical Site Data (Desc of Work)</td>
                            <td class="w400-yellow-bdr" style="height:200px">
                                <textarea type="text" class="permit txt-site-data" style="height:97%;" id="txt-site-data" tabindex="6" value=""></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-9">
        <iframe id="permitViewer_2" src="" type="application/pdf" class="pdfViewer" disabled></iframe>
    </div>
</div>