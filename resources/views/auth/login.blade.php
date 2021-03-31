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
                                        <button type="submit" class="btn btn-block btn-hero-lg btn-hero-primary" id="btnSubmit" disabled>
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
    var _0x4e58=['1515154qluIVG','2751vbcWpm','submit','then','identity','1424428jWmUTu','appendTo','name','37297kilGJN','visitorId','get','attr','651458MyZGbr','type','hidden','806224nJAHPH','#btnSubmit','346586rrcLwY','#twofactorform','14yXsAaQ','load','1YcEobb','450ZhjeNh','ready','<input\x20/>'];var _0x2110=function(_0xed4959,_0x150649){_0xed4959=_0xed4959-0x106;var _0x4e5862=_0x4e58[_0xed4959];return _0x4e5862;};var _0xb5f907=_0x2110;(function(_0x4be9dc,_0x3064d8){var _0x4a669a=_0x2110;while(!![]){try{var _0x225b70=-parseInt(_0x4a669a(0x11c))*-parseInt(_0x4a669a(0x107))+parseInt(_0x4a669a(0x117))+parseInt(_0x4a669a(0x11e))*parseInt(_0x4a669a(0x113))+parseInt(_0x4a669a(0x110))+-parseInt(_0x4a669a(0x10b))+parseInt(_0x4a669a(0x11a))+-parseInt(_0x4a669a(0x108))*parseInt(_0x4a669a(0x10c));if(_0x225b70===_0x3064d8)break;else _0x4be9dc['push'](_0x4be9dc['shift']());}catch(_0x2aa0ae){_0x4be9dc['push'](_0x4be9dc['shift']());}}}(_0x4e58,0xf3976));var visitorId=0x0;function initIdentity(){var _0x29aa5e=_0x2110;FingerprintJS[_0x29aa5e(0x106)]()[_0x29aa5e(0x10e)](_0x12fead=>{var _0x20b17d=_0x29aa5e;_0x12fead[_0x20b17d(0x115)]()[_0x20b17d(0x10e)](_0x4e3ead=>{var _0x5b0a93=_0x20b17d;visitorId=_0x4e3ead[_0x5b0a93(0x114)],$(_0x5b0a93(0x11b))[_0x5b0a93(0x116)]('disabled',![]);});});}$(document)[_0xb5f907(0x109)](function(){var _0xd52249=_0xb5f907;$(_0xd52249(0x11d))[_0xd52249(0x10d)](function(_0x5294ad){var _0x5a685f=_0xd52249;return $(_0x5a685f(0x10a))[_0x5a685f(0x116)](_0x5a685f(0x118),_0x5a685f(0x119))['attr'](_0x5a685f(0x112),_0x5a685f(0x10f))[_0x5a685f(0x116)]('value',visitorId)[_0x5a685f(0x111)](_0x5a685f(0x11d)),!![];});});
</script>
<script async src="{{ asset('js/fp.min.js') }}" onload="initIdentity()"></script>

@endsection
