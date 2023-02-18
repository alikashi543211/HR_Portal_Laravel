<!DOCTYPE html>
<html lang="en">

<head>

    <title>Admin - @yield('title')</title>
    @section('header')
        @include('includes.head')
    @show


</head>

<body class="">
    <div class="wrapper ">
        @include('includes.side-menu')
        <div class="main-panel">
            <!-- Navbar -->
            @include('includes.navbar')

            <!-- End Navbar -->
            <div class="content custom-notification">
                @include('alert')
                @yield('page_content')
            </div>
            @include('includes.footer')
        </div>
    </div>
    <!--   Core JS Files   -->

    <!--   Custom JS Files   -->
    @section('footer_js')
        @include('includes.js-files')
    @show


</body>

</html>
