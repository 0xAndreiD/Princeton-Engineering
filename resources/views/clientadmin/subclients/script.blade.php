<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    Dashmix.helpers('validation');
    var validateObj = $('#clientForm').validate({rules: {
        'name': {
            required: true,
            minlength: 3
        },
        'telno': {
            required: true,
            phoneUS: true
        },
        'number': {
            required: true,
            digits: true
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
        updateClient();
    }
    });

    $("#logofile").on('change', function(){
        $("#logolink").val($(this).val().split('\\').pop());
    });
})

function delClient(obj, id) {
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
        text: 'You will not be able to recover this client!',
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
            $.post("delSubClient", {data: id}, function(result){
                if (result && result.success){
                    $(obj).parents("tr").remove().draw;
                    toast.fire('Deleted!', 'Client has been deleted.', 'success');
                } else {
                    toast.fire('Error', 'Client delete has been failed.', 'error');
                }
                $('#clients').DataTable().ajax.reload();
            });

        } else if (result.dismiss === 'cancel') {
            toast.fire('Cancelled', 'Client is safe :)', 'error');
        }
    });
}

function updateClient() {
    let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
        }
    });
    // var data = {};
    // data.id = $('input#id').val();
    // data.name = $('input#name').val();
    // data.number = $('input#number').val();
    // data.telno = $('input#telno').val();
    // data.address = $('input#address').val();
    // data.email = $('input#email').val();
    // data.website = $('input#website').val();
    // data.max_allowable_skip = $('input#max_allowable_skip').val();

    if ($('input#id').val() == 0) { // Create client
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        $("#clientForm").ajaxSubmit({
            type: "POST",
            url: "updateSubClient",
            dataType: 'json',
            data:{},
            success: function(res){
                swal.close();
                if (res && res.success){
                    toast.fire('Created!', 'Sub-Client has been created.', 'success');
                } else {
                    toast.fire('Error!', res.message, 'error');
                    return;
                }
                $('#modal-block-normal').modal('toggle');
                $('#clients').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                swal.close();
                res = JSON.parse(xhr.responseText);
                message = res.message;
                swal.fire({ title: "Error",
                        text: message == "" ? "Error happened while processing. Please try again later." : message,
                        icon: "error",
                        confirmButtonText: `OK` });
            }
        });
    } else { // Update client
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        $("#clientForm").ajaxSubmit({
            type: "POST",
            url: "updateSubClient",
            data: {},
            dataType: 'json',
            success: function(res){
                swal.close();
                if (res){
                    toast.fire('Updated!', 'Client has been updated.', 'success');
                } else {
                    toast.fire('Error!', res.message, 'error');
                    return;
                }
                $('#modal-block-normal').modal('toggle');
                $('#clients').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                swal.close();
                res = JSON.parse(xhr.responseText);
                message = res.message;
                swal.fire({ title: "Error",
                        text: message == "" ? "Error happened while processing. Please try again later." : message,
                        icon: "error",
                        confirmButtonText: `OK` });
            }
        });
    }
}

function showEditClient(obj, id) {
    $.post("getSubClient", {data: id}, function(result){
        if (result){
            $('input#id').val(result.id);
            $('input#number').val(result.subc_client_number);
            $('input#name').val(result.name);
            $('input#telno').val(result.telno);
            $('input#address1').val(result.street_1);
            $('input#address2').val(result.street_2);
            $('input#city').val(result.city);
            $('select#state').val(result.state);
            $('input#zip').val(result.zip);
            $('input#contact_name').val(result.contact_name);
            $('input#country_code').val(result.country_code);
            $('input#website').val(result.website);
            $('input#logolink').val(result.logo);
            $('input#logofile').val('');
            $('button#updateButton').html('Update');
        }
    });
}

function showAddClient() {
    $('input#id').val('');
    $('input#number').val('');
    $('input#name').val('');
    $('input#telno').val('');
    $('input#address1').val('');
    $('input#address2').val('');
    $('input#city').val('');
    $('select#state').val('');
    $('input#zip').val('');
    $('input#contact_name').val('');
    $('input#country_code').val('');
    $('input#website').val('');
    $('input#logolink').val('');
    $('input#logofile').val('');
    $('button#updateButton').html('Add');
}

function onUploadOpen(){
    $("#logofile").trigger('click');
}
</script>