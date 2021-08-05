<script>

var stanchionId = -1;
@if(Auth::user()->userrole == 2)
function showAddStanchion(){
    stanchionId = -1;
    $("#sidebar-title").html("Create Stanchion");
    $(".form-control").val("");
    $(".form-control").css('background-color', '#FFFFFF');
    $("#page-container").addClass('side-overlay-o');
}


function showEditStanchion(obj, id){
    stanchionId = id;
    $("#sidebar-title").html("Edit Stanchion");
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

function submitStanchion(){
    var alldata = {};

    if(isEmptyInputBox()){
        swal.fire({ title: "Warning", text: "Please fill blank fields.", icon: "warning", confirmButtonText: `OK` });
        return; 
    }

    $('.side-view .form-control').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });

    $.ajax({
        url:"submitStandardStanchion",
        type:'post',
        data:{stanchionId: stanchionId, data: alldata},
        success:function(res){
            $("#page-container").removeClass('side-overlay-o');
            if (res.success == true) {
                if(stanchionId == -1)
                    swal.fire({ title: "Created!", text: "Stanchion has been created.", icon: "success", confirmButtonText: `OK` });
                else
                    swal.fire({ title: "Updated!", text: "Stanchion has been updated.", icon: "success", confirmButtonText: `OK` });
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

function delStanchion(obj, id){
    swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this Stanchion!',
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
                url:"deleteStandardStanchion",
                type:'post',
                data:{stanchionId: id},
                success:function(res){
                    if (res.success == true) {
                        swal.fire({ title: "Deleted!", text: "Stanchion has been deleted.", icon: "success", confirmButtonText: `OK` });
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
            swal.fire({ title: "Cancelled", text: "Stanchion is safe :)", icon: "info", confirmButtonText: `OK` });
        }
    });
}

function copyStanchions(){
    var ids = [];
    $(".bulkcheck").each(function(){
        if($(this)[0].checked){
            ids.push($(this).attr('id').split('bulkcheck_').join(''));
        }
    });

    if(ids.length > 0){
        swal.fire({
            title: 'Are you sure want to copy selected stanchions?',
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-danger m-1',
                cancelButton: 'btn btn-secondary m-1'
            },
            confirmButtonText: 'Yes, copy!',
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
                swal.fire({ title: "Please wait...", showConfirmButton: false });
                swal.showLoading();
                $.ajax({
                    url:"copyStandardStanchions",
                    type:'post',
                    data:{ids: ids},
                    success:function(res){
                        swal.close();
                        if (res.success == true) {
                            swal.fire({ title: "Copied!", text: "Stanchions have been duplicated.", icon: "success", confirmButtonText: `OK` });
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
        });
    } else
        swal.fire({ title: "Warning!", text: "Please select some stanchions.", icon: "warning", confirmButtonText: `OK` });
}

function delStanchions(){
    var ids = [];
    $(".bulkcheck").each(function(){
        if($(this)[0].checked){
            ids.push($(this).attr('id').split('bulkcheck_').join(''));
        }
    });

    if(ids.length > 0){
        swal.fire({
            title: 'Are you sure want to delete selected stanchions?',
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-danger m-1',
                cancelButton: 'btn btn-secondary m-1'
            },
            confirmButtonText: 'Yes, Delete!',
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
                swal.fire({ title: "Please wait...", showConfirmButton: false });
                swal.showLoading();
                $.ajax({
                    url:"delStandardStanchions",
                    type:'post',
                    data:{ids: ids},
                    success:function(res){
                        swal.close();
                        if (res.success == true) {
                            swal.fire({ title: "Deleted!", text: "Stanchions have been deleted.", icon: "success", confirmButtonText: `OK` });
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
        });
    } else
        swal.fire({ title: "Warning!", text: "Please select some stanchions.", icon: "warning", confirmButtonText: `OK` });
}

@endif
function toggleFavourite(obj, id){
    $.ajax({
        url:"standardStanchionToggleFavorite",
        type:'post',
        data:{stanchionId: id},
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