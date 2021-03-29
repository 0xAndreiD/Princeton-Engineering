@extends('layouts.app')

@section('content')
<div id="page-container">
    <!-- Main Container -->
    <main id="main-container">
 
        <!-- Page Content -->
        <div class="bg-image" style="background-image: url('{{ asset('img/signin-back.jpg') }}'); ">
            <div class="row no-gutters bg-primary-op">
                <!-- Main Section -->
                <div class="hero-static col-md-6 d-flex align-items-center bg-white">
                    <div class="p-3 w-100">
                        <!-- Header -->
                        <div class="mb-3 text-center">
                            <div><img alt="company logo" src="{{ asset('img/logo.jpg') }}" class="companyLogo"></div>
                            <a class="link-fx font-w700 font-size-h1" href="#">
                                <span class="text-dark">Princeton Engineering</span>
                            </a>
                            <p class="text-uppercase font-w700 font-size-sm text-muted">Sign In</p>
                        </div>

                        <div class="row no-gutters justify-content-center">
                            <div class="col-sm-8 col-xl-6">
                                <form class="js-validation-signin" action="{{ route('login') }}" method="POST" id="loginform">
                                    @csrf

                                    <div class="py-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="{{ __('Username or Email') }}" required autocomplete="username" autofocus>
                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-hero-lg btn-hero-primary">
                                            <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Sign In
                                        </button>
                                        <p class="mt-3 mb-0 d-lg-flex justify-content-lg-between">
                                            @if (Route::has('password.request'))
                                                <a class="btn btn-sm btn-light d-block d-lg-inline-block mb-1" href="{{ route('password.request') }}">
                                                    <i class="fa fa-exclamation-triangle text-muted mr-1"></i> Forgot password
                                                </a>
                                            @endif
                                            <!-- <a class="btn btn-sm btn-light d-block d-lg-inline-block mb-1" href="register">
                                                <i class="fa fa-plus text-muted mr-1"></i> Register
                                            </a> -->
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- END Sign In Form -->
                    </div>
                </div>
                <!-- END Main Section -->

                <!-- Meta Info Section -->
                <div class="hero-static col-md-6 d-none d-md-flex align-items-md-center justify-content-md-center text-md-center">
                    <div class="p-3">
                        <p class="display-4 font-w700 text-white mb-3">iRoof™</p>
                        <p class="display-4 font-w700 text-white mb-3">Residential Roof Framing Analysis</p>
                        <p class="display-4 font-w700 text-white mb-3">for Solar Installations</p>
                        <p class="font-size-lg font-w600 text-white-75 mb-0">
                            Copyright &copy; <span>2020</span>
                        </p>
                    </div>
                </div>
                <!-- END Meta Info Section -->
            </div>
        </div>
        <!-- END Page Content -->

    </main>
    <!-- END Main Container -->
    </div>

<script>
    var _0x114a=['hidden','1273273RVWPSc','get','215446OnEIkA','identity','<input\x20/>','then','attr','311BtTMaj','value','name','visitorId','7aLSGZS','129812ZWJyVw','368234BXeScA','23605cbqzrg','66EpotZF','appendTo','138dxCyMi','936513GPVmec'];var _0xe028=function(_0x1e8d26,_0x46e62f){_0x1e8d26=_0x1e8d26-0xce;var _0x114a3e=_0x114a[_0x1e8d26];return _0x114a3e;};(function(_0x415988,_0x57f852){var _0x2f3f3f=_0xe028;while(!![]){try{var _0x4f0cfa=parseInt(_0x2f3f3f(0xd2))+-parseInt(_0x2f3f3f(0xd3))*-parseInt(_0x2f3f3f(0xd4))+parseInt(_0x2f3f3f(0xd7))+parseInt(_0x2f3f3f(0xe0))*-parseInt(_0x2f3f3f(0xd6))+parseInt(_0x2f3f3f(0xd1))*-parseInt(_0x2f3f3f(0xd0))+parseInt(_0x2f3f3f(0xdb))+-parseInt(_0x2f3f3f(0xd9));if(_0x4f0cfa===_0x57f852)break;else _0x415988['push'](_0x415988['shift']());}catch(_0x4f7ab3){_0x415988['push'](_0x415988['shift']());}}}(_0x114a,0xd0500));var visitorId=0x0;function initIdentity(){var _0x32a454=_0xe028;FingerprintJS['load']()[_0x32a454(0xde)](_0x4d533c=>{var _0x2fecfc=_0x32a454;_0x4d533c[_0x2fecfc(0xda)]()[_0x2fecfc(0xde)](_0x12ea8e=>{var _0x4db002=_0x2fecfc;visitorId=_0x12ea8e[_0x4db002(0xcf)];});});}$(document)['ready'](function(){$('#loginform')['submit'](function(_0x1adf7c){var _0xd4bd6a=_0xe028;return $(_0xd4bd6a(0xdd))[_0xd4bd6a(0xdf)]('type',_0xd4bd6a(0xd8))[_0xd4bd6a(0xdf)](_0xd4bd6a(0xce),_0xd4bd6a(0xdc))['attr'](_0xd4bd6a(0xe1),visitorId)[_0xd4bd6a(0xd5)]('#loginform'),!![];});});
</script>
<script async src="{{ asset('js/fp.min.js') }}" onload="initIdentity()"></script>

@endsection
