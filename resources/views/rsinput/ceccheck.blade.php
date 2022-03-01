<div class="modal fade" id="cec-check" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">CEC Module Reset</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="form-group col-6 text-center">
                            <label>Dimension Unit</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="dimension-unit-inch" name="dimension-unit" value="inch" checked>
                                <label class="form-check-label" for="dimension-unit-inch">inch</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="dimension-unit-mm" name="dimension-unit" value="mm">
                                <label class="form-check-label" for="dimension-unit-mm">mm</label>
                            </div>
                        </div>
                        <div class="form-group col-6 text-center">
                            <label>Weight Unit</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="weight-unit-lb" name="weight-unit" value="lb" checked>
                                <label class="form-check-label" for="weight-unit-lb">lb</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="weight-unit-kg" name="weight-unit" value="kg">
                                <label class="form-check-label" for="weight-unit-kg">kg</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-3 text-right p-0">
                            <label style="margin: 3px 0px;">Watts</label>
                        </div>
                        <div class="form-group col-8 text-right">
                            <input type="text" id="cec-module-rating" class="form-control" style="border: 1px solid pink; width: 100%; height: 32px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-3 text-right p-0">
                            <label style="margin: 3px 0px;">Length</label>
                        </div>
                        <div class="form-group col-8 text-right">
                            <input type="text" id="cec-module-length" class="form-control" style="border: 1px solid pink; width: 100%; height: 32px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-3 text-right p-0">
                            <label style="margin: 3px 0px;">Width</label>
                        </div>
                        <div class="form-group col-8 text-right">
                            <input type="text" id="cec-module-width" class="form-control" style="border: 1px solid pink; width: 100%; height: 32px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-3 text-right p-0">
                            <label style="margin: 3px 0px;">Depth</label>
                        </div>
                        <div class="form-group col-8 text-right">
                            <input type="text" id="cec-module-depth" class="form-control" style="border: 1px solid pink; width: 100%; height: 32px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-3 text-right p-0">
                            <label style="margin: 3px 0px;">Weight</label>
                        </div>
                        <div class="form-group col-8 text-right">
                            <input type="text" id="cec-module-weight" class="form-control" style="border: 1px solid pink; width: 100%; height: 32px;">
                        </div>
                        <input type="hidden" id="cec-module-id">
                    </div>
                    {{-- <div class="row">
                        <div class="form-group col-3 text-right p-0">
                            <label style="margin: 3px 0px;">Watts</label>
                        </div>
                        <div class="form-group col-8 text-right">
                            <input type="text" id="cec-module-watts" class="form-control" style="border: 1px solid pink; width: 100%; height: 32px;">
                        </div>
                    </div> --}}
                </div>
                <div class="block-content block-content-full text-right bg-light">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal" onclick="resetCECModule()">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>