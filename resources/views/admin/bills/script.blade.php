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

function markAsPaid(obj, id){
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
        text: 'You are going to mark the jobs as paid!',
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-danger m-1',
            cancelButton: 'btn btn-secondary m-1'
        },
        confirmButtonText: 'Yes, do it!',
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
            $.post("markAsPaid", {id: id}, function(result){
                swal.close();
                if (result.success){
                    $("#infos").DataTable().row($(obj).parents("tr")).remove().draw(false);
                    toast.fire('Success', 'The jobs are marked as paid.', 'success');
                } else {
                    toast.fire('Error', result.message, 'error');
                }
            });
        } else if (result.dismiss === 'cancel') {
            toast.fire('Cancelled', 'The action is cancelled.', 'info');
        }
    });
}

function delBill(obj, id){
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
        text: 'You are going to delete the bill!',
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
            swal.fire({ title: "Please wait...", showConfirmButton: false });
            swal.showLoading();
            $.post("delBill", {id: id}, function(result){
                swal.close();
                if (result.success){
                    $("#infos").DataTable().row($(obj).parents("tr")).remove().draw(false);
                    toast.fire('Success', 'The bill is deleted.', 'success');
                } else {
                    toast.fire('Error', result.message, 'error');
                }
            });
        } else if (result.dismiss === 'cancel') {
            toast.fire('Cancelled', 'The bill is safe.', 'info');
        }
    });
}

function changeState(obj, id, state){
    $.post("setBillState", {id: id, state: state}, function(result){
        if (result.success){
            $("#infos").DataTable().row($(obj).parents("tr")).remove().draw(false);
        }
    });
}

function editBill(obj, id){
    $('#billmodal').modal('toggle');
    $.post("getBillData", {id: id}, function(result){
        if (result.success && result.data){
            $("#id").val(result.data.id);
            $("#company").val(result.data.companyId);
            $("#issuedAt").val(result.data.issuedAt);
            $("#issuedFrom").val(result.data.issuedFrom);
            $("#issuedTo").val(result.data.issuedTo);
            $("#jobCount").val(result.data.jobCount);
            let jobIds = JSON.parse(result.data.jobIds);
            $("#jobIds").val(jobIds.join(','));
            $("#amount").val(result.data.amount);
            $("#state").val(result.data.state);
        }
    });
}

function saveBill(){
    let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
        }
    });
    
    let data = {};
    data.id = $("#id").val();
    data.companyId = $("#company").val();
    data.issuedAt = $("#issuedAt").val();
    data.issuedFrom = $("#issuedFrom").val();
    data.issuedTo = $("#issuedTo").val();
    data.jobCount = $("#jobCount").val();
    data.jobIds = $("#jobIds").val().split(',');
    data.amount = $("#amount").val();
    data.state = $("#state").val();
    data.updatePDF = $("#updatePDF")[0].checked;

    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    $.post("saveBill", data, function(result){
        swal.close();
        if (result.success){
            $("#infos").DataTable().draw(false);
            toast.fire('Success', 'The bill is saved.', 'success');
        } else {
            toast.fire('Error', result.message, 'error');
        }
    });
}

function addBill(){
    $('select#company').val("1").trigger('change');
    $("#issuedAt").val('');
    $("#issuedFrom").val('');
    $("#issuedTo").val('');
    $("#jobCount").val('');
    $("#jobIds").val('');
    $("#amount").val('');
    $("#state").val('0').trigger('change');
    $("#updatePDF")[0].checked = true;

    $('#billmodal').modal('toggle');
}

function billNow(){
    let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
        }
    });

    let companyIds = [];
    
    $(".companychecker").each(function() {
        if($(this)[0].checked == true)
            companyIds.push($(this).attr('data-id'));
    });
    
    if(companyIds.length){
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        $.post("billNow", {companyIds: companyIds, issuedFrom: $("#customIssuedFrom").val(), issuedTo: $("#customIssuedTo").val()}, function(res){
            swal.close();

            if(res && res.success){
                toast.fire('Done', "Bills are created for selected clients.", 'success');
                $("#infos").DataTable().draw(false);
            }
            else 
                toast.fire('Error', res.message, 'error');
        });
    } else
        toast.fire('Warning', 'Please select any company.', 'warning');
}

</script>