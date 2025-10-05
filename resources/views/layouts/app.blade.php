<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}SafePoint Admin</title>

    <!-- Vector Map CSS -->
    <link href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- jQuery -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>

    <!-- Datepicker jquery-ui -->
    <script src="{{ asset('assets/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <link href="{{ asset('assets/libs/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- datepicker css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/leaflet/leaflet.css') }}">
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

    <!-- Date Range Picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Select2 -->
    <link href="{{ asset('assets/libs/select2/select2-min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/libs/select2/select2-min.js') }}"></script>
    <script src="{{ asset('assets/js/html2canvas.min.js') }}"></script>

    <!-- PDF Export libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <!-- alertifyjs Css -->
    <link href="{{ asset('assets/libs/alertifyjs/build/css/alertify.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- alertifyjs default themes  Css -->
    <link href="{{ asset('assets/libs/alertifyjs/build/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Day.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.9/dayjs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.9/locale/id.min.js"></script>

    <!-- Mapbox GL JS -->
    <link href='https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.css' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js'></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Force override any external styles -->
    <style>
        /* Force white/clean backgrounds everywhere */
        html, body, #layout-wrapper, .main-content, .page-content {
            background: #f8f9fa !important;
            background-image: none !important;
            background-color: #f8f9fa !important;
        }
        
        #page-topbar, .navbar-header {
            background: #fff !important;
            background-image: none !important;
            background-color: #fff !important;
        }

        /* Space on the left so fixed toggle doesn't cover topbar content */
        #page-topbar {
            padding-left: 56px;
        }

        @media (max-width: 576px) {
            #page-topbar {
                padding-left: 12px;
            }
            #vertical-menu-btn {
                left: 8px;
                top: 28px;
            }
        }
        
        /* Ensure no external gradients override our styles */
        *, *::before, *::after {
            background-image: none !important;
        }
    </style>

    <style>
        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 36px;
            user-select: none;
            -webkit-user-select: none;
        }

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 34px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            position: absolute;
            top: 1px;
            right: 5px;
            width: 20px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            display: block;
            padding-left: 15px;
            padding-right: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: darkgray;
            opacity: 0.9;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            cursor: pointer;
            float: right;
            font-weight: bold;
            height: 34px;
            margin-right: 26px;
            padding-right: 0px;
        }

        .action-button {
            display: flex;
        }

        .action-button>button {
            margin: 0 2px;
        }

        .text-muted a,
        .text-success a {
            cursor: pointer;
            position: relative;
            display: inline-block;
        }

        .text-muted a:hover,
        text-success a:hover {
            filter: brightness(75%);
        }

        /* Sidebar minimize styles - Minia Template */
        .sidebar-enable {
            transition: all 0.3s ease;
        }

        /* Sidebar styling - White theme */
        .vertical-menu {
            position: fixed;
            width: 250px;
            z-index: 1001;
            height: 100vh;
            background: #fff;
            top: 0;
            left: 0;
            transition: width 0.3s ease !important;
            box-shadow: 0 2px 4px rgba(15, 34, 58, 0.12);
            border-right: 1px solid #f6f6f6;
        }

        /* Force immediate width changes on toggle */
        .vertical-menu.transitioning {
            transition: none !important;
        }

        /* Sidebar header - simplified without toggle button */
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #f6f6f6;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            min-height: 80px;
        }

        .sidebar-logo {
            transition: all 0.3s ease;
            max-height: 40px;
            width: auto;
        }

        .sidebar-title {
            transition: all 0.3s ease;
            white-space: nowrap;
            color: #dc3545 !important;
            font-weight: 600;
        }

        /* Topbar toggle button styling */
        .header-item.sidebar-toggle-btn {
            background: none !important;
            border: none !important;
            color: #74788d;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .header-item.sidebar-toggle-btn:hover {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545;
        }

        .header-item.sidebar-toggle-btn:focus {
            box-shadow: none !important;
            outline: none !important;
        }

        .header-item.sidebar-toggle-btn i {
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn:hover {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545;
        }

        .sidebar-toggle-btn:focus {
            box-shadow: none !important;
            outline: none !important;
        }

        .sidebar-toggle-btn i {
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        /* Force proper layout in collapsed state */
        .vertical-collpsed .sidebar-header {
            display: flex !important;
        }

        .vertical-collpsed .sidebar-header > * {
            flex-shrink: 0;
        }

        /* Override any Bootstrap flex utilities in collapsed state */
        .vertical-collpsed .sidebar-header .d-flex.align-items-center {
            align-items: center !important;
            flex-direction: column !important;
        }

        /* Collapsed sidebar - show only logo centered */
        .vertical-collpsed .sidebar-header {
            padding: 20px 10px !important;
            justify-content: center !important;
            min-height: 80px !important;
        }

        .vertical-collpsed .sidebar-header .d-flex {
            flex-direction: column !important;
            align-items: center !important;
            gap: 0 !important;
        }

        .vertical-collpsed .sidebar-header .sidebar-logo {
            height: 32px !important;
            width: auto !important;
            margin: 0 !important;
        }

        .vertical-collpsed .sidebar-header .sidebar-title {
            display: none !important;
        }

        .main-content {
            margin-left: 250px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        #page-topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 1000;
            background: #fff;
            box-shadow: 0 1px 2px rgba(56, 65, 74, 0.15);
            transition: all 0.3s ease;
            height: 70px;
        }

        /* Topbar without logo - adjust layout */
        .navbar-header {
            padding: 0 20px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .navbar-header .d-flex {
            margin-left: auto;
        }

        /* Page content styling */
        .page-content {
            padding-top: 90px;
            padding-left: 20px;
            padding-right: 20px;
            background: #f8f9fa !important;
            min-height: calc(100vh - 70px);
        }

        /* Collapsed sidebar */
        .vertical-collpsed .vertical-menu {
            width: 70px;
        }

        .vertical-collpsed .main-content {
            margin-left: 70px;
        }

        .vertical-collpsed #page-topbar {
            left: 70px;
        }

        /* Sidebar menu styling - White theme */
        .vertical-menu .metismenu {
            margin: 0;
            padding: 0;
        }

        .vertical-menu .metismenu .menu-title {
            padding: 12px 20px;
            color: #6c757d;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .vertical-menu .metismenu li {
            display: block;
            width: 100%;
        }

        .vertical-menu .metismenu li a {
            display: flex;
            align-items: center;
            color: #495057;
            padding: 12px 20px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            background: none;
            border-radius: 0 25px 25px 0;
            margin-right: 15px;
        }

        .vertical-menu .metismenu li a:hover {
            color: #556ee6;
            background-color: rgba(85, 110, 230, 0.1);
        }

        .vertical-menu .metismenu li a i {
            margin-right: 10px;
            font-size: 16px;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
            color: #74788d;
        }

        /* Active menu styling - White theme */
        .vertical-menu .metismenu .mm-active > a {
            color: #556ee6 !important;
            background-color: rgba(85, 110, 230, 0.1) !important;
        }

        .vertical-menu .metismenu .mm-active > a i {
            color: #556ee6 !important;
        }

        /* Sub menu styling - White theme */
        .vertical-menu .metismenu .sub-menu {
            background-color: transparent;
            padding: 0;
        }

        .vertical-menu .metismenu .sub-menu li a {
            padding: 10px 20px 10px 50px;
            font-size: 12px;
            color: #6c757d;
            margin-right: 0;
            border-radius: 0;
        }

        .vertical-menu .metismenu .sub-menu li a:hover {
            color: #556ee6;
            background-color: rgba(85, 110, 230, 0.05);
        }

        .vertical-menu .metismenu .sub-menu .mm-active > a {
            color: #556ee6 !important;
            background-color: rgba(85, 110, 230, 0.05) !important;
        }

        /* Collapsed sidebar styling */
        .vertical-collpsed .vertical-menu .metismenu .menu-title {
            display: none;
        }

        .vertical-collpsed .vertical-menu .metismenu li a {
            padding: 15px 20px;
            justify-content: center;
            margin-right: 0;
            border-radius: 0;
        }

        .vertical-collpsed .vertical-menu .metismenu li a i {
            margin: 0;
            font-size: 18px;
        }

        .vertical-collpsed .vertical-menu .metismenu li a span {
            display: none;
        }

        .vertical-collpsed .vertical-menu .metismenu .has-arrow:after {
            display: none;
        }

        .vertical-collpsed .vertical-menu .metismenu .sub-menu {
            display: none !important;
        }

        /* Remove hover effect for collapsed sidebar - we want clean toggle behavior */
        .vertical-collpsed .vertical-menu {
            width: 70px !important;
        }

        .vertical-collpsed .vertical-menu .metismenu .menu-title {
            display: none !important;
        }

        .vertical-collpsed .vertical-menu .metismenu li a span {
            display: none !important;
        }

        .vertical-collpsed .vertical-menu .metismenu .has-arrow:after {
            display: none !important;
        }

        /* MetisMenu arrow styling */
        .metismenu .has-arrow:after {
            content: "\f107";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            float: right;
            color: #74788d;
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .metismenu .mm-show > .has-arrow:after {
            transform: rotate(180deg);
        }

        /* Navbar brand box */
        .navbar-brand-box {
            background: #556ee6;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 70px;
            width: 100%;
        }

        /* Topbar header items */
        .header-item {
            color: #74788d;
            padding: 8px 12px;
            border: none;
            background: none;
            font-size: 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .header-item:hover {
            color: #495057;
            background-color: rgba(85, 110, 230, 0.1);
        }

        /* Toggle button styling */
        #vertical-menu-btn {
            margin-right: 10px;
        }

        #vertical-menu-btn i {
            font-size: 18px;
        }

        /* Icon styles */
        .icon-sm {
            width: 14px;
            height: 14px;
        }

        .icon-dual {
            width: 16px;
            height: 16px;
        }

        /* Primary color - changing to red */
        .btn-primary {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-primary:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .text-primary {
            color: #dc3545 !important;
        }

        .bg-primary {
            background-color: #dc3545 !important;
        }

        .border-primary {
            border-color: #dc3545 !important;
        }

        .badge-primary {
            background-color: #dc3545;
        }

        /* Update theme color throughout */
        .sidebar-toggle-btn:hover {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545;
        }

        .vertical-menu .metismenu li a:hover {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
        }

        .vertical-menu .metismenu .mm-active > a {
            color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .vertical-menu .metismenu .mm-active > a i {
            color: #dc3545 !important;
        }

        .vertical-menu .metismenu .sub-menu li a:hover {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.05);
        }

        .vertical-menu .metismenu .sub-menu .mm-active > a {
            color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.05) !important;
        }

        /* Alert styling */
        .alert-primary {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        /* Update search and form focus colors */
        .app-search .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        /* Header item styling */
        .header-item:hover {
            color: #dc3545;
        }

        /* Header item styling */
        .header-item {
            color: #74788d;
            padding: 0 10px;
            border: none;
            background: none;
            font-size: 16px;
        }

        .header-item:hover {
            color: #495057;
        }

        /* Page title */
        .page-title {
            color: #495057;
            font-weight: 600;
        }

        /* App search styling */
        .app-search {
            position: relative;
        }

        .app-search .form-control {
            border: none;
            background-color: #f8f9fa;
            padding-left: 40px;
            height: 38px;
            border-radius: 30px;
        }

        .app-search .bx-search-alt {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #74788d;
        }

        /* Layout adjustments */
        .page-content {
            padding: 20px;
        }

        .container-fluid {
            padding-left: 20px;
            padding-right: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .vertical-menu {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            #page-topbar {
                left: 0;
            }
            
            .sidebar-enable .vertical-menu {
                transform: translateX(0);
            }
            
            .vertical-collpsed .vertical-menu {
                transform: translateX(-100%);
            }
            
            .vertical-collpsed.sidebar-enable .vertical-menu {
                transform: translateX(0);
            }

            /* Mobile sidebar header */
            .sidebar-header {
                    padding: 20px;
                    justify-content: flex-start;
                min-height: 80px;
            }

            .sidebar-header .d-flex {
                display: flex !important;
            }

            .sidebar-header .sidebar-logo {
                max-height: 40px;
            }

        /* Pin the sidebar toggle button to the very left of the topbar */
        #page-topbar .navbar-header {
            position: relative; /* create a containing block for absolute-positioned toggle */
        }

        /* Pin the sidebar toggle visually at the very left of the viewport */
        #vertical-menu-btn {
            position: fixed !important;
            left: 12px !important;
            top: 35px !important; /* vertically center inside 70px topbar */
            margin: 0 !important;
            padding: 8px 10px !important;
            z-index: 2000 !important;
        }

            /* Collapsed state - logo centered */
            .vertical-collpsed .sidebar-header {
                padding: 20px 10px !important;
                justify-content: center !important;
                min-height: 80px !important;
            }

            .vertical-collpsed .sidebar-header .d-flex {
                flex-direction: column !important;
                align-items: center !important;
                gap: 0 !important;
            }

            .vertical-collpsed .sidebar-header .sidebar-logo {
                height: 32px !important;
                width: auto !important;
                margin: 0 !important;
            }

            .vertical-collpsed .sidebar-header .sidebar-title {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .app-search {
                display: none !important;
            }
            
            .page-content {
                padding: 20px 10px;
                padding-top: 90px;
            }
            
            .navbar-header {
                padding: 0 15px;
            }

            .sidebar-header {
                padding: 12px 15px;
            }

            .sidebar-toggle-btn {
                padding: 6px 8px;
            }

            /* Clean container styling */
            .container-fluid {
                padding: 0 15px;
            }
        }        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            display: none;
        }

        /* Right sidebar toggle */
        .right-bar-toggle {
            color: #74788d;
        }

        /* Flag dropdown */
        .dropdown-menu .notify-item img {
            margin-right: 8px;
        }

        /* Mode toggle button */
        #mode-setting-btn {
            background: none;
            border: none;
            color: #74788d;
        }

        #mode-setting-btn:hover {
            color: #495057;
        }

        /* Container and layout improvements */
        .container-fluid {
            padding: 0 20px;
            max-width: 100%;
        }

        /* Clean card styling */
        .card {
            border: 1px solid #e9ecef;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
        }

        .card-body {
            padding: 20px;
        }

        /* Layout wrapper fixes */
        #layout-wrapper {
            min-height: 100vh;
            background: #f8f9fa !important;
        }

        /* Remove any purple/gradient backgrounds */
        body {
            background: #f8f9fa !important;
        }

        /* Override any purple/blue backgrounds from external CSS */
        * {
            background-image: none !important;
        }

        .main-content, .page-content, .container-fluid {
            background: #f8f9fa !important;
            background-image: none !important;
            background-color: #f8f9fa !important;
        }

        /* Topbar styling - clean white design */
        #page-topbar {
            background: #fff !important;
            border-bottom: 1px solid #e9ecef;
            background-image: none !important;
            background-color: #fff !important;
        }

        .navbar-header {
            background: #fff !important;
            background-image: none !important;
            background-color: #fff !important;
        }

        /* Footer positioning — fixed and aligned to sidebar */
        .footer {
            position: flex;
            bottom: 0;
            left: 250px;
            right: 0;
            height: 60px;
            padding: 12px 20px;
            transition: left 0.3s ease;
            background: #fff;
            border-top: 1px solid #f6f6f6;
            z-index: 999;
        }

        /* When sidebar is collapsed */
        body.vertical-collpsed .footer {
            left: 70px;
        }

        /* Prevent page content from being hidden behind footer */
        .page-content {
            padding-bottom: 80px;
        }

        /* Mobile: footer spans full width below content and is not fixed */
        @media (max-width: 992px) {
            .footer {
                position: relative;
                left: 0;
                right: 0;
                height: auto;
                padding: 12px 0;
            }
            .page-content {
                padding-bottom: 20px;
            }
        }

        /* Notification dropdown */
        .notification-item {
            padding: 12px 20px;
            border-bottom: 1px solid #f6f6f6;
            transition: background-color 0.3s ease;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        /* User dropdown - improved alignment */
        .header-profile-user {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Profile button alignment */
        #page-header-user-dropdown {
            display: flex !important;
            align-items: center !important;
            padding: 8px 12px !important;
        }

        #page-header-user-dropdown img {
            flex-shrink: 0;
        }

        #page-header-user-dropdown span {
            line-height: 1.2;
        }

        /* Search bar improvements */
        .app-search .form-control:focus {
            border-color: #556ee6;
            box-shadow: 0 0 0 0.2rem rgba(85, 110, 230, 0.25);
        }

        /* Dashboard specific styles */
        .mini-stats-wid {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .mini-stats-wid:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .mini-stat-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-soft-primary {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
        }

        .badge-soft-danger {
            color: #f46a6a;
            background-color: rgba(244, 106, 106, 0.1);
        }

        .badge-soft-warning {
            color: #f1b44c;
            background-color: rgba(241, 180, 76, 0.1);
        }

        .badge-soft-info {
            color: #50a5f1;
            background-color: rgba(80, 165, 241, 0.1);
        }

        .badge-soft-success {
            color: #34c38f;
            background-color: rgba(52, 195, 143, 0.1);
        }

        .badge-soft-secondary {
            color: #74788d;
            background-color: rgba(116, 120, 141, 0.1);
        }

        .badge-soft-dark {
            color: #495057;
            background-color: rgba(73, 80, 87, 0.1);
        }

        /* Table improvements */
        .table-nowrap th,
        .table-nowrap td {
            white-space: nowrap;
        }

        .table-responsive {
            border-radius: 6px;
        }

        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
        }
    </style>

    @stack('styles')
</head>

<body class="easyPrint">
    <div id="layout-wrapper">
        @include('main.partials.menu')
        
        <!-- Sidebar overlay for mobile -->
        <div class="sidebar-overlay"></div>
        
        <div class="main-content">
            <div class="page-content" style="background-color: #F9FAFB;">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
            @include('main.partials.footer')
        </div>
    </div>

    @include('main.partials.right-sidebar')

    <!-- Vendor Scripts -->
    @include('main.partials.vendor-scripts')

    <!-- Chart libraries -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}" defer="defer"></script>

    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}" defer="defer"></script>
    <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}" defer="defer"></script>

    <!-- dashboard init -->
    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}" defer="defer"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}" defer="defer"></script>

    <!-- Custom Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing sidebar toggle...');
            
            // Sidebar toggle functionality
            const toggleBtn = document.getElementById('vertical-menu-btn');
            const body = document.body;
            const sidebar = document.querySelector('.vertical-menu');
            
            console.log('Toggle button found:', toggleBtn);
            console.log('Sidebar found:', sidebar);
            
            if (toggleBtn) {
                // Remove any existing event listeners
                toggleBtn.replaceWith(toggleBtn.cloneNode(true));
                const newToggleBtn = document.getElementById('vertical-menu-btn');
                
                newToggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Toggle button clicked');
                    
                    // Force immediate toggle - no delays or hover effects
                    body.classList.toggle('vertical-collpsed');
                    
                    // Force sidebar width change immediately
                    if (body.classList.contains('vertical-collpsed')) {
                        sidebar.style.width = '70px';
                        console.log('Sidebar minimized immediately');
                    } else {
                        sidebar.style.width = '250px';
                        console.log('Sidebar expanded immediately');
                    }
                    
                    // Save state to localStorage
                    const isCollapsed = body.classList.contains('vertical-collpsed');
                    localStorage.setItem('sidebar-collapsed', isCollapsed ? 'true' : 'false');
                    
                    console.log('Sidebar collapsed:', isCollapsed);
                });
                
                console.log('Event listener attached successfully');
            } else {
                console.error('Toggle button not found!');
            }

            // Restore sidebar state from localStorage
            const sidebarCollapsed = localStorage.getItem('sidebar-collapsed');
            if (sidebarCollapsed === 'true') {
                body.classList.add('vertical-collpsed');
                if (sidebar) {
                    sidebar.style.width = '70px';
                }
                console.log('Restored collapsed state from localStorage');
            } else {
                if (sidebar) {
                    sidebar.style.width = '250px';
                }
            }

            // Initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Initialize MetisMenu for dropdown functionality
            if (typeof $ !== 'undefined' && $.fn.metisMenu) {
                $('#side-menu').metisMenu();
            }

            // Mobile sidebar overlay functionality
            const overlay = document.querySelector('.sidebar-overlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    body.classList.remove('sidebar-enable');
                });
            }

            // Handle window resize for mobile
            function handleResize() {
                if (window.innerWidth <= 992) {
                    // Mobile view - reset sidebar width and use sidebar-enable class
                    if (sidebar) {
                        sidebar.style.width = '';
                    }
                    body.classList.remove('vertical-collpsed');
                } else {
                    // Desktop view - ensure proper width based on collapsed state
                    if (body.classList.contains('vertical-collpsed')) {
                        if (sidebar) {
                            sidebar.style.width = '70px';
                        }
                    } else {
                        if (sidebar) {
                            sidebar.style.width = '250px';
                        }
                    }
                }
            }
            
            window.addEventListener('resize', handleResize);
            handleResize(); // Call on load
        });
    </script>

    <!-- Notification System JavaScript -->
    <script>
        class NotificationSystem {
            constructor() {
                this.lastCaseCount = 0;
                this.pollingInterval = 30000; // 30 seconds
                this.countElement = document.getElementById('notification-count');
                this.containerElement = document.getElementById('notifications-container');
                this.listElement = document.getElementById('notifications-list');
                this.loadingElement = document.getElementById('notifications-loading');
                this.emptyElement = document.getElementById('notifications-empty');
                this.markAllReadBtn = document.getElementById('mark-all-read-btn');
                this.refreshBtn = document.getElementById('refresh-notifications-btn');
                
                this.init();
            }

            init() {
                // Initial load
                this.loadNotifications();
                
                // Set up polling
                setInterval(() => {
                    this.loadNotifications();
                    this.checkForNewCases();
                }, this.pollingInterval);
                
                // Event listeners
                if (this.markAllReadBtn) {
                    this.markAllReadBtn.addEventListener('click', () => this.markAllAsRead());
                }
                
                if (this.refreshBtn) {
                    this.refreshBtn.addEventListener('click', () => this.loadNotifications());
                }
            }

            async loadNotifications() {
                try {
                    const response = await fetch('/api/notifications', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Failed to fetch notifications');
                    }

                    const data = await response.json();
                    this.updateNotificationUI(data);
                } catch (error) {
                    console.error('Error loading notifications:', error);
                    this.showErrorState();
                }
            }

            updateNotificationUI(data) {
                // Update count
                if (this.countElement) {
                    this.countElement.textContent = data.unread_count;
                    this.countElement.style.display = data.unread_count > 0 ? 'inline' : 'none';
                }

                // Update notifications list
                if (data.notifications && data.notifications.length > 0) {
                    this.renderNotifications(data.notifications);
                    this.showElement(this.listElement);
                    this.hideElement(this.emptyElement);
                } else {
                    this.hideElement(this.listElement);
                    this.showElement(this.emptyElement);
                }

                this.hideElement(this.loadingElement);
            }

            renderNotifications(notifications) {
                if (!this.listElement) return;

                this.listElement.innerHTML = notifications.map(notification => {
                    const readClass = notification.is_read ? 'notification-read' : 'notification-unread';
                    const iconClass = this.getNotificationIcon(notification.type);
                    const bgClass = this.getNotificationBg(notification.type);
                    
                    return `
                        <div class="notification-item ${readClass}" data-id="${notification.id}">
                            <div class="d-flex p-3 border-bottom">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title ${bgClass} rounded-circle font-size-16">
                                        <i class="${iconClass}"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 font-size-14">${notification.title}</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">${notification.message}</p>
                                        <p class="mb-0">
                                            <i class="mdi mdi-clock-outline"></i> 
                                            <span>${notification.created_at_human}</span>
                                        </p>
                                    </div>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="showCaseDetail('${notification.data.case_id}')">
                                            Lihat Detail
                                        </button>
                                    </div>
                                </div>
                                ${!notification.is_read ? `
                                    <div class="ms-2">
                                        <button class="btn btn-sm btn-link text-primary" onclick="notificationSystem.markAsRead(${notification.id})">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                }).join('');
            }

            getNotificationIcon(type) {
                switch (type) {
                    case 'new_case': return 'bx bx-plus-circle';
                    case 'case_updated': return 'bx bx-edit';
                    case 'case_assigned': return 'bx bx-user-check';
                    default: return 'bx bx-bell';
                }
            }

            getNotificationBg(type) {
                switch (type) {
                    case 'new_case': return 'bg-danger';
                    case 'case_updated': return 'bg-warning';
                    case 'case_assigned': return 'bg-success';
                    default: return 'bg-primary';
                }
            }

            async markAsRead(notificationId) {
                try {
                    const response = await fetch(`/api/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.ok) {
                        this.loadNotifications();
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            }

            async markAllAsRead() {
                try {
                    const response = await fetch('/api/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.ok) {
                        this.loadNotifications();
                    }
                } catch (error) {
                    console.error('Error marking all notifications as read:', error);
                }
            }

            async checkForNewCases() {
                try {
                    // Check if we're on dashboard page
                    if (window.location.pathname === '/dashboard' || window.location.pathname === '/') {
                        const response = await fetch('/api/notifications/unread-count', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            
                            // If unread count increased significantly, refresh the page
                            if (data.unread_count > this.lastCaseCount && this.lastCaseCount > 0) {
                                // Show notification about new cases
                                this.showNewCaseNotification();
                                
                                // Refresh page after a short delay
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            }
                            
                            this.lastCaseCount = data.unread_count;
                        }
                    }
                } catch (error) {
                    console.error('Error checking for new cases:', error);
                }
            }

            showNewCaseNotification() {
                // Show toast notification or alert for new cases
                if (typeof alertify !== 'undefined') {
                    alertify.success('Kasus baru masuk! Halaman akan dimuat ulang...');
                } else {
                    // Fallback to simple alert
                    const notification = document.createElement('div');
                    notification.className = 'alert alert-success position-fixed';
                    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
                    notification.innerHTML = 'Kasus baru masuk! Halaman akan dimuat ulang...';
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 3000);
                }
            }

            getAuthToken() {
                // For web routes, we use session authentication with CSRF tokens
                return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            }

            showElement(element) {
                if (element) {
                    element.classList.remove('d-none');
                }
            }

            hideElement(element) {
                if (element) {
                    element.classList.add('d-none');
                }
            }

            showErrorState() {
                if (this.loadingElement) {
                    this.loadingElement.innerHTML = `
                        <div class="text-center py-4">
                            <i class="bx bx-error-circle font-size-24 text-danger"></i>
                            <p class="small text-muted mt-2">Gagal memuat notifikasi</p>
                        </div>
                    `;
                }
            }
        }

        // Initialize notification system when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            window.notificationSystem = new NotificationSystem();
        });
    </script>

    <style>
        .notification-item {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-unread {
            background-color: #fff8f0;
            border-left: 3px solid #dc3545;
        }

        .notification-read {
            opacity: 0.8;
        }

        #notification-count {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>

    @stack('scripts')
</body>

</html>
