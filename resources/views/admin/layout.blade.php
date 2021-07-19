<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Super Admin Panel</title>

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
        
        <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
        
        <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}" >
        <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap.min.css">

        <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{ asset('js/plugins/jstree/themes/default/style.min.css') }}">
        <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
        <!-- END Stylesheets -->

        {{-- <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script> --}}
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/sweetalert.min.js') }}" ></script>
       
        <script src="{{ asset('js/dashmix.core.min.js') }}"></script>
        <script src="{{ asset('js/dashmix.app.min.js') }}"></script>
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
                    <span class="badge badge-pill badge-danger">Super Admin</span>
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
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('reference') }}">
                            <i class="nav-main-link-icon fas fa-info-circle"></i>
                                <span class="nav-main-link-name">Reference</span>
                            </a>
                        </li>
                        <li class="nav-main-heading">Users &amp; Companies</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('userList') }}">
                                <i class="nav-main-link-icon fa fa-user"></i>
                                <span class="nav-main-link-name">Manage Users</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('companyList') }}">
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
                        <li class="nav-main-heading">Standard Products</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('standardModule') }}">
                                <i class="nav-main-link-icon fa fa-solar-panel"></i>
                                <span class="nav-main-link-name">Modules</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('standardInverter') }}">
                                <i class="nav-main-link-icon fa fa-dharmachakra"></i>
                                <span class="nav-main-link-name">Inverters</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('standardRacking') }}">
                                <i class="nav-main-link-icon fab fa-resolving"></i>
                                <span class="nav-main-link-name">Rail Support</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('standardStanchion') }}">
                                <i class="nav-main-link-icon fa fa-wrench"></i>
                                <span class="nav-main-link-name">Stanchions</span>
                            </a>
                        </li>
                        <li class="nav-main-heading">Custom Products</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('customModule') }}">
                                <i class="nav-main-link-icon fa fa-solar-panel"></i>
                                <span class="nav-main-link-name">Modules</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('customInverter') }}">
                                <i class="nav-main-link-icon fa fa-dharmachakra"></i>
                                <span class="nav-main-link-name">Inverters</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('customRacking') }}">
                                <i class="nav-main-link-icon fab fa-resolving"></i>
                                <span class="nav-main-link-name">Solar Racking</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('customStanchion') }}">
                                <i class="nav-main-link-icon fa fa-wrench"></i>
                                <span class="nav-main-link-name">Stanchions</span>
                            </a>
                        </li>
                        <li class="nav-main-heading">Administrator Tools</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('backupInput') }}">
                                <i class="nav-main-link-icon fa fa-server"></i>
                                <span class="nav-main-link-name">Inputs Backup / Restore</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('backupDB') }}">
                                <i class="nav-main-link-icon fa fa-database"></i>
                                <span class="nav-main-link-name">DB Backup / Restore</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('guardlist') }}">
                                <i class="nav-main-link-icon fa fa-tablet-alt"></i>
                                <span class="nav-main-link-name">Allowed Logins</span>
                            </a>
                        </li>
                        <li class="nav-main-heading">Configuration</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('permit') }}">
                                <i class="nav-main-link-icon fa fa-file-pdf"></i>
                                <span class="nav-main-link-name">Permit PDF</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('settings') }}">
                                <i class="nav-main-link-icon fa fa-cogs"></i>
                                <span class="nav-main-link-name">Settings</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('myaccount') }}">
                                <i class="nav-main-link-icon fa fa-user-edit"></i>
                                <span class="nav-main-link-name">My account</span>
                            </a>
                        </li>
                        <li class="nav-main-heading">Seal Position</li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('sealtemplate') }}">
                                <i class="nav-main-link-icon fa fa-expand-arrows-alt"></i>
                                <span class="nav-main-link-name">Create Template</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('sealassign') }}">
                                <i class="nav-main-link-icon fa fa-flag-usa"></i>
                                <span class="nav-main-link-name">Assign Template</span>
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
                        <div class="d-inline-block">
                            <a type="button" class="btn btn-dual" href="{{ route('home') }}" >
                                <span class="d-none d-sm-inline ml-1">Home</span>
                            </a>
                        </div>
                        <!-- END Home Link -->
                        <!-- Term Link -->
                        <div class="d-inline-block">
                            <a type="button" class="btn btn-dual" data-toggle="modal" data-target="#modal-terms">
                                Read Terms
                            </a>
                        </div>
                        <!-- END Terms Link -->
                        <!-- Signout Form -->
                        <div class="d-inline-block">
                            <a class="btn btn-dual" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="d-none d-sm-inline ml-1">Signout</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                        <!-- END Signout Form -->
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
                            .
                        </div>
                        <div class="col-sm-6 order-sm-1 text-center text-sm-left">
                            <span >Copyright © 2020 Richard Pantel. All Rights Reserved</span>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

        <!-- Terms Modal -->
        <div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Terms and Conditions of Use</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content termsDlg">
                            <h5>1. Terms / Conditions / Privacy Policies</h5>
                            <p>We may collect personal identification information from Users in a variety of ways, including, but not limited to, when Users visit our site, place an order and in connection with other activities, services, features or resources we make available on our Site. Users may be asked for, as appropriate, name, email address, mailing address, phone number, credit card information,</p>
                            <p>Users may, however, visit the public portions of our Site anonymously.</p>
                            <p>We will collect personal identification information from Users only if they voluntarily submit such information to us. Users can always refuse to supply personally identification information, except that it may prevent them from engaging in certain Site related activities.</p>
                            <h6>Non-personal identification information</h6>
                            <p>We may collect non-personal identification information about Users whenever they interact with our Site. Non-personal identification information may include the browser name, the type of computer and technical information about Users means of connection to our Site, such as the operating system and the Internet service providers utilized and other similar information.</p>
                            <h6>Web browser cookies</h6>
                            <p>Our Site may use "cookies" to enhance User experience. User's web browser places cookies on their hard drive for record-keeping purposes and sometimes to track information about them. User may choose to set their web browser to refuse cookies, or to alert you when cookies are being sent. If they do so, note that some parts of the Site may not function properly.</p>
                            <h6>How we use collected information</h6>
                            <p>TECTONICORP, P.C. dba Princeton Engineering,: collects and uses Users personal information for the following purposes:</p>
                            <p><p style="font-weight: bold; margin-bottom: 0rem">To process transactions</p>
                            We may use the information Users provide about themselves when placing an order only to provide service to that order. We do not share this information with outside parties except to the extent necessary to provide the service.
                            </p>
                            <p><p style="font-weight: bold; margin-bottom: 0rem">To send periodic emails</p>
                            The email address Users provide for order processing, will only be used to send them information and updates pertaining to their order. It may also be used to respond to their inquiries, and/or other requests or questions. If User decides to opt-in to our mailing list, they will receive emails that may include company news, updates, related product or service information, etc. If at any time the User would like to unsubscribe from receiving future emails, we include detailed unsubscribe instructions at the bottom of each email or User may contact us via our Site.
                            </p>
                            <h6>How we protect your information</h6>
                            <p>We adopt appropriate data collection, storage and processing practices and security measures to protect against unauthorized access, alteration, disclosure or destruction of your personal information, username, password, transaction information and data stored on our Site.</p>
                            <p>Sensitive and private data exchange between the Site and its Users happens over a SSL secured communication channel and is encrypted and protected with digital signatures. </p>
                            <h6>Third party websites</h6>
                            <p>Users may find advertising or other content on our Site that link to the sites and services of our partners, suppliers, advertisers, sponsors, licensors and other third parties. We do not control the content or links that appear on these sites and are not responsible for the practices employed by websites linked to or from our Site. In addition, these sites or services, including their content and links, may be constantly changing. These sites and services may have their own privacy policies and customer service policies. Browsing and interaction on any other website, including websites which have a link to our Site, is subject to that website\'s own terms and policies.</p>
                            <h6>Changes to this privacy policy</h6>
                            <p><span style="font-weight: bold;">TECTONICORP, P.C. dba Princeton Engineering</span> has the discretion to update this privacy policy at any time. When we do, revise the updated date at the bottom of this page,. We encourage Users to frequently check this page for any changes to stay informed about how we are helping to protect the personal information we collect. You acknowledge and agree that it is your responsibility to review this privacy policy periodically and become aware of modifications.</p>
                            <h6>Your acceptance of these terms</h6>
                            <p>By using this Site, you signify your acceptance of this policy and terms of service. If you do not agree to this policy, please do not use our Site. Your continued use of the Site following the posting of changes to this policy will be deemed your acceptance of those changes.</p>
                            <h6>Contacting us</h6>
                            <p>If you have any questions about this Privacy Policy, the practices of this site, or your dealings with this site, please contact us at: info@Princeton-Engineering.com or you can call us at 908-507-5500.</p>
                            
                            <h5>2. Refunds</h5>
                            <p>Customers using a credit card are entitled to a refund if the cardholder is unhappy with the services rendered by Princeton Engineering. Refund requests must be sent to info@Princeton-Engineering.com with the subject line "REFUND REQUESTED".  Provide identification of the project and the reason for the refund request.  Refund requests made after 7 calendar days of the provision of services will be declined. Approved refund requests shall be processed and a refund will be issued.</p>

                            <h5>3. NDA</h5>
                            <p>You or a company representative has signed the MUTUAL NONDISCLOSURE AGREEMENT [a copy of which can be found <a href="{{ asset('pdf/Princeton NDA Form.pdf') }}">HERE</a>] and you and your company agree to be bound by its terms.</p>

                            <h5>4. Authorized Use of iRoof.</h5>
                            <p>By using iRoof, you agree that use of iRoof is limited to the registered client and its employees.  iRoof report distribution will be limited to provision to AHJ's and for internal record storage.  You are prohibited from providing copies of iRoof reports or allowing access into the online portal of iRoof to any unauthorized users or competitors of Princeton Engineering.  You agree that Princeton Engineering and Richard Pantel have made substantial intellectual and monetary investments into iRoof and any unauthorized use of iRoof or distribution of iRoof reports or copying or reverse engineering of any parts of the iRoof web site will result in financial damages to Princeton Engineering and Richard Pantel.  Breaches of this agreement will be pursued through litigation.  All parties related to said unauthorized access or document distribution will be enjoined in such litigation.</p>

                            <h5>5. Cancellation</h5>
                            <p>Princeton Engineering reserves the right to cancel client accounts or user access for any reason, including the client or the client's employees allowing unauthorized access into iRoof.  Unauthorized access includes access by persons who are not direct employees of a registered client.</p>

                            <h5>6. Copyright</h5>
                            <p>Copyright © 2020 Richard Pantel. All Rights Reserved.</p>
                            <p>No parts of this data input form or related calculation reports may be copied in format,
                            content or intent, or reproduced in any form or by any electronic or mechanical
                            means, including information storage and retrieval systems, without
                            permission in writing from the author.<span>&nbsp;
                            </span>Further, disassembly or reverse engineering of this data input form
                            or related calculation reports is strictly prohibited.</p>
                            <p>The author's contact
                            information is: RPantel@Princeton-Engineering.com, web-site:
                            www.Princeton-Engineering.com; tel: 908-507-5500</p>
                        </div>
                        <div class="block-content block-content-full text-right bg-light">
                            <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Done</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        

        <!-- Modal JS Plugins -->
        <script src="{{ asset('js/plugins/es6-promise/es6-promise.auto.min.js') }}"></script>
        <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('js/pages/be_comp_dialogs.min.js') }}"></script>

        <!-- Page JS Plugins -->
        {{-- <script src="https://cdn.datatables.net/download/build/nightly/jquery.dataTables.js"></script> --}}
        <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('js/plugins/datatables/buttons/dataTables.buttons.min.js')}}"></script>
        <script src="{{ asset('js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
        <script src="{{ asset('js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
        <script src="{{ asset('js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.6/js/responsive.bootstrap.min.js"></script>

        <!-- Page JS Plugins -->
        <script src="{{ asset('js/plugins/jquery.sparkline.min.js') }}"></script>
        <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>

        <!-- Date Time Picker Plugins -->
        <script src="{{ asset('js/plugins/flatpickr/flatpickr.min.js') }}"></script>

        <!-- Bootstrap Date Range Plugins -->
        <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        
        <!-- Page JS Helpers (jQuery Sparkline plugin) -->
        <script>jQuery(function(){ Dashmix.helpers(['flatpickr', 'datepicker', 'select2', 'sparkline', 'notify']); });</script>
        <script src="{{ asset('/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('/js/plugins/jquery-validation/additional-methods.js') }}"></script>

        <script src="{{ asset('js/pages/be_forms_validation.js') }}"></script>
    </body>
</html>
