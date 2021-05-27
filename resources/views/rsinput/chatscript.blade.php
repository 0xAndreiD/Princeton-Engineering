<script>
    window.msgCount = "{{ count($messages) }}";

    function addChat(user, role, message, datetime, id){
        let userrole;
        if(role == 0) userrole = 'User';
        else if(role == 1) userrole = 'Client Admin';
        else if(role == 2) userrole = 'Super Admin';
        else if(role == 3) userrole = 'Junior Super';

        var html="";
        if (role == 2 || role == 3){
            html = "<div class='col-md-10 col-sm-10'>" + 
                "<div class='block block-bordered'>" +
                    "<div class='block-header' style='background-color: #e9eaec; display: block;'>" +
                        "<i class='fa fa-user'></i> " + user + " " + 
                            "<i class='fa fa-user-secret'></i> " + userrole + 
                            "<button type='button' class='btn btn-primary ml-1 mr-1' onclick='updateChatHistory(this,"+id+")' style='padding: 3px 6px;'>" + 
                                    "<i class='fa fa-save'></i>" + 
                                "</button>" + 
                                "<button type='button' class='btn btn-danger ml-1 mr-1' onclick='delChatHistory(this,"+id+")' style='padding: 3px 6px;'>" +
                                    "<i class='fa fa-trash'></i>" +
                                "</button>" +
                    "</div>" + 
                    "<div class='block-content'>" + 
                        "<textarea rows='3' style='width:100%; border: 1px solid gray;''>"+message+"</textarea>" +
                    "</div>" +
                    "<div class='block-content block-content-full block-content-sm bg-body-light font-size-sm'>" +
                        "<i class='fa fa-clock'></i> " + datetime +
                    "</div>" + 
                "</div>" + 
            "</div>";
        } else if(role == 1 || role == 0) {
            html = "<div class='col-md-10 col-sm-10'>" + 
                "<div class='block block-bordered'>" +
                    "<div class='block-header' style='background-color: #e9eaec; display: block;'>" +
                        "<i class='fa fa-user'></i> " + user + " " + 
                            "<i class='fa fa-user-secret'></i> " + userrole + 
                    "</div>" + 
                    "<div class='block-content'>" + 
                        "<p>" + message + "</p>" +
                    "</div>" +
                    "<div class='block-content block-content-full block-content-sm bg-body-light font-size-sm'>" +
                        "<i class='fa fa-clock'></i> " + datetime +
                    "</div>" + 
                "</div>" + 
            "</div>";
        }
        
        $("#chatPane").prepend(html);
        
        $("#noMessage").css("display", "none");
    }

    function updateChat(){
        setTimeout(
            function() {
                checkChatUpdated();
        }, 1000);
    }

    function checkChatUpdated(){
        $.ajax({
			url:"checkChatList",
			method:"POST",
			data:{jobId: $("#projectId").val(), msgCount: window.msgCount},
			success:function(data){
                if(data && data.success){
                    console.log(data);
                    if(data.msgCount > 0){
                        for(let i = 0; i < data.msgs.length; i ++)
                            addChat(data.msgs[i].user, data.msgs[i].role, data.msgs[i].message, data.msgs[i].datetime, data.msg[i].id);
                    }
                }
                updateChat();
			}
		})
    }

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        updateChat();
    });

    $(document).on('submit','#submitChat', function(event){
        event.preventDefault();
		$('#send').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"submitChat",
			method:"POST",
			data:formData,
			success:function(data){
                if(!data || !data.status){
                    swal.fire({ title: "Warning", text: data && data.message ? data.message : "Failed to submit your message.", icon: "warning", confirmButtonText: `OK` });
                } else {
                    $("#message")[0].value = "";
                    $('#send').attr('disabled', false);
                    window.msgCount++;
                    //location.reload();
                    addChat(data.user, data.role, data.message, data.datetime, data.id);
                }
			}
		})
	});

    function delChatHistory(obj, id) {
        var projectId = $("#projectId").val();
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
            text: 'You will not be able to recover this chat text!',
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
                $.post("delChat", {data: id, projectId: projectId}, function(result){
                    if (result){
                        window.msgCount --;
                        $(obj).parent().parent().parent().remove().draw;
                        toast.fire('Deleted!', 'Chat has been deleted.', 'success');
                    }
                });
            } else if (result.dismiss === 'cancel') {
                toast.fire('Cancelled', 'Chat is safe :)', 'error');
            }
        });
    }
    
    var badgeColors = {'info': '#3c90df', 'warning': '#ffb119', 'primary': '#689550', 'danger': '#e04f1a', 'dark': '#343a40', 'secondary': 'rgba(0, 0, 0, 0.33)', 'success': '#82b54b'}
    
    function updateChatHistory(obj, id){
        var text = $(obj).parent().parent().find('textarea').val();
        var projectId = $("#projectId").val();

        let toast = Swal.mixin({
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-success m-1',
                cancelButton: 'btn btn-danger m-1',
                input: 'form-control'
            }
        });
        $.ajax({
            url:"updateChat",
            type:'post',
            data:{chatId: id, projectId: projectId, text: text},
            success:function(res){
                if (res.success == true) {
                    toast.fire('Updated!', 'Chat has been updated.', 'success');
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