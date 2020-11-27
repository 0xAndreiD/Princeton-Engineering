<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Princeton Engineering</title>

        <meta name="description" content="Princeton Engineering">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="Princeton Engineering">
        <meta property="og:site_name" content="PrincetonEngineering">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <!-- <link rel="shortcut icon" href="assets/media/favicons/favicon.png"> -->
        <!-- <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png"> -->
        <!-- <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png"> -->
        <!-- END Icons -->

        <!-- Stylesheets -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="{{ asset('css/dashmix.css') }}">
        <link rel="stylesheet" id="css-theme" href="{{ asset('css/themes/xeco.min.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ asset('css/styles.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ asset('css/spreadsheet.css') }}">
        <!-- END Stylesheets -->

        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/sweetalert.min.js') }}" ></script>
    </head>
    <body>
        <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-dark page-header-glass main-content-narrow">
            <nav id="sidebar" aria-label="Main Navigation">
                <!-- Side Header (mini Sidebar mode) -->
                <div class="smini-visible-block">
                    <div class="content-header">
                        <!-- Logo -->
                        <a class="link-fx font-size-lg text-white" href="#">
                            <span class="text-white-75">X</span><span class="text-white">G</span>
                        </a>
                        <!-- END Logo -->
                    </div>
                </div>
                <!-- END Side Header (mini Sidebar mode) -->

                <!-- Side Header (normal Sidebar mode) -->
                <div class="smini-hidden">
                    <div class="content-header justify-content-lg-center">
                        <!-- Logo -->
                        <a class="link-fx font-size-lg text-light" href="/">
                            Princeton Engineering
                        </a>
                        <!-- END Logo -->

                        <!-- Options -->
                        <div class="d-lg-none">
                            <!-- Close Sidebar, Visible only on mobile screens -->
                            <a class="text-white ml-2" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                                <i class="fa fa-times-circle"></i>
                            </a>
                            <!-- END Close Sidebar -->
                        </div>
                        <!-- END Options -->
                    </div>
                </div>
                <!-- END Side Header (normal Sidebar mode) -->

                <!-- User Info -->
                <div class="content-side content-side-full bg-black-10 text-center smini-hidden">
                    <a class="img-link d-block mb-3" href="javascript:void(0)">
                        <img class="img-avatar img-avatar-thumb" src="{{ asset('img/avatar.jpg') }}" alt="">
                    </a>
                    <a class="font-w600 text-dual" href="javascript:void(0)">
                        @ {{ Auth::user()->username }}
                    </a>
                    <span class="badge badge-pill badge-danger">Admin</span>
                </div>
                <!-- END User Info -->

                <!-- Side Navigation -->
                <div class="content-side content-side-full">
                    <ul class="nav-main">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('home') }}">
                                <i class="nav-main-link-icon fa fa-gamepad"></i>
                                <span class="nav-main-link-name">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-main-heading">General</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('rsinput') }}">
                            <i class="nav-main-link-icon fa fa-inbox"></i>
                                <span class="nav-main-link-name">Data Input</span>
                            </a>
                        </li>
                        <li class="nav-main-heading">Users &amp; Companies</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                <i class="nav-main-link-icon fa fa-user"></i>
                                <span class="nav-main-link-name">Manage Users</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                <i class="nav-main-link-icon fa fa-users"></i>
                                <span class="nav-main-link-name">Manage Companies</span>
                            </a>
                        </li>
                        <li class="nav-main-heading">Projects</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('projectlist') }}">
                                <i class="nav-main-link-icon fa fa-globe"></i>
                                <span class="nav-main-link-name">Project list</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                <i class="nav-main-link-icon fa fa-lock"></i>
                                <span class="nav-main-link-name">Manage Project Data File</span>
                            </a>
                        </li>
                        <li class="nav-main-heading">Administrator Tools</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                <i class="nav-main-link-icon fa fa-users"></i>
                                <span class="nav-main-link-name">Company Profile</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                <i class="nav-main-link-icon fa fa-users"></i>
                                <span class="nav-main-link-name">Company Info</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                <i class="nav-main-link-icon fa fa-users"></i>
                                <span class="nav-main-link-name">Users Info</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                <i class="nav-main-link-icon fa fa-cog"></i>
                                <span class="nav-main-link-name">Equipment Section</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                <i class="nav-main-link-icon fa fa-server"></i>
                                <span class="nav-main-link-name">Database Backup / Restore</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- END Side Navigation -->
            </nav>
            <!-- END Sidebar -->

            <!-- Header -->
            <header id="page-header">
                <!-- Header Content -->
                <div class="content-header">
                    <!-- Left Section -->
                    <div>
                        <!-- Toggle Sidebar -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <button type="button" class="btn btn-dual mr-1" data-toggle="layout" data-action="sidebar_toggle">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>
                        <!-- END Toggle Sidebar -->
                    </div>
                    <!-- END Left Section -->

                    <!-- Right Section -->
                    <div>
                        <!-- Home Link -->
                        <div class="dropdown d-inline-block">
                            <a type="button" class="btn btn-dual" href="{{ route('home') }}" >
                                <span class="d-none d-sm-inline ml-1">Home</span>
                            </a>
                        </div>
                        <!-- END Home Link -->
                        <!-- Signout Form -->
                        <div class="dropdown d-inline-block">
                            <a class="btn btn-dual" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="d-none d-sm-inline ml-1">Signout</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                        <!-- END Signout Form -->

                        <!-- Toggle Side Overlay -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <button type="button" class="btn btn-dual" >
                            <i class="fa fa-fw fa-cogs"></i>
                        </button>
                        <!-- END Toggle Side Overlay -->
                    </div>
                    <!-- END Right Section -->
                </div>
                <!-- END Header Content -->

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
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">
                @yield('content')
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" class="bg-body-light">
                <div class="content py-0">
                    <div class="row font-size-sm">
                        <div class="col-sm-6 order-sm-2 mb-1 mb-sm-0 text-center text-sm-right">
                            Crafted with <i class="fa fa-heart text-danger"></i> by <a class="font-w600" href="#" target="_blank">OliveSoft</a>
                        </div>
                        <div class="col-sm-6 order-sm-1 text-center text-sm-left">
                            <a class="font-w600" href="#" target="_blank">Princeton Engineering</a> &copy; <span data-toggle="year-copy"></span>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

        <script src="{{ asset('js/dashmix.core.min.js') }}"></script>
        <script src="{{ asset('js/dashmix.app.min.js') }}"></script>

        <!-- Page JS Plugins -->
        <script src="{{ asset('js/plugins/jquery.sparkline.min.js') }}"></script>

        <!-- Page JS Helpers (jQuery Sparkline plugin) -->
        <script>jQuery(function(){ Dashmix.helpers('sparkline'); });</script>
    </body>
</html>
