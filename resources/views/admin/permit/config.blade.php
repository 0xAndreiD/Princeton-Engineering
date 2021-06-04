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
        <div class="col-8">
            <table cellspacing="0" cellpadding="0" style="border-spacing:0;">
                <thead>
                    <tr>
                        <th style="width:15%; text-align:center" colspan="2">PDF Field</th>
                        <th style="width:15%; text-align:center">Default Value</th>
                        <th style="width:15%; text-align:center" colspan = "2">HTML Field ID</th>
                        <th style="width:20%; text-align:center">Label</th>
                        <th style="width:10%; text-align:center">DBinfo</th>
                    </tr>
                </thead>
                <tbody id="list_form">
    
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
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

        fetch(pdfsrc)
        .then(function(response) {
            return response.arrayBuffer()
        })
        .then(function(data) {
            var list_form = $('#list_form');

            var cnt = 1;
            var field_specs;
            try {
                field_specs = pdfform().list_fields(data);
            } catch (e) {
                on_error(e);
                return;
            }

            for (var field_key in field_specs) {
                var key = field_key.replace(/ /g,"-");
                var row = "<tr class='h13'>";
                row += "<td class='iw400-right-bdr'><input type='checkbox' class='configPermit' id='" + key + "'></input></td>";
                row += "<td class='iw400-right-bdr'><input type='hidden' class='configPermit' value='" + field_key + "'></input>" + field_key + "</td>";
                row += "<td class='w400-green-bdr'>";
                field_specs[field_key].forEach(function(spec, i) {
                    if ((spec.type === 'radio') && spec.options) {
                        var fieldset_el = document.createElement('fieldset');
                        spec.options.forEach(function(ostr) {
                            var label = document.createElement('label');
                            var radio = document.createElement('input');
                            radio.setAttribute('type', 'radio');
                            radio.setAttribute('value', ostr);
                            radio.setAttribute('name', key + '_' + i);
                            radio.setAttribute('data-idx', i);
                            radio.setAttribute('data-key', field_key);
                            radio.setAttribute('class', 'configPermit');
                            label.appendChild(radio);
                            label.appendChild(document.createTextNode(ostr));
                            fieldset_el.appendChild(label);
                        });
                        row += fieldset_el.outerHTML;
                        return;
                    }

                    var input = document.createElement((spec.type === 'select') ? 'select' : 'input');
                    input.setAttribute('data-idx', i);
                    input.setAttribute('data-key', field_key);
                    if (spec.type === 'boolean') {
                        input.setAttribute('type', 'checkbox');
                        input.setAttribute('class', 'configPermit');
                    } else if (spec.type === 'string') {
                        input.setAttribute('value', cnt++);
                        input.setAttribute('class', 'configPermit');
                    } else if ((spec.type === 'select') && spec.options) {
                        spec.options.forEach(function(ostr) {
                            var option_el = document.createElement('option');
                            option_el.appendChild(document.createTextNode(ostr));
                            option_el.setAttribute('value', ostr);
                            option_el.setAttribute('class', 'configPermit');
                            input.appendChild(option_el);
                        });
                    }
                    row += input.outerHTML;
                });
                row += "</td>";
                row += "<td class='w400-green-bdr'><input type='checkbox' id='htmlTagCheck' class='configPermit' ></input></td>";
                row += "<td class='w400-yellow-bdr'><input type='text' id='htmlTagId' class='configPermit' value='" + key + "'></input></td>";
                row += "<td class='w400-yellow-bdr'><input type='text' id='htmlTagLabel' class='configPermit' value='" + field_key + "'></input></td>";
                row += "<td class='w400-yellow-bdr'>\
                            <select id='dbTag' class='configPermit'>\
                                <option value='0' selected>Select</option>\
                                <option value='company_name'>companyInfo.company_name</option>\
                                <option value='company_telno'>companyInfo.company_telno</option>\
                                <option value='company_address'>companyInfo.company_address</option>\
                                <option value='contact_person'>permitInfo.contact_person</option>\
                                <option value='contact_phone'>permitInfo.contact_phone</option>\
                                <option value='FAX'>permitInfo.FAX</option>\
                                <option value='construction_email'>permitInfo.construction_email</option>\
                                <option value='registration'>permitInfo.registration</option>\
                                <option value='exp_date'>permitInfo.exp_date</option>\
                                <option value='EIN'>permitInfo.EIN</option>\
                                <option value='FAX'>permitInfo.FAX</option>\
                            </select>\
                        </td>";

                row += "</tr>";
                list_form.append(row);
            }
        }, function(err) {
            console.log(err);
        });

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

    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.0.0/FileSaver.min.js"></script>
<script src="{{ asset('/js/plugins/minipdf.js') }}"></script>
<script src="{{ asset('/js/plugins/pako.min.js') }}"></script>
<script src="{{ asset('/js/plugins/pdfform.js') }}"></script>

@include('admin.permit.script')
@endsection