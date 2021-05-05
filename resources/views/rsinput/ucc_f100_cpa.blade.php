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
                            <td class="iw400-right-bdr">BLOCK</td>
                            <td class="w400-yellow-bdr"><input type="text" id="txt-block" tabindex="1" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">LOT</td>
                            <td class="w400-yellow-bdr"><input type="text" id="txt-lot" tabindex="2" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">QUALIFICATION CODE</td>
                            <td class="w400-yellow-bdr"><input type="text" id="txt-qualifier" tabindex="3" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">ADDRESS (SITE)</td>
                            <td class="w400-yellow-bdr"><input type="text" id="txt-owner-address-combined" tabindex="4" value=""></input></td>
                        </tr>
                        <tr class="h13">
                            <td class="iw400-right-bdr">PERMIT NO</td>
                            <td class="w400-yellow-bdr"><input type="text" id="txt-permit-no" tabindex="5" value=""></input></td>
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