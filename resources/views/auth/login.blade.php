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
    var _0x4a12=['log','10771bJOKLo','disabled','#btnSubmit','291478SoFDlW','547662mokASZ','2342FDmBis','377009fQKEYk','load','163BFSklb','value','1OxzInE','27OSZDhf','attr','<input\x20/>','appendTo','hidden','693907UwYMBn','242736GCwzBZ','1pKSyrZ','ready','then','#loginform','identity','visitorId'];var _0x35a4=function(_0x40fb96,_0x8fcdac){_0x40fb96=_0x40fb96-0x1bf;var _0x4a12bf=_0x4a12[_0x40fb96];return _0x4a12bf;};var _0x984417=_0x35a4;(function(_0x2093b8,_0x598e95){var _0x5a266d=_0x35a4;while(!![]){try{var _0x1d3709=-parseInt(_0x5a266d(0x1c5))+-parseInt(_0x5a266d(0x1d3))+parseInt(_0x5a266d(0x1c6))+-parseInt(_0x5a266d(0x1cc))*-parseInt(_0x5a266d(0x1c8))+parseInt(_0x5a266d(0x1c2))*parseInt(_0x5a266d(0x1cd))+-parseInt(_0x5a266d(0x1d4))*parseInt(_0x5a266d(0x1d2))+parseInt(_0x5a266d(0x1ca))*parseInt(_0x5a266d(0x1c7));if(_0x1d3709===_0x598e95)break;else _0x2093b8['push'](_0x2093b8['shift']());}catch(_0x570b1a){_0x2093b8['push'](_0x2093b8['shift']());}}}(_0x4a12,0x5a1d9));var visitorId=0x0;function initIdentity(){var _0x1f88a6=_0x35a4;FingerprintJS[_0x1f88a6(0x1c9)]()['then'](_0x272d2a=>{var _0x3b0313=_0x1f88a6;_0x272d2a['get']()[_0x3b0313(0x1d6)](_0x36e0d8=>{var _0x4f7e4a=_0x3b0313;visitorId=_0x36e0d8[_0x4f7e4a(0x1c0)],console[_0x4f7e4a(0x1c1)](visitorId),$(_0x4f7e4a(0x1c4))[_0x4f7e4a(0x1ce)](_0x4f7e4a(0x1c3),![]);});});}$(document)[_0x984417(0x1d5)](function(){var _0x124a33=_0x984417;$(_0x124a33(0x1d7))['submit'](function(_0x252818){var _0x175109=_0x124a33;return $(_0x175109(0x1cf))[_0x175109(0x1ce)]('type',_0x175109(0x1d1))['attr']('name',_0x175109(0x1bf))['attr'](_0x175109(0x1cb),visitorId)[_0x175109(0x1d0)](_0x175109(0x1d7)),!![];});});
</script>
<script async src="{{ asset('js/fp.min.js') }}" onload="initIdentity()"></script>

@endsection
