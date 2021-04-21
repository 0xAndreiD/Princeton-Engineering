@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : 'user.layout'))

@section('content')
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    iRoof™ Structural Analysis Data Chatting
                </h1>
                <h2 class="h5 text-white-75 mb-0" id="subPageTitle">
                    Input your chats that you need
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="mt-3 ml-3">

<h2 class="content-heading">
    Messages
    @if(!$messages || count($messages) == 0)
    <small id="noMessage">
        (No messages yet.)
    </small>
    @endif
</h2>
<div id="chatPane">
@foreach($messages as $msg)	
    <div class="col-md-10 col-sm-10">
        <div class="block block-bordered">
            <div class="block-header" style="background-color: #e9eaec; display: block;">
                <i class="fa fa-user"></i> <?php echo $msg['username']; ?>
                    @if($msg['userrole'] == 0)
                        <i class="fa fa-user-secret"></i> User
                    @elseif($msg['userrole'] == 1)
                        <i class="fa fa-user-secret"></i> Client Admin
                    @elseif($msg['userrole'] == 2)
                        <i class="fa fa-user-secret"></i> Super Admin
                    @elseif($msg['userrole'] == 3)
                        <i class="fa fa-user-secret"></i> Junior Super
                    @endif
            </div>
            <div class="block-content">
                <p>{{ $msg['text'] }}</p>
            </div>
            <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                <i class="fa fa-clock"></i> {{ $msg['datetime'] }}
            </div>
        </div>
    </div>
    <!-- <article class="row ml-3 mr-0">
        <div class="col-md-10 col-sm-10">
            <div class="panel panel-default arrow right">
                <div class="panel-heading">
                    <i class="fa fa-user"></i> <?php echo $msg['username']; ?>
                    @if($msg['userrole'] == 0)
                        <i class="fa fa-user-secret"></i> User
                    @elseif($msg['userrole'] == 1)
                        <i class="fa fa-user-secret"></i> Client Admin
                    @elseif($msg['userrole'] == 2)
                        <i class="fa fa-user-secret"></i> Super Admin
                    @elseif($msg['userrole'] == 3)
                        <i class="fa fa-user-secret"></i> Junior Super
                    @endif
                    &nbsp;&nbsp;<i class="fa fa-clock"></i> {{ $msg['datetime'] }}
                </div>
                <div class="panel-body">						
                    <div class="comment-post">
                    <p>
                    {{ $msg['text'] }}
                    </p>
                    </div>                  
                </div>
                
            </div>
        </div>            
    </article> 		 -->
@endforeach
</div>
</div>

<form method="post" id="submitChat" class="ml-3">
    <article class="row ml-0 mr-0">
        <div class="col-md-10 col-sm-10">				
            <div class="form-group">							
                <textarea class="form-control" rows="5" id="message" name="message" placeholder="Enter your message..." required></textarea>	
            </div>				
        </div>
    </article>  
    <article class="row ml-0 mr-0">
        <div class="col-md-10 col-sm-10">
            <div class="form-group">							
                <input type="submit" name="send" id="send" class="btn btn-success" value="Send Message" />		
            </div>
        </div>
    </article> 
    <input type="hidden" name="projectId" id="projectId" value="{{ $projectId }}" />	
</form>

<script>
    window.msgCount = "{{ count($messages) }}";

    function addChat(user, role, message, datetime){
        let userrole;
        if(role == 0) userrole = 'User';
        else if(role == 1) userrole = 'Client Admin';
        else if(role == 2) userrole = 'Super Admin';
        else if(role == 3) userrole = 'Junior Super';
        let html = "<div class='col-md-10 col-sm-10'>" + 
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
        $("#chatPane").prepend(html);
        window.msgCount ++;
        $("#noMessage").css("display", "none");
    }

    function updateChat(){
        setTimeout(
            function() {
                checkChatUpdated();
        }, 5000);
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
                            addChat(data.msgs[i].user, data.msgs[i].role, data.msgs[i].message, data.msgs[i].datetime);
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
                    //location.reload();
                    addChat(data.user, data.role, data.message, data.datetime);
                }
			}
		})
	});
</script>

@endsection