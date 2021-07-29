@extends('clientadmin.layout')

@section('content')
<!-- Hero -->
<div class="bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
    <div class="bg-black-10">
        <div class="content content-full content-top">
            <div class="pt-5 pb-4 text-center">
                <h1 class="h2 font-w700 mb-2 text-white">
                    Client Admin Panel
                </h1>
                <h2 class="h5 text-white mb-0">
                    Welcome, {{Auth::user()->username}}
                </h2>
                <!-- <span class="badge badge-success mt-2">
                    <i class="fa fa-spinner fa-spin mr-1"></i> Running
                </span> -->
            </div>
        </div>
    </div>
</div>
<!-- END Hero -->

@if($notify == 1)
<script src="{{ asset('/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script>
    $(document).ready(function(){
        Dashmix.helpers('notify', {type: 'success', icon: 'fa fa-check mr-1', message: 'Your location & device has been saved!'});
    })
</script>
@endif
@endsection

