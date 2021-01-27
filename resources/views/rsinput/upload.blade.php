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