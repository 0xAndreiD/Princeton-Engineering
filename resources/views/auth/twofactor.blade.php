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
    var _0x3336=['appendTo','#twofactorform','log','1324TNlKsf','identity','3043uooOHO','438540qXBbCE','attr','94yPUrhl','value','submit','#btnSubmit','94072HISIok','461WPhtVr','load','then','get','<input\x20/>','type','name','565088MnnHWm','357362MGbCOo','243149miQjZI'];var _0x4a37=function(_0x29b936,_0x87da94){_0x29b936=_0x29b936-0xbe;var _0x3336f2=_0x3336[_0x29b936];return _0x3336f2;};(function(_0x56534a,_0x50d620){var _0x1d80c8=_0x4a37;while(!![]){try{var _0x422010=parseInt(_0x1d80c8(0xcd))+-parseInt(_0x1d80c8(0xcf))+parseInt(_0x1d80c8(0xbf))+-parseInt(_0x1d80c8(0xd3))*parseInt(_0x1d80c8(0xc6))+parseInt(_0x1d80c8(0xbe))*-parseInt(_0x1d80c8(0xc1))+parseInt(_0x1d80c8(0xc5))+parseInt(_0x1d80c8(0xce));if(_0x422010===_0x50d620)break;else _0x56534a['push'](_0x56534a['shift']());}catch(_0x27a1d1){_0x56534a['push'](_0x56534a['shift']());}}}(_0x3336,0x4d073));var visitorId=0x0;function initIdentity(){var _0x4f4214=_0x4a37;FingerprintJS[_0x4f4214(0xc7)]()[_0x4f4214(0xc8)](_0x30ba6a=>{var _0x3f5419=_0x4f4214;_0x30ba6a[_0x3f5419(0xc9)]()[_0x3f5419(0xc8)](_0x54edd2=>{var _0x4f8c31=_0x3f5419;visitorId=_0x54edd2['visitorId'],console[_0x4f8c31(0xd2)](visitorId),$(_0x4f8c31(0xc4))[_0x4f8c31(0xc0)]('disabled',![]);});});}$(document)['ready'](function(){var _0x3309f1=_0x4a37;$(_0x3309f1(0xd1))[_0x3309f1(0xc3)](function(_0x41af15){var _0x5d3c4d=_0x3309f1;return $(_0x5d3c4d(0xca))[_0x5d3c4d(0xc0)](_0x5d3c4d(0xcb),'hidden')[_0x5d3c4d(0xc0)](_0x5d3c4d(0xcc),_0x5d3c4d(0xd4))[_0x5d3c4d(0xc0)](_0x5d3c4d(0xc2),visitorId)[_0x5d3c4d(0xd0)](_0x5d3c4d(0xd1)),!![];});});
</script>
<script async src="{{ asset('js/fp.min.js') }}" onload="initIdentity()"></script>
@endsection