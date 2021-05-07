<div class="row container" style="max-width:100%">
    <div class="col-3">
        <div class="row">
            <div class="col-12">
                <h5 class="mt-2 ml-2">Construction Permit Application</h5>
                <h6 class="mt-2 ml-2">After you input the data below then click Update PDF button, don't input to the pdf directly.</h6>
            </div>
            <div class="col-12">
                <div class="permitCtrlBtns mt-2">
                    <button class="mr-4 btn btn-danger" onclick="updateUccF100(1, 'ucc_f100_cpa.pdf')">
                        <i class="fa fa-file-pdf mr-1" aria-hidden="true"></i>Update PDF
                    </button>
                    <button class="mr-2 btn btn-info" onclick="savePermit(1, 'ucc_f100_cpa.pdf')">
                        <i class="far fa-save mr-1"></i>Save
                    </button>
                </div>
            </div>
            
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
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-block" tabindex="1" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">LOT</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-lot" tabindex="2" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">QUALIFICATION CODE</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-qualifier" tabindex="3" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">PERMIT NO</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-permit-no" tabindex="4" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td colspan="2">
                                Name of Owner in Fee
                            </td>
                        </tr>
                        {{-- <tr class="h13">
                            <td class="iw400-right-bdr">Project Name</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-permit-tel" tabindex="5" value=""></input></td>
                        </tr> --}}
                        <tr class="h13">
                            <td class="iw400-right-bdr">Tel</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-owner-tel" tabindex="5" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">E-MAIL</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-owner-email" tabindex="6" value=""></input></td>
                        </tr>
                        {{-- <tr class="h13">
                            <td colspan="2">
                                Responsible Person in Charge once Work has Begun
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Name</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-responsible-name" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Tel</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-responsible-tel" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">FAX</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-responsible-fax" tabindex="6" value=""></input></td>
                        </tr> --}}
                        <tr class="h13">
                            <td colspan="2">
                                BUILDING/SITE CHARACTERISTICS
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Number of Stories</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-stories" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Height of Structure</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-height" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Area — Largest Floor</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-area" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">New Building Area</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-newarea" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Volume of New Structure</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-volume" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Max. Live Load</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-maxlive" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Max. Occupancy Load</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-maxoccupancy" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Industialized Building State Approved</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-approved" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Industialized Building HUD</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-hud" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Total Land Area Disturbed</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-disturbed" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Flood Hazard Zone</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-flood" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Base Flood Elevation</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-character-base" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Wetlands</td>
                            <td class="w400-green-bdr">
                                <select id="character-wetlands" class="permit" tabindex="10">
                                    <option value="1" selected>YES</option>
                                    <option value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td colspan="2">
                                SUBCODES
                            </td>
                        </tr>
                        
                        <tr class="h13">
                            <td class="iw400-right-bdr">Building Est.Cost</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-building-cost" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Electrical Est.Cost</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-elec-cost" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td colspan="2">
                                DESCRIPTION OF BUILDING USE
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">State Specific Use</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit" id="txt-state-specific" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Use Group, Proposed</td>
                            <td class="w400-green-bdr">
                                <select id="txt-use-group" class="permit" tabindex="10">
                                    <option value="Select Group" selected>Select Group</option>
                                    <option value="R-1">R-1</option>
                                    <option value="R-2">R-2</option>
                                    <option value="R-3">R-3</option>
                                    <option value="R-4">R-4</option>
                                    <option value="R-5">R-5</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-9">
        <iframe id="permitViewer_1" src="" type="application/pdf" class="pdfViewer" disabled></iframe>
    </div>
</div>