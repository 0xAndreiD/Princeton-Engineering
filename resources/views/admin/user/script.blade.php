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
            toast.fire('Cancelled', 'User is safe :)', 'error');
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
            }
            $('#modal-block-normal').modal('toggle');
            $('#users').DataTable().ajax.reload();
        });
    } else { // Update User
        $.post("updateUser", {data: data}, function(result){
            if (result){
                toast.fire('Updated!', 'User has been updated.', 'success');
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
            $('select#company').val(result.companyid);
            $('input#usernumber').val(result.usernumber);
            $('input#membership').val(result.membershipid);
            $('input#membership').val(result.membershipid);
            $('button#updateButton').html('Update');
        }
    });
}

function showAddUser() {
    $('input#userid').val(0);
    $('input#name').val('');
    $('input#email').val('');
    $('select#company').val(1);
    $('select#userrole').val(0);
    $('input#usernumber').val('');
    $('button#updateButton').html('Add');
}
</script>