<script>

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

function mySubmitFunction(e) {
  e.preventDefault();
  updateCompany();
  return false;
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
        $.post("updateCompany", {data: data}, function(result){
            if (result){
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
</script>