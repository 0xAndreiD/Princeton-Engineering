<script>

function chargeNow(obj, id){
    let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
        }
    });

    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    $.post("getPaymentShortInfo", {id: id}, function(res){
        swal.close();
        if(res && res.success){
            toast.fire({
                title: 'Are you sure?',
                text: 'Please confirm payment of ' + $(obj).parents("tr").find("td:eq(6)").text() + ' Payment Due ' + res.duedate,
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-danger m-1',
                    cancelButton: 'btn btn-secondary m-1'
                },
                confirmButtonText: 'Pay from account ending in ' + res.cardnumber,
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
                    swal.fire({ title: "Please wait...", showConfirmButton: false });
                    swal.showLoading();
                    $.post("chargeNow", {id: id}, function(result){
                        swal.close();
                        if (result.success){
                            $("#infos").DataTable().row($(obj).parents("tr")).remove().draw(false);
                            toast.fire('Success', 'The invoice has been paid.', 'success');
                        } else {
                            toast.fire('Error', result.message, 'error');
                        }
                    });
                } else if (result.dismiss === 'cancel') {
                    toast.fire('Cancelled', 'No Payment Made', 'info');
                }
            });
        } else 
            toast.fire('Error', res.message, 'error');
    });
}

</script>