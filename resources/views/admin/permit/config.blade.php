@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : 'user.layout'))


@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Permit PDF config
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Config this PDF permit file
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Table Full -->
<!-- Content -->

<div class="content" style="text-align:left">
    <div class="error">
        
    </div>
    <form id="permitForm">
    <div class="row">
        <div class="col-12">
            <table cellspacing="0" cellpadding="0" style="border-spacing:0;">
                <thead>
                    <tr>
                        <th style="width:15%; text-align:center" colspan="2">PDF Field</th>
                        <th style="width:15%; text-align:center">Type</th>
                        <th style="width:10%; text-align:center">Default Value</th>
                        <th style="width:15%; text-align:center" colspan = "2">HTML Field ID</th>
                        <th style="width:15%; text-align:center">Label</th>
                        <th style="width:10%; text-align:center">DBinfo</th>
                        <th style="width:30%; text-align:center">Options</th>
                    </tr>
                </thead>
                <tbody id="list_form">
                    <?php $idx = 0; ?>
                    @foreach($fields as $field)
                    <tr class='h13'>
                        <td class='iw400-right-bdr'>
                            <input type='checkbox' class='configPermit pdfcheck' id="pdfcheck_{{ $idx }}" <?php echo $field['pdfcheck'] == 1 ? 'checked' : '';?>></input>
                        </td>
                        <td class='iw400-right-bdr'>
                            <input type='hidden' class='configPermit' value="{{ $field['pdffield'] }}" id="fieldkey_{{ $idx }}"></input>{{ $field['pdffield'] }}
                        </td>
                        <td class='w400-green-bdr'>
                            <select class='configPermit typesConfig' value="{{ $field['type'] }}" id="fieldtype_{{ $idx }}">
                                <option value="0" <?php echo $field['type'] == 0 ? 'selected' : ''; ?>> Text Input </option>
                                <option value="1" <?php echo $field['type'] == 1 ? 'selected' : ''; ?>> Radio </option>
                                <option value="2" <?php echo $field['type'] == 2 ? 'selected' : ''; ?>> Check Box </option>
                                <option value="3" <?php echo $field['type'] == 3 ? 'selected' : ''; ?>> Select </option>
                                <option value="4" <?php echo $field['type'] == 4 ? 'selected' : ''; ?>> Text Box </option>
                            </select>
                        </td>
                        <td class='w400-green-bdr' id="defaultfield_{{ $idx }}">
                            @if($field['type'] == 0)
                                <input data-idx="{{ $field['idx'] }}" data-key="{{ $field['pdffield'] }}" class="configPermit" value="{{ $field['defaultvalue'] }}" >
                            @elseif($field['type'] == 1)
                                <?php $i = 0; ?>
                                @foreach(explode(",", $field['options']) as $option)
                                <input type="radio" data-idx="{{ $i }}" data-key="{{ $field['pdffield'] }}" name="{{ $field['htmlfield'] }}_{{ $field['idx'] }}" <?php echo $field['defaultvalue'] == $option ? 'checked' : '';?> class="permitRadio"> {{ $option }} </input>
                                <?php $i ++; ?>
                                @endforeach
                                <input type="hidden" class="configPermit" id="{{ $field['htmlfield'] }}_value" value="{{ $field['defaultvalue'] }}">
                            @elseif($field['type'] == 2)
                                <input type="checkbox" data-idx="{{ $field['idx'] }}" data-key="{{ $field['pdffield'] }}" class="configPermit" <?php echo $field['defaultvalue'] == "on" ? 'checked' : '';?>>
                            @elseif($field['type'] == 3)
                                <select data-idx="{{ $field['idx'] }}" data-key="{{ $field['pdffield'] }}" class="configPermit">
                                @foreach(explode(",", $field['options']) as $option)
                                    <option data-idx="{{ $field['idx'] }}" value="{{ $option }}" <?php echo $field['defaultvalue'] == $option ? 'selected' : ''; ?>>{{ $option }}</option>
                                @endforeach
                                </select>
                            @elseif($field['type'] == 4)
                                <textarea data-idx="{{ $field['idx'] }}" data-key="{{ $field['pdffield'] }}" class="configPermit">{{ $field['defaultvalue'] }}</textarea>
                            @endif
                        </td>
                        <td class="w400-green-bdr">
                            <input type='checkbox' class='configPermit' id='htmlcheck_{{ $idx }}' <?php echo $field['pdfcheck'] == 1 ? '' : 'disabled'; ?> <?php echo $field['htmlcheck'] == 1 ? 'checked' : '';?>></input>
                        </td>
                        <td class='w400-yellow-bdr'>
                            <input type='text' class='configPermit' value="{{ $field['htmlfield'] }}"></input>
                        </td>
                        <td class='w400-yellow-bdr'>
                            <input type='text' class='configPermit' value="{{ $field['label'] }}"></input>
                        </td>
                        <td class='w400-yellow-bdr'>
                            <select class='configPermit' value="{{ $field['dbinfo'] }}">
                                <option value='0'>Select</option>
                                <option value='job_projectname' <?php echo $field['dbinfo'] == 'job_projectname' ? 'selected' : ''; ?>>Job Name</option>
                                <option value='job_address' <?php echo $field['dbinfo'] == 'job_address' ? 'selected' : ''; ?>>Job Address</option>
                                <option value='street_address' <?php echo $field['dbinfo'] == 'street_address' ? 'selected' : ''; ?>>Street Address</option>
                                <option value='site_city' <?php echo $field['dbinfo'] == 'site_city' ? 'selected' : ''; ?>>City</option>
                                <option value='state_code' <?php echo $field['dbinfo'] == 'state_code' ? 'selected' : ''; ?>>State Code</option>
                                <option value='zip_code' <?php echo $field['dbinfo'] == 'zip_code' ? 'selected' : ''; ?>>Zip Code</option>
                                <option value='company_name' <?php echo $field['dbinfo'] == 'company_name' ? 'selected' : ''; ?>>Company Name</option>
                                <option value='company_telno' <?php echo $field['dbinfo'] == 'company_telno' ? 'selected' : ''; ?>>Company Telephone</option>
                                <option value='company_address' <?php echo $field['dbinfo'] == 'company_address' ? 'selected' : ''; ?>>Company Address</option>
                                <option value='contact_person' <?php echo $field['dbinfo'] == 'contact_person' ? 'selected' : ''; ?>>Contact Person</option>
                                <option value='contact_phone' <?php echo $field['dbinfo'] == 'contact_phone' ? 'selected' : ''; ?>>Contact Phone</option>
                                <option value='FAX' <?php echo $field['dbinfo'] == 'FAX' ? 'selected' : ''; ?>>FAX</option>
                                <option value='construction_email' <?php echo $field['dbinfo'] == 'construction_email' ? 'selected' : ''; ?>>Construction Email</option>
                                <option value='registration' <?php echo $field['dbinfo'] == 'registration' ? 'selected' : ''; ?>>Registration</option>
                                <option value='exp_date' <?php echo $field['dbinfo'] == 'exp_date' ? 'selected' : ''; ?>>Expiration Date</option>
                                <option value='EIN' <?php echo $field['dbinfo'] == 'EIN' ? 'selected' : ''; ?>>EIN</option>
                                <option value='FAX' <?php echo $field['dbinfo'] == 'FAX' ? 'selected' : ''; ?>>FAX</option>
                                <option value='architect_engineer' <?php echo $field['dbinfo'] == 'architect_engineer' ? 'selected' : ''; ?>>Architect Engineer</option>
                                <option value='architect_address' <?php echo $field['dbinfo'] == 'architect_address' ? 'selected' : ''; ?>>Architect Address</option>
                                <option value='architect_email' <?php echo $field['dbinfo'] == 'architect_email' ? 'selected' : ''; ?>>Architect Email</option>
                                <option value='architect_tel' <?php echo $field['dbinfo'] == 'architect_tel' ? 'selected' : ''; ?>>Architect Tel</option>
                                <option value='architect_fax' <?php echo $field['dbinfo'] == 'architect_fax' ? 'selected' : ''; ?>>Architect Fax</option>
                                <option value='architect_license' <?php echo $field['dbinfo'] == 'architect_license' ? 'selected' : ''; ?>>Architect License</option>
                                <option value='elec_desc_of_work' <?php echo $field['dbinfo'] == 'elec_desc_of_work' ? 'selected' : ''; ?>>Elec Desc of Work</option>
                            </select>
                        </td>
                        <td class='w400-green-bdr'>
                            <input type='text' class='configPermit optionsConfig' value="{{ $field['options'] }}" id="optionsfield_{{ $idx }}"></input>
                        </td>
                        <input type='hidden' class='configPermit' value="{{ $field['idx'] }}" id="idx_{{ $idx }}">
                    </tr>
                    <?php $idx ++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-4">
            <input type="button" name="permit-test" id="permit-test" class="btn btn-hero-info" tabindex="1" value="Fill and download" style="width:100%;"></input>
        </div>
        <div class="col-4">
            <input type="button" name="permit-save" id="permit-save" class="btn btn-hero-primary" tabindex="2" value="Save" style="width:100%;"></input>
        </div>
    </div>
    </form>
</div>

<script src="{{ asset('js/pages/common.js') }}"></script>

<input type="hidden" id="permitId" value="{{$permitId}}"/>
<input type="hidden" id="filename" value="{{$filename}}"/>
<input id="assetPdfLink" value="{{asset('/pdf/')}}" hidden>

<script>
    $(document).ready(function () {
        function on_error(e) {
            console.error(e, e.stack);  // eslint-disable-line no-console
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(e.message));
            document.querySelector('.error').appendChild(div);
        }

        var filename = $("#filename").val();

        var pdfsrc = $("#assetPdfLink").val() + '/' + filename;
        var script = $("<script>");

        @if(count($fields) == 0)
        fetch(pdfsrc)
        .then(function(response) {
            return response.arrayBuffer()
        })
        .then(function(data) {
            var list_form = $('#list_form');

            // var cnt = 1;
            var field_specs;
            try {
                field_specs = pdfform().list_fields(data);
            } catch (e) {
                on_error(e);
                return;
            }

            var idx = 0;
            for (var field_key in field_specs) {
                var options = [];
                var key = field_key.replace(/ /g,"-");
                var row = "<tr class='h13'>";
                row += "<td class='iw400-right-bdr'><input type='checkbox' class='configPermit pdfcheck' id='pdfcheck_" + idx + "' checked></input></td>";
                row += "<td class='iw400-right-bdr'><input type='hidden' class='configPermit' value='" + field_key + "' id='fieldkey_" + idx + "'></input>" + field_key + "</td>";
                row += "<td class='w400-green-bdr'>";
                
                var fieldtype = 0;
                var defaulthtml = "";
                var fieldidx;
                field_specs[field_key].forEach(function(spec, i) {
                    fieldidx = i;
                    if ((spec.type === 'radio') && spec.options) {
                        fieldtype = 1;
                        var fieldset_el = document.createElement('fieldset');
                        let defaultoption = "", checked = false;
                        spec.options.forEach(function(ostr, j) {
                            options.push(ostr);
                            var label = document.createElement('label');
                            var radio = document.createElement('input');
                            radio.setAttribute('type', 'radio');
                            radio.setAttribute('value', ostr);
                            radio.setAttribute('name', key + '_' + i);
                            radio.setAttribute('data-idx', j);
                            radio.setAttribute('data-key', field_key);
                            radio.setAttribute('data-valuekey', key);
                            radio.setAttribute('class', 'permitRadio');
                            if(!check){
                                check = true;
                                defaultoption = ostr;
                                radio.setAttribute('checked', true);
                            }
                            label.appendChild(radio);
                            label.appendChild(document.createTextNode(ostr));
                            fieldset_el.appendChild(label);
                        });
                        defaulthtml += fieldset_el.outerHTML;
                        defaulthtml += "<input class='configPermit' value='" + defaultoption + "' id='" + key + "_value' type='hidden'>";
                        return;
                    }

                    var input = document.createElement((spec.type === 'select') ? 'select' : 'input');
                    input.setAttribute('data-idx', i);
                    input.setAttribute('data-key', field_key);
                    if (spec.type === 'boolean') {
                        fieldtype = 2;
                        input.setAttribute('type', 'checkbox');
                        input.setAttribute('class', 'configPermit');
                    } else if (spec.type === 'string') {
                        fieldtype = 0;
                        input.setAttribute('value', '');
                        input.setAttribute('class', 'configPermit');
                    } else if ((spec.type === 'select') && spec.options) {
                        fieldtype = 3;
                        let check = false;
                        spec.options.forEach(function(ostr) {
                            options.push(ostr);
                            if(!check)
                                input.setAttribute('value', ostr);
                            var option_el = document.createElement('option');
                            option_el.appendChild(document.createTextNode(ostr));
                            option_el.setAttribute('value', ostr);
                            input.appendChild(option_el);
                        });
                        input.setAttribute('class', 'configPermit');
                    }
                    defaulthtml += input.outerHTML;
                });

                row += "<select class='configPermit typesConfig' value='" + fieldtype + "' id='fieldtype_" + idx + "'>\
                            <option value='0' " + (fieldtype == 0 ? "selected" : "") + "> Text Input </option>\
                            <option value='1' " + (fieldtype == 1 ? "selected" : "") + "> Radio </option>\
                            <option value='2' " + (fieldtype == 2 ? "selected" : "") + "> Check Box </option>\
                            <option value='3' " + (fieldtype == 3 ? "selected" : "") + "> Select </option>\
                            <option value='4' " + (fieldtype == 4 ? "selected" : "") + "> Text Box </option>\
                        </select>";
                row += "</td>";
                row += "<td class='w400-green-bdr' id='defaultfield_" + idx + "'>";
                row += defaulthtml;
                row += "</td>";
                row += "<td class='w400-green-bdr'><input type='checkbox' class='configPermit' id='htmlcheck_" + idx + "' checked></input></td>";
                row += "<td class='w400-yellow-bdr'><input type='text' class='configPermit' value='" + key + "'></input></td>";
                row += "<td class='w400-yellow-bdr'><input type='text' class='configPermit' value='" + field_key + "'></input></td>";
                row += "<td class='w400-yellow-bdr'>\
                            <select class='configPermit'>\
                                <option value='0' selected>Select</option>\
                                <option value='job_projectname'>Owner Name</option>\
                                <option value='job_address'>Owner Address</option>\
                                <option value='street_address'>Street Address</option>\
                                <option value='site_city'>City</option>\
                                <option value='state_code'>State Code</option>\
                                <option value='zip_code'>Zip Code</option>\
                                <option value='site_address'>Site Address</option>\
                                <option value='company_name'>Company Name</option>\
                                <option value='company_telno'>Company Telephone</option>\
                                <option value='company_address'>Company Address</option>\
                                <option value='contact_person'>Contact Person</option>\
                                <option value='contact_phone'>Contact Phone</option>\
                                <option value='FAX'>FAX</option>\
                                <option value='construction_email'>Construction Email</option>\
                                <option value='registration'>Registration</option>\
                                <option value='exp_date'>Expiration Date</option>\
                                <option value='EIN'>EIN</option>\
                                <option value='FAX'>FAX</option>\
                                <option value='architect_engineer'>Architect Engineer</option>\
                                <option value='architect_address'>Architect Address</option>\
                                <option value='architect_email'>Architect Email</option>\
                                <option value='architect_tel'>Architect Tel</option>\
                                <option value='architect_fax'>Architect Fax</option>\
                                <option value='architect_license'>Architect License</option>\
                                <option value='elec_desc_of_work'>Elec Desc of Work</option>\
                            </select>\
                        </td>";
                row += "<td class='w400-green-bdr'>\
                            <input type='text' class='configPermit optionsConfig' value='" + options.join(",") + "' id='optionsfield_" + idx + "'></input>\
                        </td>";
                row += "<input type='hidden' class='configPermit' value='" + fieldidx + "' id='idx_" + idx + "'>";
                row += "</tr>";
                list_form.append(row);
                idx ++;
            }

            setPdfCheckHandler();
            setRadioHandler();
            setTypesHandler();
            setOptionsHandler();
        }, function(err) {
            console.log(err);
        });
        @endif

        var submitData = async function(e) {
            e.preventDefault();
            e.stopPropagation(); 
            var i = 0;
            var alldata = [];

            $('#permitForm .h13').each(function(i) { 
                alldata[i] = [];
                $(this).find('.configPermit').each(function(j) {
                    if ($(this).attr('type') == 'checkbox'){
                        alldata[i][j] = this.checked ? $(this).val() : "";
                    } else                         
                        alldata[i][j] = $(this).val();
                });
            });

            var message = '';
            swal.fire({ title: "Please wait...", showConfirmButton: false });
            swal.showLoading();
            $.ajax({
                url:"submitPermitConfig",
                type:'post',
                data:{data: alldata, filename: filename},
                success:function(res){
                    swal.close();
                    if (res.status == true) {
                        message = 'Permit Info saved successfully!';
                        swal.fire({
                            title: "Success",
                            text: message,
                            icon: "success",
                            showCancelButton: true,
                        })
                    } else {
                        // error handling
                        swal.fire({ title: "Error",
                            text: "Error happened while processing. Please try again later.",
                            icon: "error",
                            confirmButtonText: `OK` });
                    }
                },
                error: function(xhr, status, error) {
                    swal.close();
                    res = JSON.parse(xhr.responseText);
                    message = res.message;
                    swal.fire({ title: "Error",
                            text: message == "" ? "Error happened while processing. Please try again later." : message,
                            icon: "error",
                            confirmButtonText: `OK` });
                }
            });
        }
        
        $('#permit-save').click(function(e) {
            submitData(e);
        });

        var setPdfCheckHandler = function() {
            $('.pdfcheck').on('click', function() {
                let id = $(this).attr('id').split("pdfcheck_").join("htmlcheck_");
                if($(this)[0].checked)
                    $('#' + id).attr('disabled', false);
                else
                    $('#' + id).attr('disabled', true);
            });
        }

        var setRadioHandler = function() {
            $('.permitRadio').on('click', function() {
                $('#' + $(this).attr('data-valuekey') + '_value').val($(this).val());
            });
        }

        var setTypesHandler = function(){
            $('.typesConfig').on('change', function() {
                let index = $(this).attr("id").split("fieldtype_").join("");
                let idx = $("#idx_" + index).val();
                let optionsid = $(this).attr("id").split("fieldtype_").join("optionsfield_");
                let defaultid = $(this).attr("id").split("fieldtype_").join("defaultfield_");
                let field_key = $("#" + $(this).attr("id").split("fieldtype_").join("fieldkey_")).val();
                let key = field_key.replace(/ /g,"-");
                let options = $("#" + optionsid).val().split(",");
                
                if($(this).val() == "0"){
                    var input = document.createElement('input');
                    input.setAttribute('data-idx', idx);
                    input.setAttribute('data-key', field_key);
                    input.setAttribute('value', '');
                    input.setAttribute('class', 'configPermit');
                    $("#" + defaultid).html(input.outerHTML);
                } else if($(this).val() == "1"){
                    let html = "", defaultoption = "", check = false;
                    var fieldset_el = document.createElement('fieldset');
                    options.forEach(function(ostr, j) {
                        var label = document.createElement('label');
                        var radio = document.createElement('input');
                        radio.setAttribute('type', 'radio');
                        radio.setAttribute('value', ostr);
                        radio.setAttribute('name', key + "_" + idx);
                        radio.setAttribute('data-idx', j);
                        radio.setAttribute('data-key', field_key);
                        radio.setAttribute('data-valuekey', key);
                        radio.setAttribute('class', 'permitRadio');
                        if(!check){
                            check = true;
                            defaultoption = ostr;
                            radio.setAttribute('checked', true);
                        }
                        label.appendChild(radio);
                        label.appendChild(document.createTextNode(ostr));
                        fieldset_el.appendChild(label);
                    });
                    html += fieldset_el.outerHTML;
                    html += "<input class='configPermit' value='" + defaultoption + "' id='" + key + "_value' type='hidden'>";
                    $("#" + defaultid).html(html);
                    setRadioHandler();
                } else if($(this).val() == "2"){
                    var input = document.createElement('input');
                    input.setAttribute('data-idx', idx);
                    input.setAttribute('data-key', field_key);
                    input.setAttribute('type', 'checkbox');
                    input.setAttribute('class', 'configPermit');
                    $("#" + defaultid).html(input.outerHTML);
                } else if($(this).val() == "3"){
                    var input = document.createElement('select');
                    input.setAttribute('data-idx', idx);
                    input.setAttribute('data-key', field_key);
                    let check = false;
                    options.forEach(function(ostr) {
                        if(!check)
                            input.setAttribute('value', ostr);
                        var option_el = document.createElement('option');
                        option_el.appendChild(document.createTextNode(ostr));
                        option_el.setAttribute('value', ostr);
                        input.appendChild(option_el);
                    });
                    input.setAttribute('class', 'configPermit');
                    $("#" + defaultid).html(input.outerHTML);
                } else if($(this).val() == "4"){
                    var textarea = document.createElement('textarea');
                    textarea.setAttribute('data-idx', idx);
                    textarea.setAttribute('data-key', field_key);
                    textarea.setAttribute('class', 'configPermit');
                    textarea.appendChild(document.createTextNode(''));
                    $("#" + defaultid).html(textarea);
                }
            });
        }

        var setOptionsHandler = function(){
            $('.optionsConfig').on('change', function() {
                let index = $(this).attr("id").split("optionsfield_").join("");
                let idx = $("#idx_" + index).val();
                let typeid = $(this).attr("id").split("optionsfield_").join("fieldtype_");
                let defaultid = $(this).attr("id").split("optionsfield_").join("defaultfield_");
                let field_key = $("#" + $(this).attr("id").split("optionsfield_").join("fieldkey_")).val();
                let key = field_key.replace(/ /g,"-");
                let options = $(this).val().split(",");
                
                if($("#" + typeid).val() == "1"){
                    let html = "", defaultoption = "", check = false;
                    var fieldset_el = document.createElement('fieldset');
                    options.forEach(function(ostr, j) {
                        var label = document.createElement('label');
                        var radio = document.createElement('input');
                        radio.setAttribute('type', 'radio');
                        radio.setAttribute('value', ostr);
                        radio.setAttribute('name', key + "_" + idx);
                        radio.setAttribute('data-idx', j);
                        radio.setAttribute('data-key', field_key);
                        radio.setAttribute('data-valuekey', key);
                        radio.setAttribute('class', 'permitRadio');
                        if(!check){
                            check = true;
                            defaultoption = ostr;
                            radio.setAttribute('checked', true);
                        }
                        label.appendChild(radio);
                        label.appendChild(document.createTextNode(ostr));
                        fieldset_el.appendChild(label);
                    });
                    html += fieldset_el.outerHTML;
                    html += "<input class='configPermit' value='" + defaultoption + "' id='" + key + "_value' type='hidden'>";
                    $("#" + defaultid).html(html);
                    setRadioHandler();
                } else if($("#" + typeid).val() == "3"){
                    var input = document.createElement('select');
                    input.setAttribute('data-idx', idx);
                    input.setAttribute('data-key', field_key);
                    let check = false;
                    options.forEach(function(ostr) {
                        if(!check)
                            input.setAttribute('value', ostr);
                        var option_el = document.createElement('option');
                        option_el.appendChild(document.createTextNode(ostr));
                        option_el.setAttribute('value', ostr);
                        input.appendChild(option_el);
                    });
                    input.setAttribute('class', 'configPermit');
                    $("#" + defaultid).html(input.outerHTML);
                }
            });
        }

        $('#permit-test').click(function(e) {
            fetch(pdfsrc)
            .then(function(response) {
                return response.arrayBuffer()
            })
            .then(function(data) {
                fill(data);
            }, function(err) {
                console.log(err);
            });
        });

        // Example of filling out fields
        function fill(buf) {
            var list_form = document.querySelector('#list_form');
            var fields = {};
            list_form.querySelectorAll('input,select').forEach(function(input) {
                if ((input.getAttribute('type') === 'radio') && !input.checked) {
                    return;
                }

                var key = input.getAttribute('data-key');
                if (!fields[key]) {
                    fields[key] = [];
                }
                var index = parseInt(input.getAttribute('data-idx'), 10);
                var value = (input.getAttribute('type') === 'checkbox') ? input.checked : input.value;
                fields[key][index] = value;
            });

            var filled_pdf; // Uint8Array
            try {
                filled_pdf = pdfform().transform(buf, fields);
            } catch (e) {
                return on_error(e);
            }

            var blob = new Blob([filled_pdf], {type: 'application/pdf'});
            saveAs(blob, 'test.pdf');
        }
        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        setPdfCheckHandler();
        setRadioHandler();
        setTypesHandler();
        setOptionsHandler();
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.0.0/FileSaver.min.js"></script>
<script src="{{ asset('/js/plugins/minipdf.js') }}"></script>
<script src="{{ asset('/js/plugins/pako.min.js') }}"></script>
<script src="{{ asset('/js/plugins/pdfform.js') }}"></script>

@include('admin.permit.script')
@endsection