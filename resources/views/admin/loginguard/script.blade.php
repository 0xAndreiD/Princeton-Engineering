<script>
function blockAlert(){
    return new Promise((resolve, reject) => {
        swal.fire({
            title: "Warning",
            html: "You are going to block a IP address / device. Continue?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: `Yes`,
            cancelButtonText: `No`,
        })
        .then(( result ) => {
            if ( result.value )
                resolve(true);
            else
                resolve(false);
        });
    });
}

async function toggleBlock(id, blockType){
    if(blockType == 1 && await blockAlert() == false){
        return;
    }
    $.ajax({
        url:"toggleBlock",
        type:'post',
        data:{id: id},
        success:function(res){
            if (res.success == true) {
                if(blockType == 1)
                    swal.fire({ title: "Success!", text: "An IP / Device has been blocked.", icon: "success", confirmButtonText: `OK` });
                else
                    swal.fire({ title: "Success!", text: "An IP / Device has been allowed.", icon: "success", confirmButtonText: `OK` });
                $('#guards').DataTable().ajax.reload();
            } else {
                // error handling
                swal.fire({ title: "Error",
                    text: res.message,
                    icon: "error",
                    confirmButtonText: `OK` });
            }
        },
        error: function(xhr, status, error) {
            $("#page-container").removeClass('side-overlay-o');
            res = JSON.parse(xhr.responseText);
            message = res.message;
            swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
        }
    });
}

function delGuard(id){
    swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this login ip / device!',
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
    })
    .then(( result ) => {
        if ( result.value ) {
            $.ajax({
                url:"deleteGuard",
                type:'post',
                data:{id: id},
                success:function(res){
                    if (res.success == true) {
                        swal.fire({ title: "Deleted!", text: "IP/Device Guard has been deleted.", icon: "success", confirmButtonText: `OK` });
                        $('#guards').DataTable().ajax.reload();
                    } else {
                        // error handling
                        swal.fire({ title: "Error",
                            text: res.message,
                            icon: "error",
                            confirmButtonText: `OK` });
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
        } else if (result.dismiss === 'cancel') {
            swal.fire({ title: "Cancelled", text: "Inverter is safe :)", icon: "info", confirmButtonText: `OK` });
        }
    });
}
</script>