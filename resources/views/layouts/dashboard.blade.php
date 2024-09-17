<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $site_title }} | {{ $page_title }}</title>
    <!--[if lt IE 10]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta property="og:title" content="{{ $basic->title }}">
    <meta name="description" content="{{ $basic->description }}" />
    <meta name="keywords" content="{{ $basic->meta_tag }}" />
    <meta name="author" content="{{ $basic->author }}" />

    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/font-awesome.min.css') }}">
    @yield('import_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/jquery.mCustomScrollbar.css') }}">
    @yield('style')

</head>

<body>

    <div class="theme-loader">
        <div class="loader-track">
            <div class="loader-bar"></div>
        </div>
    </div>

    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">
                    <div class="navbar-logo">
                        <a class="mobile-menu" id="mobile-collapse" href="#">
                            <i class="ti-menu"></i>
                        </a>
                        <a href="{{ route('home') }}">
                            <img class="img-fluid" src="{{ asset('assets/images/logo.png') }}" alt="{{ $site_title }}" style="width: 180px;" />
                        </a>
                        <a class="mobile-options">
                            <i class="ti-more"></i>
                        </a>
                    </div>
                    <div class="navbar-container container-fluid">
                        <ul class="nav-left">
                            <li>
                                <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                            </li>
                            <li>
                                <a href="#" onclick="javascript:toggleFullScreen()">
                                    <i class="ti-fullscreen"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav-right">
                            <li class="user-profile header-notification">
                                <a href="#">
                                    <img src="{{ asset('assets/images') }}/{{ Auth::user()->image }}" class="img-radius" alt="{{ Auth::user()->name }}">
                                    <span>{{ Auth::user()->name }}</span>
                                    <i class="ti-angle-down"></i>
                                </a>
                                <ul class="show-notification profile-notification">

                                    <li>
                                        <a href="{{ route('edit-profile') }}">
                                            <i class="ti-user"></i> Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin-change-password') }}">
                                            <i class="ti-pencil-alt"></i> Change Password
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.logout') }}">
                                            <i class="ti-share"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <nav class="pcoded-navbar">
                        <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                        <div class="pcoded-inner-navbar main-menu">
                            <div class="pcoded-navigation-label">General setting</div>
                            <ul class="pcoded-item pcoded-left-item">
                                <li class="{{ Request::is('admin-dashboard') ? 'active' : '' }}">
                                    <a href="{{ route('dashboard') }}">
                                        <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                                        <span class="pcoded-mtext">Dashboard</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="pcoded-hasmenu @if (Request::is('admin/signal-create') or Request::is('admin/signal-all')) active pcoded-trigger @endif " dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="ti-stats-up"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">Manage Signal</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/signal-create') ? 'active' : '' }}">
                                            <a href="{{ route('signal-create') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">New Signal</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/signal-all') ? 'active' : '' }}">
                                            <a href="{{ route('signal-all') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">All Signal</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="pcoded-hasmenu @if (Request::is('admin/manage-asset') or Request::is('admin/manage-status') or Request::is('admin/manage-frame') or Request::is('admin/manage-type') or Request::is('admin/manage-symbol')) active pcoded-trigger @endif" dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="ti-settings"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">Signal Tools</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/manage-asset') ? 'active' : '' }}">
                                            <a href="{{ route('manage-asset') }}">
                                                <span class="pcoded-micon"><i class="ti-layout-grid2"></i><b>D</b></span>
                                                <span class="pcoded-mtext">Manage Assets</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/manage-symbol') ? 'active' : '' }}">
                                            <a href="{{ route('manage-symbol') }}">
                                                <span class="pcoded-micon"><i class="ti-layout-grid2"></i><b>D</b></span>
                                                <span class="pcoded-mtext">Manage Symbol</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>

                                        <li class="{{ Request::is('admin/manage-type') ? 'active' : '' }}">
                                            <a href="{{ route('manage-type') }}">
                                                <span class="pcoded-micon"><i class="ti-layout-grid2"></i><b>D</b></span>
                                                <span class="pcoded-mtext">Manage Type</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>

                                        <li class="{{ Request::is('admin/manage-frame') ? 'active' : '' }}">
                                            <a href="{{ route('manage-frame') }}">
                                                <span class="pcoded-micon"><i class="ti-layout-grid2"></i><b>D</b></span>
                                                <span class="pcoded-mtext">Manage Frame</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>

                                        <li class="{{ Request::is('admin/manage-status') ? 'active' : '' }}">
                                            <a href="{{ route('manage-status') }}">
                                                <span class="pcoded-micon"><i class="ti-layout-grid2"></i><b>D</b></span>
                                                <span class="pcoded-mtext">Manage Status</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="pcoded-hasmenu @if (Request::is('admin/plan-create') or Request::is('admin/plan-all')) active pcoded-trigger @endif " dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="ti-layout-column3"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">Manage Plan</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/plan-create') ? 'active' : '' }}">
                                            <a href="{{ route('plan-create') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">New Plan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/plan-all') ? 'active' : '' }}">
                                            <a href="{{ route('plan-all') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">All Plan</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="pcoded-hasmenu @if (Request::is('admin/post-create') or Request::is('admin/post-all')) active pcoded-trigger @endif " dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="fa fa-newspaper-o"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">Manage Blog</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/post-create') ? 'active' : '' }}">
                                            <a href="{{ route('post-create') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">New Blog</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/post-all') ? 'active' : '' }}">
                                            <a href="{{ route('post-all') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">All Blog</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="{{ Request::is('admin/manage-category') ? 'active' : '' }}">
                                    <a href="{{ route('manage-category') }}">
                                        <span class="pcoded-micon"><i class="ti-layout-grid2"></i><b>D</b></span>
                                        <span class="pcoded-mtext">Blog Category</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('admin/speciality-control') ? 'active' : '' }}">
                                    <a href="{{ route('speciality-control') }}">
                                        <span class="pcoded-micon"><i class="ti-package"></i><b>D</b></span>
                                        <span class="pcoded-mtext">Manage Speciality</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>

                                <li class="pcoded-hasmenu @if (Request::is('admin/payment-method') or Request::is('admin/manual-payment-method') or Request::is('admin/manual-payment-request')) active pcoded-trigger @endif " dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="fa fa-credit-card"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">Payment Gateway</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/payment-method') ? 'active' : '' }}">
                                            <a href="{{ route('payment-method') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Automated Gateway</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/manual-payment-method') ? 'active' : '' }}">
                                            <a href="{{ route('manual-payment-method') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Manual Gateway</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/manual-payment-request') ? 'active' : '' }}">
                                            <a href="{{ route('manual-payment-request') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Payment Request</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="pcoded-hasmenu @if (Request::is('admin/withdraw-method') or Request::is('admin/withdraw-create') or Request::is('admin/withdraw-request') or Request::is('admin/withdraw-request-pending') or Request::is('admin/withdraw-request-success') or Request::is('admin/withdraw-request-refund') or Request::is('admin/withdraw-request-pending')) active pcoded-trigger @endif " dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="fa fa-credit-card-alt"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">Manage Withdraw</span>
                                        <span class="pcoded-badge label label-danger" style="margin-right: -5px;">{{ \App\Models\WithdrawLog::whereStatus(0)->count() }}</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/withdraw-method') ? 'active' : '' }}">
                                            <a href="{{ route('withdraw-method') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Withdraw Method</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/withdraw-request') ? 'active' : '' }}">
                                            <a href="{{ route('withdraw-request') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Withdraw Request</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/withdraw-request-pending') ? 'active' : '' }}">
                                            <a href="{{ route('withdraw-request-pending') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Pending Request</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/withdraw-request-success') ? 'active' : '' }}">
                                            <a href="{{ route('withdraw-request-success') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Confirm Request</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/withdraw-request-refund') ? 'active' : '' }}">
                                            <a href="{{ route('withdraw-request-refund') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Refund Request</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="pcoded-hasmenu @if (Request::is('admin/manage-subscriber') or Request::is('admin/subscriber-message') or Request::is('admin/subscriber-message-list')) active pcoded-trigger @endif " dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="ti-themify-favicon"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">Manage Subscriber</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/manage-subscriber') ? 'active' : '' }}">
                                            <a href="{{ route('manage-subscriber') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Subscriber List</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/subscriber-message') ? 'active' : '' }}">
                                            <a href="{{ route('subscriber-message') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Send Message</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/subscriber-message-list') ? 'active' : '' }}">
                                            <a href="{{ route('subscriber-message-list') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Send Message List</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>


                                <li class="pcoded-hasmenu @if (Request::is('admin/user-create') or Request::is('admin/manage-user')) active pcoded-trigger @endif " dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="ti-user"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">Manage Users</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/user-create') ? 'active' : '' }}">
                                            <a href="{{ route('user-create') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Add User</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/manage-user') ? 'active' : '' }}">
                                            <a href="{{ route('manage-user') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">All User</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="pcoded-hasmenu @if (Request::is('admin/staff-create') or Request::is('admin/manage-staff')) active pcoded-trigger @endif " dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="fa fa-user-secret"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">Manage Staff</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/staff-create') ? 'active' : '' }}">
                                            <a href="{{ route('staff-create') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Add Staff</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/manage-staff') ? 'active' : '' }}">
                                            <a href="{{ route('manage-staff') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">All Staff</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <div class="pcoded-navigation-label">Basic control</div>
                            <ul class="pcoded-item pcoded-left-item">
                                <li class="{{ Request::is('admin/basic-setting') ? 'active' : '' }} ">
                                    <a href="{!! route('basic-setting') !!}">
                                        <span class="pcoded-micon"><i class="ti-settings"></i><b>BS</b></span>
                                        <span class="pcoded-mtext">Basic Setting</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('admin/referral-setting') ? 'active' : '' }}">
                                    <a href="{!! route('referral-setting') !!}">
                                        <span class="pcoded-micon"><i class="ti-credit-card"></i><b>GR</b></span>
                                        <span class="pcoded-mtext">Referral Setting</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('admin/google-recaptcha') ? 'active' : '' }}">
                                    <a href="{!! route('google-recaptcha') !!}">
                                        <span class="pcoded-micon"><i class="ti-control-shuffle"></i><b>GR</b></span>
                                        <span class="pcoded-mtext">Google Recaptcha</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>

                                <li class="pcoded-hasmenu @if (Request::is('admin/email-drivers') or Request::is('admin/email-template')) active pcoded-trigger @endif " dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="ti-settings"></i><b>IM</b></span>
                                        <span class="pcoded-mtext">Email Settings</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/email-drivers') ? 'active' : '' }}">
                                            <a href="{!! route('email-drivers') !!}">
                                                <span class="pcoded-micon"><i class="ti-settings"></i><b>ES</b></span>
                                                <span class="pcoded-mtext">Email Drivers</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/email-template') ? 'active' : '' }}">
                                            <a href="{!! route('email-template') !!}">
                                                <span class="pcoded-micon"><i class="ti-email"></i><b>ET</b></span>
                                                <span class="pcoded-mtext">Email Template</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="pcoded-hasmenu @if (Request::is('admin/sms-gateway') or Request::is('admin/sms-template')) active pcoded-trigger @endif" dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="ti-settings"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">SMS Setting</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/sms-gateway') ? 'active' : '' }}">
                                            <a href="{!! route('sms-gateway') !!}">
                                                <span class="pcoded-micon"><i class="ti-settings"></i><b>SA</b></span>
                                                <span class="pcoded-mtext">SMS Gateway</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/sms-template') ? 'active' : '' }}">
                                            <a href="{!! route('sms-template') !!}">
                                                <span class="pcoded-micon"><i class="ti-loop"></i><b>PS</b></span>
                                                <span class="pcoded-mtext">SMS Template</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="pcoded-hasmenu @if (Request::is('admin/whatsapp-drivers')) active pcoded-trigger @endif" dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="ti-settings"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">Whatsapp Setting</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/whatsapp-drivers') ? 'active' : '' }}">
                                            <a href="{!! route('whatsapp-drivers') !!}">
                                                <span class="pcoded-micon"><i class="ti-settings"></i><b>SA</b></span>
                                                <span class="pcoded-mtext">Whatsapp Drivers</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="{{ Request::is('admin/telegram-config') ? 'active' : '' }}">
                                    <a href="{!! route('telegram-config') !!}">
                                        <span class="pcoded-micon"><i class="fa fa-send-o"></i><b>TB</b></span>
                                        <span class="pcoded-mtext">Telegram Bot</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('admin/google-analytic') ? 'active' : '' }}">
                                    <a href="{!! route('google-analytic') !!}">
                                        <span class="pcoded-micon"><i class="fa fa-google"></i><b>GA</b></span>
                                        <span class="pcoded-mtext">Google Analytic</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('admin/live-chat') ? 'active' : '' }}">
                                    <a href="{!! route('live-chat') !!}">
                                        <span class="pcoded-micon"><i class="fa fa-comments-o"></i><b>LC</b></span>
                                        <span class="pcoded-mtext">Live Chat</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('admin/cron-job') ? 'active' : '' }}">
                                    <a href="{!! route('cron-job') !!}">
                                        <span class="pcoded-micon"><i class="ti-link"></i><b>CB</b></span>
                                        <span class="pcoded-mtext">Cron Config</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                {{--  <li class="{{ Request::is('admin/database-backup') ? 'active' : '' }}">
                                    <a href="{!! route('database-backup') !!}">
                                        <span class="pcoded-micon"><i class="ti-layout-list-thumb"></i><b>CB</b></span>
                                        <span class="pcoded-mtext">Database Backup</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>  --}}
                            </ul>
                            <div class="pcoded-navigation-label">Web Setting</div>
                            <ul class="pcoded-item pcoded-left-item">
                                <li class="{{ Request::is('admin/manage-logo') ? 'active' : '' }}">
                                    <a href="{!! route('manage-logo') !!}">
                                        <span class="pcoded-micon"><i class="fa fa-picture-o"></i><b>ML</b></span>
                                        <span class="pcoded-mtext">Manage Logo</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>

                                <li class="{{ Request::is('admin/manage-about') ? 'active' : '' }}">
                                    <a href="{!! route('manage-about') !!}">
                                        <span class="pcoded-micon"><i class="fa fa-file-text-o"></i><b>MF</b></span>
                                        <span class="pcoded-mtext">Manage About</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>

                                <li class="{{ Request::is('admin/manage-footer') ? 'active' : '' }}">
                                    <a href="{!! route('manage-footer') !!}">
                                        <span class="pcoded-micon"><i class="fa fa-sitemap"></i><b>MF</b></span>
                                        <span class="pcoded-mtext">Manage Footer</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('admin/manage-social') ? 'active' : '' }}">
                                    <a href="{!! route('manage-social') !!}">
                                        <span class="pcoded-micon"><i class="ti-share"></i><b>MS</b></span>
                                        <span class="pcoded-mtext">Manage Social</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('admin/manage-breadcrumb') ? 'active' : '' }}">
                                    <a href="{!! route('manage-breadcrumb') !!}">
                                        <span class="pcoded-micon"><i class="fa fa-file-image-o"></i><b>MB</b></span>
                                        <span class="pcoded-mtext">Manage Breadcrumb</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('admin/manage-slider') ? 'active' : '' }}">
                                    <a href="{!! route('manage-slider') !!}">
                                        <span class="pcoded-micon"><i class="fa fa-sliders"></i><b>MS</b></span>
                                        <span class="pcoded-mtext">Manage Slider</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('admin/menu-control') ? 'active' : '' }}">
                                    <a href="{!! route('menu-control') !!}">
                                        <span class="pcoded-micon"><i class="fa fa-rss"></i><b>MM</b></span>
                                        <span class="pcoded-mtext">Manage Menu</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>

                                <li class="pcoded-hasmenu @if (Request::is('admin/member-create') or Request::is('admin/member-all')) active pcoded-trigger @endif " dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="fa fa-users"></i><b>IM</b></span>
                                        <span class="pcoded-mtext">Team Member</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/member-create') ? 'active' : '' }}">
                                            <a href="{{ route('member-create') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">New Member</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/member-all') ? 'active' : '' }}">
                                            <a href="{{ route('member-all') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">All Member</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="pcoded-hasmenu @if (Request::is('admin/testimonial-create') or Request::is('admin/testimonial-all')) active pcoded-trigger @endif " dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="fa fa-desktop"></i><b>I</b></span>
                                        <span class="pcoded-mtext">Manage Testimonial</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        <li class="{{ Request::is('admin/testimonial-create') ? 'active' : '' }}">
                                            <a href="{{ route('testimonial-create') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">New Testimonial</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/testimonial-all') ? 'active' : '' }}">
                                            <a href="{{ route('testimonial-all') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">All Testimonial</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="{{ Request::is('admin/manage-terms') ? 'active' : '' }}">
                                    <a href="{!! route('manage-terms') !!}">
                                        <span class="pcoded-micon"><i class="fa fa-quote-left"></i><b>TC</b></span>
                                        <span class="pcoded-mtext">Term & Condition</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('admin/manage-privacy') ? 'active' : '' }}">
                                    <a href="{!! route('manage-privacy') !!}">
                                        <span class="pcoded-micon"><i class="fa fa-cogs"></i><b>PP</b></span>
                                        <span class="pcoded-mtext">Privacy & Policy</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            </ul>
                            <div class="pcoded-navigation-label">Manage Section</div>
                            <ul class="pcoded-item pcoded-left-item">
                                <li class="pcoded-hasmenu @if (Request::is('admin/speciality-section') or Request::is('admin/subscriber-section') or Request::is('admin/provider-section') or Request::is('admin/trading-section') or Request::is('admin/currency-section') or Request::is('admin/team-section') or Request::is('admin/blog-section') or Request::is('admin/testimonial-section') or Request::is('admin/plan-section') or Request::is('admin/counter-section') or Request::is('admin/about-section') or Request::is('admin/plan-section')) active pcoded-trigger @endif" dropdown-icon="style3" subitem-icon="style7">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="ti-desktop"></i><b>I</b></span>
                                        <span class="pcoded-mtext">Manage Section</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                    <ul class="pcoded-submenu">

                                        <li class="{{ Request::is('admin/speciality-section') ? 'active' : '' }}">
                                            <a href="{{ route('speciality-section') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Speciality Section</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/currency-section') ? 'active' : '' }}">
                                            <a href="{{ route('currency-section') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Signal Section</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/trading-section') ? 'active' : '' }}">
                                            <a href="{{ route('trading-section') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Live Trading Section</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/plan-section') ? 'active' : '' }}">
                                            <a href="{{ route('plan-section') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Plan Section</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/about-section') ? 'active' : '' }}">
                                            <a href="{{ route('about-section') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">About Section</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/advertise-section') ? 'active' : '' }}">
                                            <a href="{{ route('advertise-section') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Advertise Section</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/counter-section') ? 'active' : '' }}">
                                            <a href="{{ route('counter-section') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Counter Section</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/testimonial-section') ? 'active' : '' }}">
                                            <a href="{{ route('testimonial-section') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Testimonial Section</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/subscriber-section') ? 'active' : '' }}">
                                            <a href="{{ route('subscriber-section') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Subscriber Section</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/blog-section') ? 'active' : '' }}">
                                            <a href="{{ route('blog-section') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Blog Section</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('admin/team-section') ? 'active' : '' }}">
                                            <a href="{{ route('team-section') }}">
                                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                <span class="pcoded-mtext">Team Section</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>

                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-header card">
                                        <div class="card-block">
                                            <h5 class="m-b-10">{{ $page_title }}</h5>
                                            <ul class="breadcrumb-title flex justify-content-center">
                                                <li class="breadcrumb-item">
                                                    <a href="{{ route('dashboard') }}"> <i class="fa fa-home"></i> Dashboard </a>
                                                </li>
                                                <li class="breadcrumb-item"><a href="#">{{ $page_title }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-warning icons-alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <i class="icofont icofont-close-line-circled"></i>
                                                </button>
                                                <p>{!! $error !!}</p>
                                            </div>
                                        @endforeach
                                    @endif
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/modernizr.js') }}"></script>
    <script src="{{ asset('assets/admin/js/css-scrollbars.js') }}"></script>
    @yield('import_scripts')
    <script src="{{ asset('assets/admin/js/pcoded.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vertical-layout.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/script.js') }}"></script>
    <script src="{{ asset('assets/admin/js/toastr.js') }}"></script>
    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('type', 'info') }}";
            switch (type) {
                case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;
                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;
                case 'error':
                    toastr.error("{{ Session::get('message') }}");
                    break;
            }
        @endif
    </script>

    @yield('scripts')

</body>

</html>
