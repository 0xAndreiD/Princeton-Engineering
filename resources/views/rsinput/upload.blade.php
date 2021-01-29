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

<div class="block-content block-content-full">
    <div class="table-responsive">
        <table id="filelist" class="table table-bordered table-striped table-vcenter text-center" style="width:100%;">
            <thead>
                <tr>
                    <th style="width:50%">Name</th>
                    <th style="width:30%;">Modified Time</th>
                    <th style="width:20%">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($filelist as $file)
                <tr>
                    <td>{{ $file->name }}</td>
                    <td>{{ date('Y-m-d H:i:s', strtotime('-5 hour',strtotime($file->client_modified))) }}</td>
                    <td>
                        <div class='text-center'>
                            <a href='' class='btn btn-primary'>
                                <i class='fa fa-download'></i>
                            </a>
                            <button type='button' class='btn btn-danger' onclick="delFile(this, '{{ $file->name }}')">
                                <i class='fa fa-trash'></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>