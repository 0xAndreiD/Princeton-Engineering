<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    Dashmix.helpers('validation');
    var validateObj = $('#profileForm').validate({rules: {
        'name': {
            required: true,
            minlength: 3
        },
        'email': {
            required: true,
            email: true
        },
        'telno': {
            required: true,
            phoneUS: true
        },
        'digits': {
            required: true,
            digits: true
        },
        'number': {
            required: true,
            number: true
        },
    },
    messages: {
        'name': {
            required: 'Please enter a name',
            minlength: 'Your name must consist of at least 3 characters'
        },
        'email': 'Please enter a valid email address',
        'telno': 'Please enter a US phone!',
        'digits': 'Please enter only digits!',
        'number': 'Please enter a number!',
    },
    submitHandler: function(){
        updateCompany();
    }
    });

    $('#permitForm').validate({rules: {
        'state': {
            required: true,
        },
        'construction_email': {
            required: false,
            email: true
        },
        'registration': {
            required: false,
            digits: true
        },
        'exp_date': {
            required: false,
        },
        'EIN': {
            required: false,
            number: true
        },
        'contact_person': {
            required: false,
        },
        'contact_phone': {
            required: false,
            phoneUS: true
        },
        'fax': {
            required: false,
        }
    },
    messages: {
        'state': {
            required: 'Please select a state',
            minlength: 'Your name must consist of at least 3 characters'
        },
        'construction_email': 'Please enter a valid email address',
        'registration': 'Please enter only digits!',
        'EIN': 'Please enter only digits!',
    },
    submitHandler: function(){
        updatePermitInfo();
    }
    });
})

function delCompany(obj, id) {
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
        text: 'You will not be able to recover this company!',
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
            $.post("delCompany", {data: id}, function(result){
                if (result){
                    $(obj).parents("tr").remove().draw;
                    toast.fire('Deleted!', 'Company has been deleted.', 'success');
                }
            });

        } else if (result.dismiss === 'cancel') {
            toast.fire('Cancelled', 'Company is safe :)', 'error');
        }
    });
}

function updatePermitInfo(){
    let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
        }
    });
    var data = {};
    data.id = $('input#permitId').val();
    data.state = $('#usState').val();
    data.construction_email = $('input#construction_email').val();
    data.registration = $('input#registration').val();
    data.exp_date = $('input#exp_date').val();
    data.ein = $('input#EIN').val();
    data.contact_person = $('input#contact_person').val();
    data.contact_phone = $('input#contact_phone').val();
    data.fax = $('input#fax').val();
    
    $.post("updatePermitInfo", {data: data}, function(result){
        if (result && result.success){
            toast.fire('Updated!', 'Permit Info has been updated.', 'success');
        } else{
            if(result && result.message)
                toast.fire('Failed!', result.message, 'error');
            else
                toast.fire('Failed!', "Permit Info Update Failed.", 'error');
        }
    });
}

function updateCompany() {
    let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
        }
    });
    var data = {};
    data.id = $('input#id').val();
    data.name = $('input#name').val();
    data.number = $('input#number').val();
    data.telno = $('input#telno').val();
    data.address = $('input#address').val();
    data.email = $('input#email').val();
    data.website = $('input#website').val();

    if (data.id == 0) { // Create company
        $.post("updateCompany", {data: data}, function(result){
            if (result == true){
                toast.fire('Created!', 'Company has been created.', 'success');
            } else if (result == "exist") {
                toast.fire('Error!', 'Company already exists with the same name', 'error');
                return;
            }
            $('#modal-block-normal').modal('toggle');
            $('#companys').DataTable().ajax.reload();
        });
    } else { // Update company
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        $.post("updateCompany", {data: data}, function(result){
            if (result){
                swal.close();
                toast.fire('Updated!', 'Company has been updated.', 'success');
            }
            $('#modal-block-normal').modal('toggle');
            $('#companys').DataTable().ajax.reload();
        });
    }
}

function showEditCompany(obj, id) {
    $.post("getCompany", {data: id}, function(result){
        if (result){
            $('input#id').val(result.id);
            $('input#number').val(result.company_number);
            $('input#name').val(result.company_name);
            $('input#telno').val(result.company_telno);
            $('input#address').val(result.company_address);
            $('input#email').val(result.company_email);
            $('input#website').val(result.company_website);
            $('button#updateButton').html('Update');
        }
    });
}

function showPermitInfoCompany(obj, id) {
    $('input#permitId').val(id);
    $('#usState').val("");
    $('#construction_email').val("");
    $('#registration').val("");
    $('#exp_date').val("");
    $('#EIN').val("");
    $('#contact_person').val("");
    $('#contact_phone').val("");
    $('#fax').val("");
}

function showAddCompany() {
    $('input#id').val(0);
    $('input#number').val('');
    $('input#name').val('');
    $('input#email').val('');
    $('input#telno').val('');
    $('input#address').val('');
    $('input#website').val('');
    $('button#updateButton').html('Add');
}

function pullPermit(){
    $.ajax({
        url:"getPermitInfo",
        type:'post',
        data: {
            id: $('input#permitId').val(),
            state: $('#usState').val()
        },
        success:function(res){
            if(res.success){
                $('#construction_email').val(res.construction_email);
                $('#registration').val(res.registration);
                $('#exp_date').val(res.exp_date);
                $('#EIN').val(res.ein);
                $('#contact_person').val(res.contact_person);
                $('#contact_phone').val(res.contact_phone);
                $('#fax').val(res.fax);
            } else {
                $('#construction_email').val("");
                $('#registration').val("");
                $('#exp_date').val("");
                $('#EIN').val("");
                $('#contact_person').val("");
                $('#contact_phone').val("");
                $('#fax').val("");
            }
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            message = res.message;
            swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
            resolve(false);
        }
    });
}
</script>