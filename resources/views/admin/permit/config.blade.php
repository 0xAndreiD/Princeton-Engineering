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
    {{-- <div class="row">
        <div class="col-md-3">
            <h2 class="content-heading pt-0">Input Page Settings</h2>
            <div class="form-group">
                <label for="font-size">Font Size</label>
                <select class="form-control" id="font-size" name="example-select" onchange="updateSetting()">
                    <option value="6">6</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="12">12</option>
                    <option value="14">14</option>
                    <option value="16">16</option>
                    <option value="18">18</option>
                    <option value="20">20</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cell-height">Input Cell Height</label>
                <select class="form-control" id="cell-height" name="example-select" onchange="updateSetting()">
                    <option value="12">12</option>
                    <option value="16">16</option>
                    <option value="18">18</option>
                    <option value="20">20</option>
                    <option value="24">24</option>
                    <option value="28">28</option>
                    <option value="32">32</option>
                    <option value="36">36</option>
                    <option value="40">40</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cell-height">Input Cell Font</label>
                <select class="form-control" id="cell-font" name="example-select" onchange="updateSetting()">
                    <option value="Arial">Arial</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Times">Times</option>
                    <option value="Courier New">Courier New</option>
                    <option value="Courier">Courier</option>
                    <option value="Verdana">Verdana</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Palatino">Palatino</option>
                    <option value="Garamond">Garamond</option>
                    <option value="Bookman">Bookman</option>
                    <option value="Tahoma">Tahoma</option>
                    <option value="Trebuchet MS">Trebuchet MS</option>
                    <option value="Arial Black">Arial Black</option>
                    <option value="Impact">Impact</option>
                    <option value="Comic Sans MS">Comic Sans MS</option>
                </select>
            </div>

            <h2 class="content-heading pt-0">Backup Zip Settings</h2>
            <div class="custom-control custom-checkbox custom-control-danger mb-1">
                <input type="checkbox" class="custom-control-input" id="include-folder" name="include-folder" onchange="updateSetting()">
                <label class="custom-control-label" for="include-folder">Include Folder Name on Zip</label>
            </div>
        </div>
        <div class="col-md-9">
            <h2 class="content-heading ml-5 pt-0">Input Element Preview</h2>
            <table cellspacing="0" cellpadding="0" class="ml-5">    
                <colgroup>
                    <col width="80">
                    <col width="300">
                    <col width="70">
                    <col width="70">
                    <col width="70">
                    <col width="70">
                </colgroup>
                <tbody>
                    <tr class="h13">
                        <td class="w400-bdr">A-2</td>
                        <td class="iw400-right-bdr" colspan="2">Roof Average Height</td>
                        <td class="iw400-bdr">ft | in</td>
                        <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" value="30.00" readonly></input></td>
                        <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" value="0.00" readonly></input></td>
                    </tr>
                    <tr class="h13">
                        <td class="w400-bdr">A-3</td>
                        <td class="iw400-right-bdr" colspan="2">Plan View Length of Building Section</td>
                        <td class="iw400-bdr">ft | in</td>
                        <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" value="31.17" readonly></input></td>
                        <td class="w400-yellow-bdr"><input type="text" class="txt-center-align" value="0.00" readonly></input></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div> --}}
    <div class="error">
        
    </div>
    <div id="list_form">

    </div>

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
            var list_form = document.querySelector('#list_form');

            var cnt = 1;
            var field_specs;
            try {
                field_specs = pdfform().list_fields(data);
            } catch (e) {
                on_error(e);
                return;
            }

            for (var field_key in field_specs) {
                var row = document.createElement('div');
                row.appendChild(document.createTextNode(field_key));
                list_form.appendChild(row);
                field_specs[field_key].forEach(function(spec, i) {
                    if ((spec.type === 'radio') && spec.options) {
                        var fieldset_el = document.createElement('fieldset');
                        spec.options.forEach(function(ostr) {
                            var label = document.createElement('label');
                            var radio = document.createElement('input');
                            radio.setAttribute('type', 'radio');
                            radio.setAttribute('value', ostr);
                            radio.setAttribute('name', field_key + '_' + i);
                            radio.setAttribute('data-idx', i);
                            radio.setAttribute('data-key', field_key);
                            label.appendChild(radio);
                            label.appendChild(document.createTextNode(ostr));
                            fieldset_el.appendChild(label);
                        });
                        row.appendChild(fieldset_el);
                        return;
                    }

                    var input = document.createElement((spec.type === 'select') ? 'select' : 'input');
                    input.setAttribute('data-idx', i);
                    input.setAttribute('data-key', field_key);
                    if (spec.type === 'boolean') {
                        input.setAttribute('type', 'checkbox');
                    } else if (spec.type === 'string') {
                        input.setAttribute('value', cnt++);
                    } else if ((spec.type === 'select') && spec.options) {
                        spec.options.forEach(function(ostr) {
                            var option_el = document.createElement('option');
                            option_el.appendChild(document.createTextNode(ostr));
                            option_el.setAttribute('value', ostr);
                            input.appendChild(option_el);
                        });
                    }
                    row.appendChild(input);
                });
            }
        }, function(err) {
            console.log(err);
        });

        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

    });
</script>
<script src="{{ asset('/js/plugins/minipdf.js') }}"></script>
<script src="{{ asset('/js/plugins/pako.min.js') }}"></script>
<script src="{{ asset('/js/plugins/pdfform.js') }}"></script>

@include('admin.permit.script')
@endsection