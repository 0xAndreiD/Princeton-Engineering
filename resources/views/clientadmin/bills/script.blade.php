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
            if(res.security == false){
                swal.fire({ title: "Please input credit card security code.", input: 'text', confirmButtonText: `OK`, showCancelButton: true }).then((result => {
                    if(result && result.value){
                        doCharge(obj, res.duedate, res.cardnumber, {id: id, code: result.value});
                    } else
                        swal.fire({ title: "Warning", text: "Invalid Security Code. Please try again.", icon: "warning", confirmButtonText: `OK` });
                }));
            } else
                doCharge(obj, res.duedate, res.cardnumber, {id: id});
        } else 
            toast.fire('Error', res.message, 'error');
    });
}

function doCharge(obj, duedate, cardnumber, postdata){
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
        html: 'Please confirm payment of ' + $(obj).parents("tr").find("td:eq(6)").text() + '<br/>Payment Due ' + duedate,
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-danger m-1',
            cancelButton: 'btn btn-secondary m-1'
        },
        confirmButtonText: 'Pay from account ending in ' + cardnumber,
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
            $.post("chargeNow", postdata, function(result){
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
}

</script>