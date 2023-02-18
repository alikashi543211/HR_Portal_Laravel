<meta charset="utf-8" />
<link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
<link rel="icon" type="image/png" href="../assets/img/favicon.png">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>
    Paper Dashboard 2 by Creative Tim
</title>
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
<!--     Fonts and icons     -->
<!-- CSS Files -->

<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/paper-dashboard.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" />

<link rel="stylesheet" href="{{ asset('assets/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-year-calendar.min.css') }}">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css">


<style>
    .table-responsive {
        overflow: auto !important;
    }

    .main-panel>.content {
        {{ Request::segment(3) == 'notifications' || Request::segment(2) == 'dashboard' ? 'height: auto !important;' : 'height: calc(100vh - 163px) !important;' }}
        /* height: calc(100vh - 163px) !important; */
    }

    .sidebar .sidebar-wrapper,
    .off-canvas-sidebar .sidebar-wrapper {
        overflow-x: hidden;
    }

    .select2-container--default .select2-selection--single {
        border-radius: 3px;
        /* padding: 20px 0px; */
    }

    .select2-container--default .select2-selection--single {
        height: 40px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 7px;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #dedede;
    }

    .checkInOut {
        padding: 15px;
        background-color: #e8e6e6;
        text-align: center;
        border: 1px solid #cfcfcf;
    }

    .checkInOut>p {
        margin-bottom: 0px;
    }

    .timeCircle>p {
        margin-bottom: 0px;
    }

    .timeCircle {
        border: 4px solid #a09f9f;
        border-radius: 50%;
        background-color: #e8e6e6;
        height: 100px;
        width: 100px;
        margin: 15px 0 15px 0;
    }

    .view-attendance-popup {
        cursor: pointer;
    }

    .role-header {
        padding: 20px !important;
        background-color: #51bcda !important;
        color: white !important;
        font-weight: bold !important;
    }

    .card_shadow {
        box-shadow: 0 1.5rem 4rem rgba(0, 0, 0, .15) !important;
    }

    .card-category {
        color: black !important;
    }

    .navbar-brand {
        color: black !important;
        font-weight: bold;
    }

    .card label {
        color: black;
        font-size: 15px;
    }

    .navbar.navbar-transparent .nav-item .nav-link:not(.btn) {
        color: black;
        font-weight: bold;
    }

    .sidebar[data-color="white"] .logo .simple-text,
    .off-canvas-sidebar[data-color="white"] .logo .simple-text {
        color: black;
    }

    .custom_text_primary {
        color: blue;
    }

    @media(max-width: 991.98px) {

        .sidebar.active {
            left: 260px;
        }

    }

    .sidebar-wrapper .nav a {
        color: black !important;
    }

    .sidebar-wrapper .nav a i {
        color: black !important;
    }

    .text-primary,
    a,
    a.text-primary,
    a.text-primary:focus,
    a.text-primary:hover {
        color: black;
    }
</style>
