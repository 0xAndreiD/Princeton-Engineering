<div class="row">

<div class="col-md-7 filetreePane">
    <div class="fileActions mt-1 mb-2">
        <button class="btn btn-success" onclick="downloadFile()"><i class="fa fa-download"></i> Download</button>
        @if(Auth::user()->userrole != 4 && Auth::user()->userrole != 6 && Auth::user()->userrole != 7)
        <button class="btn btn-danger" onclick="delFile()" id="deleteBtn" disabled><i class="fa fa-trash"></i> Delete</button>
        <button class="btn btn-warning" onclick="editFile()" id="renameBtn" disabled><i class="fa fa-edit"></i> Rename</button>
        @endif
    </div>
    <div id="filetree" >
    
    </div>
    <input type="hidden" id="disableIN" value="{{ Auth::user()->userrole == 6 || Auth::user()->userrole == 7 ? 1 : 0 }}">
</div>

<div class="col-md-5">
@if(Auth::user()->userrole != 4  && Auth::user()->userrole != 6 && Auth::user()->userrole != 7)
<form id="upload" method="post" action="{{ route('jobFileUpload') }}" enctype="multipart/form-data">
    <div id="drop">
        Drop Here

        <a>Browse</a>
        <input type="file" name="upl" multiple />
        <input type="text" value="{{ $projectId }}" id="uploadJobId" name="uploadProjectId" hidden>
    </div>
    
    <div id="progress">
        <div class="progressBar">
            <div id="progressBar-value" ></div>
        </div>
        <div class="progressLabelPane">
            <span id="uploadStatus"> Ready </span>
            <span id="uploadPercent"> 0% </span>
        </div>
    </div>
    
    <ul></ul>

</form>
@endif
</div>

</div>