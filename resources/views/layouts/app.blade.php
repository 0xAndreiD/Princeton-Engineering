<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Princeton Engineering</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" id="css-main" href="{{ asset('css/dashmix.css') }}">
    <link rel="stylesheet" id="css-custom" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <div id="page-container" class="enable-page-overlay side-scroll page-header-fixed page-header-dark page-header-glass main-content-narrow">
        <header id="page-header">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Company Text -->
                <div class="companyText">
                    <a class="link-fx font-size-lg text-black" href="http://www.princeton-engineering.com/">
                        Princeton Engineering Home Page
                    </a>
                </div>
                <!-- END Company Text -->

                <div>
                    @guest
                    <!-- Welcome Link -->
                    <div class="dropdown d-inline-block">
                        <a href="/" class="btn btn-dual" >
                            <span class="d-none d-sm-inline-block">Home</span>
                        </a>
                    </div>
                    <!-- Login Link -->
                    <div class="d-inline-block">
                        <a href="{{ route('login') }}" class="btn btn-dual" >
                            <span class="d-none d-sm-inline-block">Login</span>
                        </a>
                    </div>
                    <!-- Register Link -->
                    <!-- <div class="d-inline-block">
                        <a href="register" class="btn btn-dual" >
                            <span class="d-none d-sm-inline-block">Register</span>
                        </a>
                    </div> -->
                    @else
                    <!-- Welcome Link -->
                    <div class="dropdown d-inline-block">
                        <a href="{{ route('home') }}" class="btn btn-dual" >
                            <span class="d-none d-sm-inline-block">Home</span>
                        </a>
                    </div>
                    <div class="dropdown d-inline-block">
                        <a href = "{{ route('home') }}" class="btn btn-dual" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-fw fa-user d-sm-none"></i>
                            <span class="d-none d-sm-inline-block">{{ Auth::user()->username }}</span>
                            <i class="fa fa-fw fa-angle-down ml-1 d-none d-sm-inline-block"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="page-header-user-dropdown">
                            <div class="bg-primary-darker rounded-top font-w600 text-white text-center p-3">
                                User Options
                            </div>
                            <div class="p-2">
                                <a class="dropdown-item" href="be_pages_generic_profile.html">
                                    <i class="far fa-fw fa-user mr-1"></i> Profile
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_inbox.html">
                                    <span><i class="far fa-fw fa-envelope mr-1"></i> Inbox</span>
                                    <span class="badge badge-primary badge-pill">3</span>
                                </a>
                                <div role="separator" class="dropdown-divider"></div>

                                <!-- Toggle Side Overlay -->
                                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                <a class="dropdown-item" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_toggle">
                                    <i class="far fa-fw fa-building mr-1"></i> Settings
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="d-inline-block">
                        <a href="{{ route('logout') }}" class="btn btn-dual" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span class="d-none d-sm-inline-block">Signout</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    @endguest
                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->

            <!-- Header Search -->
            <div id="page-header-search" class="overlay-header bg-primary">
                <div class="content-header">
                    <form class="w-100" action="be_pages_generic_search.html" method="POST">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                <button type="button" class="btn btn-dual" data-toggle="layout" data-action="header_search_off">
                                    <i class="fa fa-fw fa-times-circle"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control border-0" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Header Search -->

            <!-- Header Loader -->
            <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
            <div id="page-header-loader" class="overlay-header bg-primary-darker">
                <div class="content-header">
                    <div class="w-100 text-center">
                        <i class="fa fa-fw fa-2x fa-sun fa-spin text-white"></i>
                    </div>
                </div>
            </div>
            <!-- END Header Loader -->
        </header>
    
        <main class="py-0">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/dashmix.core.min.js') }}"></script>
    <script src="{{ asset('js/dashmix.app.min.js') }}"></script>

</body>
</html>
