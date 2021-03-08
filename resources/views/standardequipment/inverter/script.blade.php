<script>

var inverterId = -1;
@if(Auth::user()->userrole == 2)
function showAddInverter(){
    inverterId = -1;
    $("#sidebar-title").html("Create Inverter");
    $(".form-control").val("");
    $(".form-control").css('background-color', '#FFFFFF');
    $("#page-container").addClass('side-overlay-o');
}


function showEditInverter(obj, id){
    inverterId = id;
    $("#sidebar-title").html("Edit Inverter");
    $(".form-control").val("");
    $(".form-control").css('background-color', '#FFFFFF');
    
    var current_row = $(obj).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = $('#equipments').DataTable().row(current_row).data();
    
    for(let key in data){
        if(key != 'id' && key != 'actions' && key != 'client_no')
            $(`#${key}`).val(data[key]);
    }
    
    $("#page-container").addClass('side-overlay-o');
}


var requiredFields = ['module', 'submodule', 'option1', 'option2'];
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

function submitInverter(){
    var alldata = {};

    if(isEmptyInputBox()){
        swal.fire({ title: "Warning", text: "Please fill blank fields.", icon: "warning", confirmButtonText: `OK` });
        return; 
    }

    $('.side-view .form-control').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });

    $.ajax({
        url:"submitStandardInverter",
        type:'post',
        data:{inverterId: inverterId, data: alldata},
        success:function(res){
            $("#page-container").removeClass('side-overlay-o');
            if (res.success == true) {
                if(inverterId == -1)
                    swal.fire({ title: "Created!", text: "Inverter has been created.", icon: "success", confirmButtonText: `OK` });
                else
                    swal.fire({ title: "Updated!", text: "Inverter has been updated.", icon: "success", confirmButtonText: `OK` });
                $('#equipments').DataTable().ajax.reload();
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

function delInverter(obj, id){
    swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this inverter!',
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
                url:"deleteStandardInverter",
                type:'post',
                data:{inverterId: id},
                success:function(res){
                    if (res.success == true) {
                        swal.fire({ title: "Deleted!", text: "Inverter has been deleted.", icon: "success", confirmButtonText: `OK` });
                        $('#equipments').DataTable().ajax.reload();
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
            swal.fire({ title: "Cancelled", text: "Inverter is safe :)", icon: "info", confirmButtonText: `OK` });
        }
    });
}
@endif
function toggleFavourite(obj, id){
    $.ajax({
        url:"standardInverterToggleFavorite",
        type:'post',
        data:{inverterId: id},
        success:function(res){
            if (res.success == true) {
                $('#equipments').DataTable().ajax.reload();
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
}

</script>