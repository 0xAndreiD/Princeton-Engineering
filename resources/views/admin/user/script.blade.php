<script>

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
    if (data.name == ''){
        $('input#name').focus();
        return;
    }
    data.email = $('input#email').val();
    if (!ValidateEmail(data.email)){
        $('input#email').focus();
        return;
    }
    data.password = $('input#password').val();
    data.companyid = $('select#company').val();
    data.usernumber = $('input#usernumber').val();
    data.userrole = $('input#userrole').val();

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