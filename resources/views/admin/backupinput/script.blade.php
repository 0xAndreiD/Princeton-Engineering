<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    $("#company").select2({ width: '100%' });
});

function showProcess(isBackup){
    window.backupOrRestore = isBackup;
    $("#fileListBox").val('');
    $("#totalProgress").css('width', '5%');
    $("#progressValue").html("0%");
    $("#loadingAnim").css('display', 'none');
    $("#curFileName").val('');
    window.jsonData = [];
    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    $.ajax({
        url:"getProjectFiles",
        type:'post',
        data:{
            company: $("#company").val(),
            dateFrom: $("#date-from").val(),
            dateTo: $("#date-to").val(),
            idFrom: $("#projectid-from").val(),
            idTo: $("#projectid-to").val()
        },
        success: function(res){
            swal.close();
            if(res.success){
                if(res.data && res.data.length > 0){
                    window.jsonData = res.data;
                    var files = '';
                    for(let i = 0; i < res.data.length; i ++)
                        files += ('/' + res.data[i].file + '\n');
                    $("#fileListBox").val(files);
                    $('#backupModal').modal({backdrop: 'static', keyboard: false});
                    $("#startBtn").attr('disabled', false);
                } else
                    swal.fire({ title: "Warning", text: "Sorry, No projects founds.", icon: "info", confirmButtonText: `OK` });
            }
            else 
                swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
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

function doProcess(data){
    return new Promise((resolve, reject) => {
        if(window.backupOrRestore){
            if(window.ignoreWarnings){
                $.ajax({
                    url:"backupJSON",
                    type:'post',
                    data:{ file: data.file },
                    success: function(res){
                        if(res.success) resolve(true);
                        else resolve(false);
                    },
                    error: function(xhr, status, error) {
                        res = JSON.parse(xhr.responseText);
                        message = res.message;
                        swal.fire({ title: "Error", text: message == "" ? "Error happened while processing." : message, icon: "error", confirmButtonText: `OK` }).then((result) => {
                            resolve(false);
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Warning',
                    text: '/' + data.file + ' on dropbox will be overwritten.',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: `OK`,
                    denyButtonText: `OK for All`,
                    }).then((result) => {
                        if (result.isDenied)
                            window.ignoreWarnings = true;
                        if (result.isConfirmed || result.isDenied) {
                            $.ajax({
                                url:"backupJSON",
                                type:'post',
                                data:{ file: data.file },
                                success: function(res){
                                    if(res.success) resolve(true);
                                    else resolve(false);
                                },
                                error: function(xhr, status, error) {
                                    res = JSON.parse(xhr.responseText);
                                    message = res.message;
                                    swal.fire({ title: "Error", text: message == "" ? "Error happened while processing." : message, icon: "error", confirmButtonText: `OK` }).then((result) => {
                                        resolve(false);
                                    });
                                }
                            });
                        } else
                            resolve(false);
                });
            }
        } else {
            if(window.ignoreWarnings || !data.local){
                $.ajax({
                    url:"restoreJSON",
                    type:'post',
                    data:{ file: data.file },
                    success: function(res){
                        if(res.success) resolve(true);
                        else resolve(false);
                    },
                    error: function(xhr, status, error) {
                        res = JSON.parse(xhr.responseText);
                        message = res.message;
                        swal.fire({ title: "Error", text: message == "" ? "Error happened while processing." : message, icon: "error", confirmButtonText: `OK` }).then((result) => {
                            resolve(false);
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Warning',
                    text: '/' + data.file + ' on server will be overwritten.',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: `OK`,
                    denyButtonText: `OK for All`,
                    }).then((result) => {
                        if (result.isDenied)
                            window.ignoreWarnings = true;
                        if (result.isConfirmed || result.isDenied) {
                            $.ajax({
                                url:"restoreJSON",
                                type:'post',
                                data:{ file: data.file },
                                success: function(res){
                                    if(res.success) resolve(true);
                                    else resolve(false);
                                },
                                error: function(xhr, status, error) {
                                    res = JSON.parse(xhr.responseText);
                                    message = res.message;
                                    swal.fire({ title: "Error", text: message == "" ? "Error happened while processing." : message, icon: "error", confirmButtonText: `OK` }).then((result) => {
                                        resolve(false);
                                    });
                                }
                            });
                        } else
                            resolve(false);
                });
            }
        }
    });
}

async function startProcess(){
    window.ignoreWarnings = false;
    $("#startBtn").attr('disabled', true);
    $("#fileListBox").val('');
    $("#loadingAnim").css('display', 'block');
    for(let i = 0; i < window.jsonData.length; i ++){
        $("#curFileName").val('/' + window.jsonData[i].file);
        let result = await doProcess(window.jsonData[i]);
        if(result)
            $("#fileListBox").val($("#fileListBox").val() + '/' + window.jsonData[i].file + ' -> Done\n');
        else
            $("#fileListBox").val($("#fileListBox").val() + '/' + window.jsonData[i].file + ' -> Failed\n');
        $("#totalProgress").css("width", ((i + 1) * 100 / window.jsonData.length).toFixed(2) + '%');
        $("#progressValue").html(((i + 1) * 100 / window.jsonData.length).toFixed(2) + '%');
    }
    swal.fire({ title: "Done", icon: "success", confirmButtonText: `OK` });
    $("#loadingAnim").css('display', 'none');
}
</script>