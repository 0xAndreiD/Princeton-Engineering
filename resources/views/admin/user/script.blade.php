<script>

$(document).ready(function(){
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
        'usernumber': {
            required: true,
            number: true
        },
        'password': {
            required: true,
            minlength: 5
        },
    },
    messages: {
        'name': {
            required: 'Please enter a name',
            minlength: 'Your name must consist of at least 3 characters'
        },
        'email': 'Please enter a valid email address',
        'usernumber': 'Please enter a number!',
        'password': {
            required: 'Please provide a password',
            minlength: 'Your password must be at least 5 characters long'
        },
    },
    submitHandler: function(){
        updateUser();
    }
    });

    $('select#company').on('change', function(){
        $.post("recommendUserNum", {companyid: $(this).val()}, function(result){
            $('input#usernumber').attr('placeholder', 'Recommended User Number: ' + result);
        });
    })
})

function delUser(obj, id) {
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
        text: 'You will not be able to recover this user!',
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
            $.post("delUser", {data: id}, function(result){
                if (result){
                    $(obj).parents("tr").remove().draw;
                    toast.fire('Deleted!', 'User has been deleted.', 'success');
                }
            });

        } else if (result.dismiss === 'cancel') {
            toast.fire('Cancelled', 'User is safe :)', 'info');
        }
    });
}

function updateUser() {
    let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
        }
    });
    var data = {};
    data.id = $('input#userid').val();
    data.name = $('input#name').val();
    data.email = $('input#email').val();
    data.password = $('input#password').val();
    data.companyid = $('select#company').val();
    data.usernumber = $('input#usernumber').val();
    data.userrole = $('select#userrole').val();
    data.distance_limit = $('input#distance_limit').val();
    data.ask_two_factor = $('select#ask_two_factor').val();

    if (data.id == 0) { // Create user
        if (data.password == ''){
            $('input#password').focus();
            return;
        }
        $.post("updateUser", {data: data}, function(result){
            if (result == true){
                toast.fire('Created!', 'User has been created.', 'success');
            } else if (result == "exist") {
                toast.fire('Error!', 'User already exists with the same name', 'error');
                return;
            } else if (result == "idexist") {
                toast.fire('Error!', 'User number already exists in the same company', 'error');
                return;
            }
            $('#modal-block-normal').modal('toggle');
            $('#users').DataTable().ajax.reload();
        });
    } else { // Update User
        $.post("updateUser", {data: data}, function(result){
            if (result == true){
                toast.fire('Updated!', 'User has been updated.', 'success');
            } else if (result == "exist") {
                toast.fire('Error!', 'User already exists with the same name', 'error');
                return;
            } else if (result == "idexist") {
                toast.fire('Error!', 'User number already exists in the same company', 'error');
                return;
            }
            $('#modal-block-normal').modal('toggle');
            $('#users').DataTable().ajax.reload();
        });
    }
}

function showEditUser(obj, id) {
    $.post("getUser", {data: id}, function(result){
        if (result){
            $('input#userid').val(result.id);
            $('input#name').val(result.username);
            $('input#password').val(result.password);
            $('input#email').val(result.email);
            $('select#company').val(result.companyid.toString()).trigger('change');
            $('select#userrole').val(result.userrole.toString()).trigger('change');
            $('input#usernumber').val(result.usernumber);
            $('input#membership').val(result.membershipid);
            $('input#distance_limit').val(result.distance_limit);
            $('button#updateButton').html('Update');
            $('select#ask_two_factor').val(result.ask_two_factor.toString()).trigger('change');
            $.post("recommendUserNum", {companyid: result.companyid}, function(userNum){
                $('input#usernumber').attr('placeholder', 'Recommended User Number: ' + userNum);
            });
        }
    });
}

function showAddUser() {
    $('input#userid').val(0);
    $('input#name').val('');
    $('input#email').val('');
    $('select#company').val("1").trigger('change');
    $('select#userrole').val("0").trigger('change');
    $('input#usernumber').val('');
    $('button#updateButton').html('Add');
    $.post("recommendUserNum", {companyid: 1}, function(result){
        $('input#usernumber').attr('placeholder', 'Recommended User Number: ' + result);
    });
}
</script>