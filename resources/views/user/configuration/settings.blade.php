@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : 'user.layout'))

@section('content')

<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    User Configuration
                </h1>
                <h2 class="h5 text-white-75 mb-0">
                    Set your settings here
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Content -->
<div class="content" style="text-align:left">
    <div class="row">
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
    </div>
</div>

@include('user.configuration.script')
@endsection