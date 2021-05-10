<div class="row container" style="max-width:100%">
    <div class="col-3">
        <div class="row">
            <div class="col-12">
                <h5 class="mt-2 ml-2">Electrical Subcode Technical Section</h5>
                <h6 class="mt-2 ml-2">After you input the data below then click Update PDF button, don't input to the pdf directly.</h6>
            </div>
            <div class="col-12">
                <div class="permitCtrlBtns mt-2">
                    <button class="mr-4 btn btn-danger" onclick="updateUccF120(3, 'ucc_f120_elec.pdf')">
                        <i class="fa fa-file-pdf mr-1" aria-hidden="true"></i>Update PDF
                    </button>
                    <button class="mr-2 btn btn-info" onclick="savePermit(3, 'ucc_f120_elec.pdf')">
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
                            <td class="iw400-right-bdr">Tel</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-owner-tel" id="txt-owner-tel" tabindex="5" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">E-MAIL</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-owner-email" id="txt-owner-email" tabindex="6" value=""></input></td>
                        </tr>
                        
                        <tr class="h13">
                            <td colspan="2">
                                ELECTRICAL CHARACTERISTICS
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Use Group, Present</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-use-group-present" id="txt-use-group-present" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Use Group, Proposed</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-use-group-proposed" id="txt-use-group-proposed" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Building Occupied</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-building-occupied" id="txt-building-occupied" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Utility Co</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-utility-co" id="txt-utility-co" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Est.Cost of Elec</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-elec-cost" id="txt-elec-cost" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td colspan="2">
                                Technical Site Data
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">KW Transformer/Generator QTY</td>
                            <td class="w400-yellow-bdr">
                                <input type="text" class="permit txt-kw-qty" id="txt-kw-qty" tabindex="6" value=""></input>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">KW Transformer/Generator Size</td>
                            <td class="w400-yellow-bdr">
                                <input type="text" class="permit txt-kw-size" id="txt-kw-size" tabindex="6" value=""></input>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">AMP Service QTY</td>
                            <td class="w400-yellow-bdr">
                                <input type="text" class="permit txt-amp-qty" id="txt-amp-qty" tabindex="6" value=""></input>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">AMP Service Size</td>
                            <td class="w400-yellow-bdr">
                                <input type="text" class="permit txt-amp-size" id="txt-amp-size" tabindex="6" value=""></input>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">AMP Subpanels QTY</td>
                            <td class="w400-yellow-bdr">
                                <input type="text" class="permit txt-subpanels-qty" id="txt-subpanels-qty" tabindex="6" value=""></input>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">AMP Subpanels Size</td>
                            <td class="w400-yellow-bdr">
                                <input type="text" class="permit txt-subpanels-size" id="txt-subpanels-size" tabindex="6" value=""></input>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">AMP Motor Control Center QTY</td>
                            <td class="w400-yellow-bdr">
                                <input type="text" class="permit txt-motor-qty" id="txt-motor-qty" tabindex="6" value=""></input>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">AMP Motor Control Center Size</td>
                            <td class="w400-yellow-bdr">
                                <input type="text" class="permit txt-motor-size" id="txt-motor-size" tabindex="6" value=""></input>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">KW Elec. Sign/Outline Light QTY</td>
                            <td class="w400-yellow-bdr">
                                <input type="text" class="permit txt-light-qty" id="txt-light-qty" tabindex="6" value=""></input>
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">KW Elec. Sign/Outline Light Size</td>
                            <td class="w400-yellow-bdr">
                                <input type="text" class="permit txt-light-size" id="txt-light-size" tabindex="6" value=""></input>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-9">
        <iframe id="permitViewer_3" src="" type="application/pdf" class="pdfViewer" disabled></iframe>
    </div>
</div>