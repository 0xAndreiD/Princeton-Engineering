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
    var visitorId = 0;
    
    function initIdentity(){
        FingerprintJS.load().then(fp => {
            fp.get().then(result => {
                visitorId = result.visitorId;
                $("#btnSubmit").attr('disabled', false);
            });
        });
    }

    $(document).ready(function() { 
        $("#twofactorform").submit( function(eventObj) {
        $("<input />").attr("type", "hidden")
            .attr("name", "identity")
            .attr("value", visitorId)
            .appendTo("#twofactorform");
        return true;
        });
    });
</script>
<script async src="{{ asset('js/fp.min.js') }}" onload="initIdentity()"></script>
@endsection