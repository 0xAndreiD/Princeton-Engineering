<script>

var moduleId = -1;

function showAddModule(){
    moduleId = -1;
    $("#sidebar-title").html("Create Module");
    $(".form-control").val("");
    $(".form-control").css('background-color', '#FFFFFF');
    $("#page-container").addClass('side-overlay-o');
}


function showEditModule(obj, id){
    moduleId = id;
    $("#sidebar-title").html("Edit Module");
    $(".form-control").val("");
    $(".form-control").css('background-color', '#FFFFFF');
    
    var data = $('#equipments').DataTable().row($(obj).parents("tr")).data();
    for(let key in data){
        if(key != 'id' && key != 'actions' && key != 'client_no')
            $(`#${key}`).val(data[key]);
    }
    
    $("#page-container").addClass('side-overlay-o');
}


var requiredFields = ['mfr', 'model', 'rating', 'length', 'width', 'depth', 'weight', 'Mtg_Hole_1', 'url'];
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

function submitModule(){
    var alldata = {};

    if(isEmptyInputBox()){
        swal.fire({ title: "Warning", text: "Please fill blank fields.", icon: "warning", confirmButtonText: `OK` });
        return; 
    }

    $('.side-view .form-control').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });

    $.ajax({
        url:"submitModule",
        type:'post',
        data:{moduleId: moduleId, data: alldata},
        success:function(res){
            $("#page-container").removeClass('side-overlay-o');
            if (res.success == true) {
                if(moduleId == -1)
                    swal.fire({ title: "Created!", text: "Module has been created.", icon: "success", confirmButtonText: `OK` });
                else
                    swal.fire({ title: "Updated!", text: "Module has been updated.", icon: "success", confirmButtonText: `OK` });
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
@if(Auth::user()->userrole == 2)
function delModule(obj, id){
    swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this module!',
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
                url:"deleteModule",
                type:'post',
                data:{moduleId: id},
                success:function(res){
                    if (res.success == true) {
                        swal.fire({ title: "Deleted!", text: "Module has been deleted.", icon: "success", confirmButtonText: `OK` });
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
            swal.fire({ title: "Cancelled", text: "Module is safe :)", icon: "info", confirmButtonText: `OK` });
        }
    });
}
@endif
function toggleFavourite(obj, id){
    $.ajax({
        url:"moduleToggleFavorite",
        type:'post',
        data:{moduleId: id},
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