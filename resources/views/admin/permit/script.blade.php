<script>

var permitId = -1;
function showAddPermit(){
    permitId = -1;
    $("#sidebar-title").html("Create Permit");
    $(".form-control").val("");
    $(".form-control").css('background-color', '#FFFFFF');
    $("#page-container").addClass('side-overlay-o');
    $("#file").attr('disabled', false);
}


function showEditPermit(obj, id){
    permitId = id;
    $("#sidebar-title").html("Edit Permit");
    $(".form-control").val("");
    $(".form-control").css('background-color', '#FFFFFF');
    $("#file").attr('disabled', true);
    
    var current_row = $(obj).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = $('#permitFiles').DataTable().row(current_row).data();
    
    for(let key in data){
        if(key != 'id' && key != 'actions')
            $(`#${key}`).val(data[key]);
    }
    
    $("#page-container").addClass('side-overlay-o');
}

$('#file').change(function() {
  var i = $(this).prev('label').clone();
  var fileName = $('#file')[0].files[0].name;
  $(this).prev('label').text(fileName);
  $("#filename").val(fileName);
});

var requiredFields = ['filename', 'state', 'description', 'tabname'];
function isEmptyInputBox(){
    var isEmpty = false;
    for(let i = 0; i < requiredFields.length; i ++){
        if($(`#${requiredFields[i]}`).val() == ""){
            isEmpty = true;
            $(`#${requiredFields[i]}`).css('background-color', '#FFC7CE');
        }
    }
    return isEmpty;
}


function submitPermit(){
    var alldata = {};

    if(isEmptyInputBox()){
        swal.fire({ title: "Warning", text: "Please fill blank fields.", icon: "warning", confirmButtonText: `OK` });
        return; 
    }

    var formData = new FormData();

    formData.append('permitId', permitId);
    $('.side-view .form-control').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
        formData.append($(this).attr('id'), $(this).val());
    });

    if (permitId == -1)
        formData.append('file', $('#file')[0].files[0]);

    $.ajax({
        url:"submitPermit",
        type:'post',
        contentType: false,
        processData: false,
        dataType: 'text',
        enctype: 'multipart/form-data',
        data: formData,
        success:function(res){
            $("#page-container").removeClass('side-overlay-o');
            res = JSON.parse(res);
            if (res.success == true) {
                if(permitId == -1)
                    swal.fire({ title: "Created!", text: "Permit file has been created.", icon: "success", confirmButtonText: `OK` });
                else
                    swal.fire({ title: "Updated!", text: "Permit file has been updated.", icon: "success", confirmButtonText: `OK` });
                $('#permitFiles').DataTable().ajax.reload();
            } else {
                // error handling
                swal.fire({ title: "Error",
                text: res.message,
                icon: "error",
                confirmButtonText: `OK` });
            }
        },
        error: function(xhr, status, error) {
            $("#page-container").removeClass('side-overlay-o');
            res = JSON.parse(xhr.responseText);
            message = res.message;
            swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
        }
    });

    
}

function configPermit(obj, id) {
    window.location = "configPermit?id=" + id;
}

function delPermit(obj, id){
    swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this permit file!',
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
    })
    .then(( result ) => {
        if ( result.value ) {
            $.ajax({
                url:"deletePermit",
                type:'post',
                data:{permitId: id},
                success:function(res){
                    if (res.success == true) {
                        swal.fire({ title: "Deleted!", text: "Permit has been deleted.", icon: "success", confirmButtonText: `OK` });
                        $('#permitFiles').DataTable().ajax.reload();
                    } else {
                        // error handling
                        swal.fire({ title: "Error",
                            text: res.message,
                            icon: "error",
                            confirmButtonText: `OK` });
                    }
                },
                error: function(xhr, status, error) {
                    res = JSON.parse(xhr.responseText);
                    message = res.message;
                    swal.fire({ title: "Error",
                            text: message == "" ? "Error happened while processing. Please try again later." : message,
                            icon: "error",
                            confirmButtonText: `OK` });
                }
            });
        } else if (result.dismiss === 'cancel') {
            swal.fire({ title: "Cancelled", text: "Permit file is safe :)", icon: "info", confirmButtonText: `OK` });
        }
    });
}

</script>