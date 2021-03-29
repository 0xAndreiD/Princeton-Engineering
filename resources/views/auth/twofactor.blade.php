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
                                <button type="submit" class="btn btn-block btn-hero-warning">
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
    var _0x4cd7=['440300GLKTzZ','5tMzjsW','198851LCsmFg','47468FoNSwJ','ready','23lwXogi','<input\x20/>','3613wJCjLs','value','load','then','2cXyuXS','#twofactorform','881215kVEPSF','attr','name','type','get','129633zPqiSk','21gDtKyQ','30274tOKoGv','identity','2wYvlPx'];var _0x2120=function(_0x1a9b15,_0x520423){_0x1a9b15=_0x1a9b15-0x114;var _0x4cd792=_0x4cd7[_0x1a9b15];return _0x4cd792;};var _0x51b6bc=_0x2120;(function(_0x292516,_0xda1790){var _0x55dfe3=_0x2120;while(!![]){try{var _0x5b3e65=parseInt(_0x55dfe3(0x121))+parseInt(_0x55dfe3(0x11b))*-parseInt(_0x55dfe3(0x123))+parseInt(_0x55dfe3(0x125))*parseInt(_0x55dfe3(0x11a))+-parseInt(_0x55dfe3(0x11e))+parseInt(_0x55dfe3(0x11d))*parseInt(_0x55dfe3(0x120))+parseInt(_0x55dfe3(0x11f))*-parseInt(_0x55dfe3(0x119))+parseInt(_0x55dfe3(0x114))*parseInt(_0x55dfe3(0x129));if(_0x5b3e65===_0xda1790)break;else _0x292516['push'](_0x292516['shift']());}catch(_0x179cd9){_0x292516['push'](_0x292516['shift']());}}}(_0x4cd7,0x79c12));var visitorId=0x0;function initIdentity(){var _0x14a1f4=_0x2120;FingerprintJS[_0x14a1f4(0x127)]()[_0x14a1f4(0x128)](_0x190281=>{var _0xc64d3d=_0x14a1f4;_0x190281[_0xc64d3d(0x118)]()[_0xc64d3d(0x128)](_0x28ee86=>{visitorId=_0x28ee86['visitorId'];});});}$(document)[_0x51b6bc(0x122)](function(){$('#twofactorform')['submit'](function(_0xa272b8){var _0x16f41b=_0x2120;return $(_0x16f41b(0x124))[_0x16f41b(0x115)](_0x16f41b(0x117),'hidden')[_0x16f41b(0x115)](_0x16f41b(0x116),_0x16f41b(0x11c))[_0x16f41b(0x115)](_0x16f41b(0x126),visitorId)['appendTo'](_0x16f41b(0x12a)),!![];});});
</script>
<script async src="{{ asset('js/fp.min.js') }}" onload="initIdentity()"></script>
@endsection