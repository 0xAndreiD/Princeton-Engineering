<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    
    var table = $('#files').DataTable({
        "responsive": true,
        "orderCellsTop": true,
        "pageLength" : 50,
        "order": [[ 1, "desc" ]]
    });

    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var min = new Date($('#date-from').val());
            var max = new Date($('#date-to').val());
            max.setDate(max.getDate() + 1);
            var createdDate = new Date(data[1]);
            if (min == null && max == null) return true;
            if (min == null && createdDate <= max) return true;
            if (max == null && createdDate >= min) return true;
            if (createdDate <= max && createdDate >= min) return true;
            return false;
        }
    );

    $('#date-from, #date-to').change(function () {
        var t = $('#files').DataTable();
        t.draw();
    });
});

function updateSetting(){
    var days = [];
    var weekdays = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    if($("#backup-everyday")[0].checked){
        $("#weekDays").addClass("disabledPane");
        days.push("-1");
    } else {
        $("#weekDays").removeClass("disabledPane");
        for(let i = 0; i < weekdays.length; i ++)
            if($(`#weekday-${weekdays[i]}`)[0].checked)
                days.push(i.toString());
    }
    $.ajax({
        url:"updateDBSetting",
        type:'post',
        data:{ setting: days.join(",") },
        success: function(res){
            if(!res.success)
                swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            message = res.message;
            swal.fire({ title: "Error", text: message == "" ? "Error happened while processing." : message, icon: "error", confirmButtonText: `OK` });
        }
    });
}

function backupNow(){
    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    $.ajax({
        url:"manualDBBackup",
        type:'post',
        success: function(res){
            swal.close();
            if(res.success){
                swal.fire({ title: "Done", text: "DB Backup Finished.", icon: "success", confirmButtonText: `OK` });
                var t = $('#files').DataTable();
                t.row.add([res.filename, res.modified, "<button type='button' class='btn btn-warning' style='margin-right: 5px;' onclick='doRestore(this, '" + res.filename + "')'><i class='fa fa-database'></i></button><button type='button' class='btn btn-danger' onclick='delBackup(this, '" + res.filename + ")'><i class='fa fa-trash'></i></button>"]).draw();
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

function delBackup(obj, filename){
    let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
        }
    });
    toast.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this file!',
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-danger m-1',
            cancelButton: 'btn btn-secondary m-1'
        },
        confirmButtonText: 'Yes, delete it!',
        html: false,
        preConfirm: e => {
            return new Promise(resolve => {
                setTimeout(() => {
                    resolve();
                }, 50);
            });
        }
    }).then(result => {
        if (result.value) {
            $.post("delBackup", {filename: filename}, function(result){
                if (result.success){
                    $("#filelist").DataTable().row($(obj).parents("tr")).remove().draw(false);
                    toast.fire('Deleted!', 'File has been deleted.', 'success');
                } else {
                    toast.fire('Error', result.message, 'error');
                }
            });

        } else if (result.dismiss === 'cancel') {
            toast.fire('Cancelled', 'File is safe :)', 'error');
        }
    });
}
</script>