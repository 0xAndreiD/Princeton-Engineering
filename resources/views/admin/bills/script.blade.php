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

var defaultExps = [
    {'code': 'Monthly Base', 'description': 'Monthly base number of jobs', 'price': '', 'quantity': '', 'amount': 0 },
    {'code': 'Above Monthly', 'description': 'Jobs exceeding monthly base', 'price': '', 'quantity': '', 'amount': 0 },
    {'code': 'Shipping', 'description': 'Overnight Shipping', 'price': '', 'quantity': '', 'amount': 0 },
    {'code': 'Reproduction', 'description': 'Photocopying / Printing: Letter size', 'price': 0.1, 'quantity': 1, 'amount': 0.1 },
    {'code': 'Reproduction', 'description': 'Photocopying / Printing: Ledger size', 'price': 0.2, 'quantity': 1, 'amount': 0.2 },
    {'code': 'Travel', 'description': 'Mileage', 'price': 0.5, 'quantity': 1, 'amount': 0.5 },
    {'code': 'Travel', 'description': 'Other', 'price': '', 'quantity': '', 'amount': 0 },
    {'code': 'Letter', 'description': 'Post Installation Letter', 'price': '', 'quantity': '', 'amount': 0 },
    {'code': 'Review', 'description': 'Electric Load Calculator Review', 'price': '', 'quantity': '', 'amount': 0 },
];

var pulldownHandler = function (e, li) {
    if($(this).attr('name') == 'code'){
        let exps = defaultExps.filter(e => e.code == $(li).html());

        let html = '<select name="description" class="form-control editableSel" style="border: 1px solid pink;">';
        exps.forEach(exp => {
            html += `<option>${exp.description}</option>`;
        });
        html += '</select>';
        
        $(li).parents(".expenseRow").find(".descSel").html(html);
        $(li).parents(".expenseRow").find("select[name='description']").editableSelect({ filter: false }).on('select.editable-select', pulldownHandler);
    } else if($(this).attr('name') == 'description'){
        let code = $(li).parents(".expenseRow").find("input[name='code']").val();
        let description = $(li).parents(".expenseRow").find("input[name='description']").val();
        
        let exp = defaultExps.filter(e => e.code == code && e.description == description);
        if(exp.length > 0 && exp[0].price > 0){
            $(li).parents(".expenseRow").find("input[name='price']").val(exp[0].price);
            $(li).parents(".expenseRow").find("input[name='quantity']").val(exp[0].quantity);
            $(li).parents(".expenseRow").find("input[name='amount']").val(exp[0].amount);
            updateAmount();
        }
    }
}

function editBill(obj, id){
    $('#billmodal').modal('toggle');
    $('#expContainer').html('');
    $.post("getBillData", {id: id}, function(result){
        if (result.success && result.data){
            $("#id").val(result.data.id);
            $("#company").val(result.data.companyId);
            $("#issuedAt").val(result.data.issuedAt);
            $("#duedate").val(result.data.duedate);
            $("#issuedFrom").val(result.data.issuedFrom);
            $("#issuedTo").val(result.data.issuedTo);
            $("#jobCount").val(result.data.jobCount);
            let jobIds = JSON.parse(result.data.jobIds);
            $("#jobIds").val(jobIds.join(','));
            $("#amount").val(result.data.amount);
            $("#state").val(result.data.state);
            if(result.data.expenses){
                let expenses = JSON.parse(result.data.expenses);
                expenses.forEach((expense, idx) => {
                    $('#expContainer').append(`<div class="row mb-1 expenseRow">\
                        <div class="col-2 pl-0 pr-0">\
                            <input type="date" class="form-control" name="date" placeholder="" style="border: 1px solid pink;" value="${expense.date}">\
                        </div>\
                        <div class="col-2 pl-0 pr-0">\
                            <select id="code_${idx}" name="code" class="form-control editableSel" style="border: 1px solid pink;" value="${expense.code}">\
                                <option>Monthly Base</option>\
                                <option>Above Monthly</option>\
                                <option>Shipping</option>\
                                <option>Reproduction</option>\
                                <option>Travel</option>\
                                <option>Letter</option>\
                                <option>Review</option>\
                            </select>\
                        </div>\
                        <div class="col-4 pl-0 pr-0 descSel">\
                            <select id="desc_${idx}" name="description" class="form-control editableSel" style="border: 1px solid pink;" value="${expense.description}">\
                            </select>\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="price" placeholder="" style="border: 1px solid pink;" value="${expense.price}" onchange="expAmountUpdate(this)">\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="quantity" placeholder="" style="border: 1px solid pink;" value="${expense.quantity}" onchange="expAmountUpdate(this)">\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="amount" placeholder="" style="border: 1px solid pink;" value="${expense.amount}" onchange="updateAmount()">\
                        </div>\
                        <div class="col-1 pl-0 pr-0 text-center">\
                            <button class="btn btn-danger" onclick="delExpense(this)"><i class="fa fa-fw fa-trash"></i></button>\
                        </div>\
                    </div>`);

                    let exps = defaultExps.filter(e => e.code == expense.code);
                    if(exps.length == 0)
                        $(`#code_${idx}`).append(`<option>${expense.code}</option>`);
                    exps.forEach(exp => {
                        $(`#desc_${idx}`).append(`<option>${exp.description}</option>`);
                    });
                    if(exps.filter(e => e.description == expense.description).length == 0)
                        $(`#desc_${idx}`).append(`<option>${expense.description}</option>`);
                });

                $(".editableSel").editableSelect({ filter: false }).on('select.editable-select', pulldownHandler);
            } else {
                $('#expContainer').html(`<div class="row mb-1 expenseRow">\
                        <div class="col-2 pl-0 pr-0">\
                            <input type="date" class="form-control" name="date" placeholder="" style="border: 1px solid pink;">\
                        </div>\
                        <div class="col-2 pl-0 pr-0">\
                            <select name="code" class="form-control editableSel" style="border: 1px solid pink;">\
                                <option>Monthly Base</option>\
                                <option>Above Monthly</option>\
                                <option>Shipping</option>\
                                <option>Reproduction</option>\
                                <option>Travel</option>\
                                <option>Letter</option>\
                                <option>Review</option>\
                            </select>\
                        </div>\
                        <div class="col-4 pl-0 pr-0 descSel">\
                            <select name="description" class="form-control editableSel" style="border: 1px solid pink;">\
                            </select>\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="price" placeholder="" style="border: 1px solid pink;" onchange="expAmountUpdate(this)">\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="quantity" placeholder="" style="border: 1px solid pink;" onchange="expAmountUpdate(this)">\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="amount" placeholder="" style="border: 1px solid pink;" onchange="updateAmount()">\
                        </div>\
                        <div class="col-1 pl-0 pr-0 text-center">\
                            <button class="btn btn-danger" onclick="delExpense(this)"><i class="fa fa-fw fa-trash"></i></button>\
                        </div>\
                    </div>`);
                $(".editableSel").editableSelect({ filter: false }).on('select.editable-select', pulldownHandler);
            }
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
    data.duedate = $("#duedate").val();
    data.issuedFrom = $("#issuedFrom").val();
    data.issuedTo = $("#issuedTo").val();
    data.jobCount = $("#jobCount").val();
    data.jobIds = $("#jobIds").val().split(',');
    data.amount = $("#amount").val();
    data.state = $("#state").val();
    data.updatePDF = $("#updatePDF")[0].checked;
    data.sendMail = $("#sendMail")[0].checked;

    data.expenses = [];
    $(".expenseRow").each(function(){
        let date = $(this).find("input[name='date']").val();
        let code = $(this).find("input[name='code']").val();
        let description = $(this).find("input[name='description']").val();
        let price = $(this).find("input[name='price']").val();
        let quantity = $(this).find("input[name='quantity']").val();
        let amount = $(this).find("input[name='amount']").val();
        if(description != '')
            data.expenses.push({date: date, code: code, description: description, price: price, quantity: quantity, amount: amount});
    });

    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    $.post("saveBill", data, function(result){
        swal.close();
        $('#billmodal').modal('toggle');
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
    $('#expContainer').html(`<div class="row mb-1 expenseRow">\
                        <div class="col-2 pl-0 pr-0">\
                            <input type="date" class="form-control" name="date" placeholder="" style="border: 1px solid pink;">\
                        </div>\
                        <div class="col-2 pl-0 pr-0">\
                            <select name="code" class="form-control editableSel" style="border: 1px solid pink;" >\
                                <option>Monthly Base</option>\
                                <option>Above Monthly</option>\
                                <option>Shipping</option>\
                                <option>Reproduction</option>\
                                <option>Travel</option>\
                                <option>Letter</option>\
                                <option>Review</option>\
                            </select>\
                        </div>\
                        <div class="col-4 pl-0 pr-0 descSel">\
                            <select name="description" class="form-control editableSel" style="border: 1px solid pink;">\
                            </select>\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="price" placeholder="" style="border: 1px solid pink;" onchange="expAmountUpdate(this)">\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="quantity" placeholder="" style="border: 1px solid pink;" onchange="expAmountUpdate(this)">\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="amount" placeholder="" style="border: 1px solid pink;" onchange="updateAmount()">\
                        </div>\
                        <div class="col-1 pl-0 pr-0 text-center">\
                            <button class="btn btn-danger" onclick="delExpense(this)"><i class="fa fa-fw fa-trash"></i></button>\
                        </div>\
                    </div>`);
    $(".editableSel").editableSelect({ filter: false }).on('select.editable-select', pulldownHandler);
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

function addExpense(){
    $('#expContainer').append(`<div class="row mb-1 expenseRow">\
                        <div class="col-2 pl-0 pr-0">\
                            <input type="date" class="form-control" name="date" placeholder="" style="border: 1px solid pink;">\
                        </div>\
                        <div class="col-2 pl-0 pr-0">\
                            <select name="code" class="form-control editableSel" style="border: 1px solid pink;">\
                                <option>Monthly Base</option>\
                                <option>Above Monthly</option>\
                                <option>Shipping</option>\
                                <option>Reproduction</option>\
                                <option>Travel</option>\
                                <option>Letter</option>\
                                <option>Review</option>\
                            </select>\
                        </div>\
                        <div class="col-4 pl-0 pr-0 descSel">\
                            <select name="description" class="form-control editableSel" style="border: 1px solid pink;">\
                            </select>\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="price" placeholder="" style="border: 1px solid pink;" onchange="expAmountUpdate(this)">\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="quantity" placeholder="" style="border: 1px solid pink;" onchange="expAmountUpdate(this)">\
                        </div>\
                        <div class="col-1 pl-0 pr-0">\
                            <input type="text" class="form-control" name="amount" placeholder="" style="border: 1px solid pink;" onchange="updateAmount()">\
                        </div>\
                        <div class="col-1 pl-0 pr-0 text-center">\
                            <button class="btn btn-danger" onclick="delExpense(this)"><i class="fa fa-fw fa-trash"></i></button>\
                        </div>\
                    </div>`);
    $(".editableSel").editableSelect({ filter: false }).on('select.editable-select', pulldownHandler);
}

function updateAmount(){
    let amount = 0;
    $(".expenseRow").each(function(){
        console.log($(this).find("input[name='amount']").val());
        amount += parseFloat($(this).find("input[name='amount']").val());
    });
    $("#amount").val(amount);
}

function expAmountUpdate(obj){
    let price = parseFloat($(obj).parents(".expenseRow").find("input[name='price']").val());
    let quantity = parseFloat($(obj).parents(".expenseRow").find("input[name='quantity']").val());
    if(price && quantity){
        $(obj).parents(".expenseRow").find("input[name='amount']").val(price * quantity);
        updateAmount();
    }
}

function delExpense(obj){
    $(obj).parents(".expenseRow").remove();
    updateAmount();
}

</script>