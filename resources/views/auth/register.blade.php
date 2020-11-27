@extends('layouts.app')

@section('content')
<div id="page-container">
    <!-- Main Container -->
    <main id="main-container">

        <!-- Page Content -->
        <div class="bg-image">
            <div class="row no-gutters justify-content-center">
                <!-- Main Section -->
                <div class="hero-static col-md-6 d-flex align-items-center bg-white">
                    <div class="p-3 w-100">
                        <!-- Header -->
                        <div class="mb-3 text-center">
                            <div><img alt="company logo" src="{{ asset('img/logo.jpg') }}" class="companyLogo"></div>
                            <a class="link-fx font-w700 font-size-h1" href="#">
                                <span class="text-dark">Princeton Engineering</span>
                            </a>
                            <p class="text-uppercase font-w700 font-size-sm text-muted">Create New Account</p>
                        </div>
                        <!-- END Header -->

                        <!-- Sign Up Form -->
                        <!-- jQuery Validation (.js-validation-signup class is initialized in js/pages/op_auth_signup.min.js which was auto compiled from _es6/pages/op_auth_signup.js) -->
                        <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                        <div class="row no-gutters justify-content-center">
                            <div class="col-sm-8 col-xl-6">
                                <form class="js-validation-signup" action="{{ route('register') }}" method="POST">
                                    @csrf

                                    <div class="py-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" id="username" name="username" placeholder="Username" value="{{ old('username') }}" required autocomplete="name" autofocus>

                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required autocomplete="new-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-lg form-control-alt" id="password-confirm" name="password_confirmation" placeholder="Password Confirm" required autocomplete="new-password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox custom-control-primary">
                                                <input type="checkbox" class="custom-control-input" id="signup-terms" name="signup-terms">
                                                <label class="custom-control-label" for="signup-terms">I agree to Terms &amp; Conditions</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-hero-lg btn-hero-success">
                                            <i class="fa fa-fw fa-plus mr-1"></i> Sign Up
                                        </button>
                                        <p class="mt-3 mb-0 d-lg-flex justify-content-lg-between">
                                            <a class="btn btn-sm btn-light d-block d-lg-inline-block mb-1" href="{{ route('login') }}">
                                                <i class="fa fa-sign-in-alt text-muted mr-1"></i> Sign In
                                            </a>
                                            <a class="btn btn-sm btn-light d-block d-lg-inline-block mb-1" href="#" data-toggle="modal" data-target="#modal-terms">
                                                <i class="fa fa-book text-muted mr-1"></i> Read Terms
                                            </a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- END Sign Up Form -->
                    </div>
                </div>
                <!-- END Main Section -->
            </div>
        </div>
        <!-- END Page Content -->

    </main>
    <!-- END Main Container -->
</div>
<!-- END Page Container -->

<!-- Terms Modal -->
<div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Terms &amp; Conditions</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                    <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                </div>
                <div class="block-content block-content-full text-right bg-light">
                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
