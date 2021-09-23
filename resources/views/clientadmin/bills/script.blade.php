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
    toast.fire({
        title: 'Are you sure?',
        text: 'You are going to charge real funds!',
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-danger m-1',
            cancelButton: 'btn btn-secondary m-1'
        },
        confirmButtonText: 'Yes, charge it!',
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
                    toast.fire('Success', 'The bill has been charged.', 'success');
                } else {
                    toast.fire('Error', result.message, 'error');
                }
            });
        } else if (result.dismiss === 'cancel') {
            toast.fire('Cancelled', 'The bill is not charged.', 'info');
        }
    });
}

</script>