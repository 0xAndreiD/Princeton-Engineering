<script>

function delProject(obj, id) {
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
        text: 'You will not be able to recover this project!',
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
            $.post("delProject", {data: id}, function(result){
                if (result){
                    $(obj).parents("tr").remove().draw;
                    toast.fire('Deleted!', 'Project has been deleted.', 'success');
                }
            });

        } else if (result.dismiss === 'cancel') {
            toast.fire('Cancelled', 'Project is safe :)', 'error');
        }
    });
}

var badgeColors = {'info': '#3c90df', 'warning': '#ffb119', 'primary': '#689550', 'danger': '#e04f1a', 'dark': '#343a40', 'secondary': 'rgba(0, 0, 0, 0.33)', 'success': '#82b54b'}

function changeState(jobId, state){
    $.ajax({
        url:"setProjectState",
        type:'post',
        data:{projectId: jobId, state: state},
        success:function(res){
            if (res.success == true) {
                $(`#state_${jobId}`).html(res.stateText);
                $(`#state_${jobId}`).css('background-color', badgeColors[res.stateColor]);
            }
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            message = res.message;
            swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
        }
    });
}

function changeStatus(jobId, status){
    $.ajax({
        url:"setPlanStatus",
        type:'post',
        data:{projectId: jobId, status: status},
        success:function(res){
            if (res.success == true) {
                $(`#status_${jobId}`).html(res.statusText);
                $(`#status_${jobId}`).css('background-color', badgeColors[res.statusColor]);
            }
        },
        error: function(xhr, status, error) {
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