<div class="row">

<div class="col-md-8">
<form id="upload" method="post" action="{{ route('jobFileUpload') }}" enctype="multipart/form-data">
    <div id="drop">
        Drop Here

        <a>Browse</a>
        <input type="file" name="upl" multiple />
        <input type="text" value="{{ $projectId }}" id="uploadJobId" name="uploadProjectId" hidden>
    </div>
    
    <ul></ul>

    <div id="progress">
        <div class="progressBar">
            <div id="progressBar-value" ></div>
        </div>
        <div class="progressLabelPane">
            <span id="uploadStatus"> Ready </span>
            <span id="uploadPercent"> 0% </span>
        </div>
    </div>
</form>
</div>

<div class="col-md-4 filetreePane">
    <div class="fileActions mt-1 mb-2">
        <button class="btn btn-success" onclick="downloadFile()"><i class="fa fa-download"></i> Download</button>
        <button class="btn btn-danger" onclick="delFile()"><i class="fa fa-trash"></i> Delete</button>
    </div>
    <div id="filetree" >
    
    </div>
</div>

</div>