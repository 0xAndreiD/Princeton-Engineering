<script>

var rackingId = -1;
@if(Auth::user()->userrole == 2)
function showAddRacking(){
    rackingId = -1;
    $("#sidebar-title").html("Create Solar Racking");
    $(".form-control").val("");
    $(".form-control").css('background-color', '#FFFFFF');
    $("#page-container").addClass('side-overlay-o');
}


function showEditRacking(obj, id){
    rackingId = id;
    $("#sidebar-title").html("Edit Solar Racking");
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

function submitRacking(){
    var alldata = {};

    if(isEmptyInputBox()){
        swal.fire({ title: "Warning", text: "Please fill blank fields.", icon: "warning", confirmButtonText: `OK` });
        return; 
    }

    $('.side-view .form-control').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });

    $.ajax({
        url:"submitStandardRacking",
        type:'post',
        data:{rackingId: rackingId, data: alldata},
        success:function(res){
            $("#page-container").removeClass('side-overlay-o');
            if (res.success == true) {
                if(rackingId == -1)
                    swal.fire({ title: "Created!", text: "Solar Racking has been created.", icon: "success", confirmButtonText: `OK` });
                else
                    swal.fire({ title: "Updated!", text: "Solar Racking has been updated.", icon: "success", confirmButtonText: `OK` });
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

function delRacking(obj, id){
    swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this Solar Racking!',
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
                url:"deleteStandardRacking",
                type:'post',
                data:{rackingId: id},
                success:function(res){
                    if (res.success == true) {
                        swal.fire({ title: "Deleted!", text: "Solar Racking has been deleted.", icon: "success", confirmButtonText: `OK` });
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
            swal.fire({ title: "Cancelled", text: "Solar Racking is safe :)", icon: "info", confirmButtonText: `OK` });
        }
    });
}

function copyRackings(){
    var ids = [];
    $(".bulkcheck").each(function(){
        if($(this)[0].checked){
            ids.push($(this).attr('id').split('bulkcheck_').join(''));
        }
    });

    if(ids.length > 0){
        swal.fire({
            title: 'Are you sure want to copy selected rail supports?',
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
                    url:"copyStandardRackings",
                    type:'post',
                    data:{ids: ids},
                    success:function(res){
                        swal.close();
                        if (res.success == true) {
                            swal.fire({ title: "Copied!", text: "Rail supports have been duplicated.", icon: "success", confirmButtonText: `OK` });
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
        swal.fire({ title: "Warning!", text: "Please select some rail supports.", icon: "warning", confirmButtonText: `OK` });
}

function delRackings(){
    var ids = [];
    $(".bulkcheck").each(function(){
        if($(this)[0].checked){
            ids.push($(this).attr('id').split('bulkcheck_').join(''));
        }
    });

    if(ids.length > 0){
        swal.fire({
            title: 'Are you sure want to delete selected rail supports?',
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
                    url:"delStandardRackings",
                    type:'post',
                    data:{ids: ids},
                    success:function(res){
                        swal.close();
                        if (res.success == true) {
                            swal.fire({ title: "Deleted!", text: "Rail supports have been deleted.", icon: "success", confirmButtonText: `OK` });
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
        swal.fire({ title: "Warning!", text: "Please select some rail supports.", icon: "warning", confirmButtonText: `OK` });
}

@endif

var rackingId;

function toggleFavourite(id, isFavorite) {
    if(isFavorite) {
        rackingId = id;
        saveFavorite();
    } else {
        $("#path_filename").val('');
        $("#pages").val('');
        rackingId = id;
        $("#details_modal").modal('show');
    }   
}

function doToggle() {
    if($("#path_filename").val() == '' || $("#pages").val() == '') {
        swal.fire({
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-danger m-1',
                cancelButton: 'btn btn-secondary m-1'
            },
            confirmButtonText: 'Accept',
            cancelButtonText: 'Retry',
            html: 'No product cut sheet path / file name entered.  Automatic insertion of this product will not be available.  <br>  Retry or accept this condition?',
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
                saveFavorite();
                $("#details_modal").modal('hide');
            }
        });
    } else {
        saveFavorite();
        $("#details_modal").modal('hide');
    }
}

function saveFavorite() {
    $.ajax({
        url:"standardRackingToggleFavorite",
        type:'post',
        data:{path_filename: $("#path_filename").val(), pages: $("#pages").val(), rackingId: rackingId},
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