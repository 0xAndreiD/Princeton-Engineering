<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
});

var table = $('#files').DataTable({
    "responsive": true,
    "orderCellsTop": true,
    "pageLength" : 50,
    "order": [[ 1, "desc" ]]
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
</script>