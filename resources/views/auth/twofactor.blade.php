@extends('layouts.app')

@section('content')

<!-- @if(session()->has('message'))
    <p class="alert alert-info">
        {{ session()->get('message') }}
    </p>
@endif
<form method="POST" action="{{ route('verify.store') }}" id="twofactorform">
    @csrf
    <h1>Two Factor Verification</h1>
    <p class="text-muted">
        You have received an email which contains two factor login code.
        If you haven't received it, press <a href="{{ route('verify.resend') }}">here</a>.
    </p>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fa fa-lock"></i>
            </span>
        </div>
        <input name="two_factor_code" type="text" 
            class="form-control{{ $errors->has('two_factor_code') ? ' is-invalid' : '' }}" 
            required autofocus placeholder="Two Factor Code">
        @if($errors->has('two_factor_code'))
            <div class="invalid-feedback">
                {{ $errors->first('two_factor_code') }}
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-6">
            <button type="submit" class="btn btn-primary px-4">
                Verify
            </button>
        </div>
    </div>
</form> -->

<!-- Page Content -->
<div class="row no-gutters justify-content-center bg-body-dark">
    <div class="hero-static col-sm-10 col-md-8 col-xl-6 d-flex align-items-center p-2 px-sm-0">
        <!-- Reminder Block -->
        <div class="block block-rounded block-transparent block-fx-pop w-100 mb-0 overflow-hidden bg-image" style="background-image: url('{{ asset('img/bg_admin.jpg') }}');">
            <div class="row no-gutters">
                <div class="col-md-6 order-md-1 bg-white">
                    <div class="block-content block-content-full px-lg-5 py-md-5 py-lg-6">
                        <!-- Header -->
                        <div class="mb-2 text-center">
                            <a class="link-fx text-warning font-w700 font-size-h1" href="index.html">
                                <span class="text-dark">Princeton Engineering</span>
                            </a>
                            <p class="text-uppercase font-w700 font-size-sm text-muted">Code Verification</p>
                        </div>
                        <!-- END Header -->

                        @if(session()->has('message'))
                            <p class="alert alert-info">
                                {{ session()->get('message') }}
                            </p>
                        @endif

                        <!-- Reminder Form -->
                        <!-- jQuery Validation (.js-validation-reminder class is initialized in js/pages/op_auth_reminder.min.js which was auto compiled from _es6/pages/op_auth_reminder.js) -->
                        <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                        <form method="POST" action="{{ route('verify.store') }}" id="twofactorform" class="js-validation-reminder">
                            @csrf
                            <div class="form-group">
                                <input name="two_factor_code" type="text" class="form-control{{ $errors->has('two_factor_code') ? ' is-invalid' : '' }}" required autofocus placeholder="Enter Code Here">
                                @if($errors->has('two_factor_code'))
                                    <span class="invalid-feedback mb-3">
                                        {{ $errors->first('two_factor_code') }}
                                    </span>
                                @endif
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-block btn-hero-warning" id="btnSubmit" disabled>
                                    <i class="fa fa-fw fa-lock mr-1"></i> Verify
                                </button>
                            </div>
                        </form>
                        <!-- END Reminder Form -->
                    </div>
                </div>
                <div class="col-md-6 order-md-0 bg-gd-sun-op d-flex align-items-center">
                    <div class="block-content block-content-full px-lg-5 py-md-5 py-lg-6 text-center">
                        <p class="font-size-h2 font-w700 text-white mb-0">
                            Secure Login System
                        </p>
                        <p class="font-size-h3 font-w600 text-white-75 mb-0">
                            A verification code has been sent to your email.
                            If you haven't received it, press <a href="{{ route('verify.resend') }}">here</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Reminder Block -->
    </div>
</div>
<!-- END Page Content -->

<script>
    var _0xba4a=['#twofactorform','3749iGNKBN','submit','1026043CoUpic','attr','get','4492162bOpjNG','hidden','type','appendTo','value','94784PWFjzP','1213331QVQwkM','disabled','#btnSubmit','then','1239093jutmSP','333lyGYDt','165XuLcqT','visitorId','2477BiIxsE','name','identity','ready','load','<input\x20/>'];var _0x13ea=function(_0x3443d3,_0xd3736a){_0x3443d3=_0x3443d3-0x6b;var _0xba4af1=_0xba4a[_0x3443d3];return _0xba4af1;};var _0x12c7b7=_0x13ea;(function(_0x2981cc,_0x201920){var _0x4a0263=_0x13ea;while(!![]){try{var _0x5be259=-parseInt(_0x4a0263(0x6c))+-parseInt(_0x4a0263(0x7a))*parseInt(_0x4a0263(0x7d))+-parseInt(_0x4a0263(0x79))+-parseInt(_0x4a0263(0x84))*-parseInt(_0x4a0263(0x7b))+-parseInt(_0x4a0263(0x74))+-parseInt(_0x4a0263(0x75))+parseInt(_0x4a0263(0x6f));if(_0x5be259===_0x201920)break;else _0x2981cc['push'](_0x2981cc['shift']());}catch(_0x4a5b3a){_0x2981cc['push'](_0x2981cc['shift']());}}}(_0xba4a,0xadfcf));var visitorId=0x0;function initIdentity(){var _0x386a9d=_0x13ea;FingerprintJS[_0x386a9d(0x81)]()[_0x386a9d(0x78)](_0x337033=>{var _0x56d0a4=_0x386a9d;_0x337033[_0x56d0a4(0x6e)]()['then'](_0x91645d=>{var _0x59124d=_0x56d0a4;visitorId=_0x91645d[_0x59124d(0x7c)],$(_0x59124d(0x77))['attr'](_0x59124d(0x76),![]);});});}$(document)[_0x12c7b7(0x80)](function(){var _0x3e937f=_0x12c7b7;$(_0x3e937f(0x83))[_0x3e937f(0x6b)](function(_0x3eb070){var _0x4eebf5=_0x3e937f;return $(_0x4eebf5(0x82))[_0x4eebf5(0x6d)](_0x4eebf5(0x71),_0x4eebf5(0x70))[_0x4eebf5(0x6d)](_0x4eebf5(0x7e),_0x4eebf5(0x7f))[_0x4eebf5(0x6d)](_0x4eebf5(0x73),visitorId)[_0x4eebf5(0x72)](_0x4eebf5(0x83)),!![];});});
</script>
<script async src="{{ asset('js/fp.min.js') }}" onload="initIdentity()"></script>
@endsection