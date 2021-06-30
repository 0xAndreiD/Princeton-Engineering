@extends((Auth::user()->userrole == 2)? 'admin.layout': ((Auth::user()->userrole == 1 || Auth::user()->userrole == 3) ? 'clientadmin.layout' : (Auth::user()->userrole == 4 ? 'reviewer.layout' : 'user.layout')))

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
        Project Number:{{$project->clientProjectNumber}}, Project Name: {{$project->clientProjectName}}, State:{{$project->state}}
        <br/>
        @if(!$messages || count($messages) == 0)
        <small id="noMessage">
            (No messages yet.)
        </small>
        @endif
    </h2>
    <form method="post" id="submitChat">
        <article class="row ml-0 mr-0">
            <div class="col-md-10 col-sm-10">				
                <div class="form-group">							
                    <textarea class="form-control" rows="3" id="message" name="message" placeholder="Enter your message..." required></textarea>	
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
                                <button type="button" class="btn btn-primary ml-1 mr-1" onclick="updateChatHistory(this,{{ $msg['id'] }})" style="padding: 3px 6px;">
                                    <i class="fa fa-save"></i>
                                </button>
                                <button type="button" class="btn btn-danger ml-1 mr-1" onclick="delChatHistory(this,{{ $msg['id'] }})" style="padding: 3px 6px;">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @elseif($msg['userrole'] == 3)
                                <i class="fa fa-user-secret"></i> Junior Super
                                <button type="button" class="btn btn-primary ml-1 mr-1" onclick="updateChatHistory(this,{{ $msg['id'] }})" style="padding: 3px 6px;">
                                    <i class="fa fa-save"></i>
                                </button>
                                <button type="button" class="btn btn-danger ml-1 mr-1" onclick="delChatHistory(this,{{ $msg['id'] }})" style="padding: 3px 6px;">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @elseif($msg['userrole'] == 4)
                                <i class="fa fa-user-secret"></i> Reviewer
                            @endif

                    </div>
                    @if ($userrole == 2 || $userrole == 3)
                    <div class="block-content">
                        <input id="chat_id" type="hidden" value="{{ $msg['id'] }}"></input>
                        <textarea rows="3" style="width:100%; border: 1px solid gray;">{{ $msg['text'] }}</textarea>
                    </div>
                    @else
                    <div class="block-content">
                        <p>{{ $msg['text'] }}</p>
                    </div>
                    @endif
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

@include('rsinput.chatscript')

@endsection