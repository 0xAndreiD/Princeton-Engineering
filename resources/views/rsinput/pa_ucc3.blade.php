<div class="row container" style="max-width:100%">
    <div class="col-3">
        <div class="row">
            <div class="col-12">
                <h5 class="mt-2 ml-2">Application For UCC Building Permit</h5>
                <h6 class="mt-2 ml-2">After you input the data below then click Update PDF button, don't input to the pdf directly.</h6>
            </div>
            <div class="col-12">
                <div class="permitCtrlBtns mt-2">
                    <button class="mr-4 btn btn-danger" onclick="updateUcc3(4, 'PA ucc-3.pdf')">
                        <i class="fa fa-file-pdf mr-1" aria-hidden="true"></i>Update PDF
                    </button>
                    <button class="mr-2 btn btn-info" onclick="savePermit(4, 'PA ucc-3.pdf')">
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
                                Site Information
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Political subdivision</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-political-subdivision" id="txt-political-subdivision" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">County</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-county" id="txt-county" tabindex="6" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td colspan="2">
                                Project Data
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Number of Stories</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-stories" id="txt-character-stories" tabindex="7" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Total floor area(sq. ft.)</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-area" id="txt-character-area" tabindex="9" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Floor area new construction(sq.ft.)</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-newarea" id="txt-character-newarea" tabindex="10" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Floor area of addition (sq. ft.)</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-volume" id="txt-character-volume" tabindex="11" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Floor area of renovated (sq. ft.)</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-character-renovated" id="txt-character-renovated" tabindex="11" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Estimated cost of construction</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-building-cost" id="txt-building-cost" tabindex="11" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td colspan="2">
                                Fees
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">List total sq. ft. of floor area</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-total-floor-area" id="txt-total-floor-area" placeholder="Enter values" tabindex="20" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">List estimated construction cost</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-fee-1" id="txt-fee-1" placeholder="Enter values" tabindex="20" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">pay $1,048.74</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-fee-2" id="txt-fee-2" placeholder="Enter values" tabindex="20" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">pay $336.65 base fee</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-fee-3" id="txt-fee-3" placeholder="Enter values" tabindex="20" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Plus, pay $68.17 per each $1000 of est. construction cost</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-fee-4" id="txt-fee-4" placeholder="Enter values" tabindex="20" value=""></input></td>
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
                                Owner Information
                            </td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">Tel</td>
                            <td class="w400-yellow-bdr"><input type="text" class="permit txt-owner-tel" id="txt-owner-tel" tabindex="5" value=""></input></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-9">
        <iframe id="permitViewer_4" src="" type="application/pdf" class="pdfViewer" disabled></iframe>
    </div>
</div>