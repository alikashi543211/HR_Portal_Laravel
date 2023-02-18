<!DOCTYPE html>
<html lang="en">
{{-- <meta http-equiv="content-type" content="text/html;charset=UTF-8" /> --}}

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Devstudio Employee Portal</title>

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="public/images/favicon.png">

    <link href="{{ asset('employeesAsset/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('employeesAsset/vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('employeesAsset/vendor/chartist/css/chartist.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('employeesAsset/css/LineIcons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('employeesAsset/vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('employeesAsset/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('employeesAsset/vendor/toastr/css/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('employeesAsset/custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('employeesAsset/custom-media.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/brands.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/fontawesome.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/regular.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/regular.min.css" crossorigin="anonymous" />
    <style>
        .sk-three-bounce .sk-child {
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: #F47429;
            border-radius: 100%;
            display: inline-block;
            -webkit-animation: sk-three-bounce 1.9s ease-in-out 0s infinite both;
            animation: sk-three-bounce 1.4s ease-in-out 0s infinite both;
        }
    </style>
    @yield('css')

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        @include('employees.layouts.header')

        <!--**********************************
            Nav header end
        ***********************************-->




        <!--**********************************
            Sidebar start
        ***********************************-->
        <!--**********************************
            Sidebar start
        ***********************************-->

        @include('employees.layouts.sidebar')

        <!--**********************************
            Sidebar end
        ***********************************-->



        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            @yield('content')
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
            ***********************************-->
        @include('employees.layouts.footer')

        <!--**********************************
            Footer end
        ***********************************-->

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="{{ asset('employeesAsset/vendor/global/global.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/vendor/bootstrap-datetimepicker/js/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/vendor/chart.js/Chart.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/vendor/owl-carousel/owl.carousel.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/vendor/peity/jquery.peity.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/js/dashboard/dashboard-1.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/js/custom.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/js/deznav-init.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/vendor/toastr/js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('employeesAsset/js/plugins-init/toastr-init.js') }}" type="text/javascript"></script>

    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyBI_-2VliQ7dzqLRwsSI4KUZy27s4Yeqec",
            authDomain: "push-notification-6c9bd.firebaseapp.com",
            projectId: "push-notification-6c9bd",
            storageBucket: "push-notification-6c9bd.appspot.com",
            messagingSenderId: "345671504039",
            appId: "1:345671504039:web:dbd6f116528e6ad3738b82",
            measurementId: "G-W429M9388V"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        initFirebaseMessagingRegistration();

        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken()
                })
                .then(function(token) {
                    console.log(token);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    $.ajax({
                        url: '{{ route('employee.save-token') }}',
                        type: 'POST',
                        data: {
                            token: token
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            console.log('Token saved successfully.');
                        },
                        error: function(err) {
                            console.log('User Chat Token Error' + err);
                        },
                    });

                }).catch(function(err) {
                    console.log('User Chat Token Error' + err);
                });
        }

        messaging.onMessage(function(payload) {
            const noteTitle = payload.data.title;
            const noteOptions = {
                body: payload.data.body,
                icon: payload.data.icon,
            };
            var number = parseInt($('#notificationNumber').html());
            number = number + 1;
            $('#notificationNumber').html(number);
            getNotificationAjax();
            new Notification(noteTitle, noteOptions);
        });
    </script>
    @yield('scripts')
    <script>
        $(document).on('click', '.notification-click', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            var id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: "{{ route('employee.NotificationStatus') }}",
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                }
            });
        })
        getNotificationAjax();

        $('#notificationAjax').on('click', function() {
            getNotificationAjax();

        })

        var listener = new BroadcastChannel('listener');
        listener.onmessage = function(e) {
            var number = parseInt($('#notificationNumber').html());
            number = number + 1;
            $('#notificationNumber').html(number);
            getNotificationAjax();
        };

        function getNotificationAjax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            var id = $('#notificationAjax').attr('data-id');

            $.ajax({
                type: 'POST',
                url: "{{ route('employee.getNotifications') }}",
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    $('#displayNotifications').html(data.html);
                }
            });

        }
    </script>
    <script>
        @if (Session::has('success'))
            toastr.success("{{ session('success') }}", "Success", {
                positionClass: "toast-bottom-right",
                timeOut: 5e3,
                closeButton: !0,
                debug: !1,
                newestOnTop: !0,
                progressBar: !0,
                preventDuplicates: !0,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                tapToDismiss: !1
            });
        @endif

        @if (Session::has('error'))
            toastr.error("{{ session('error') }}", "Error", {
                positionClass: "toast-bottom-right",
                timeOut: 5e3,
                closeButton: !0,
                debug: !1,
                newestOnTop: !0,
                progressBar: !0,
                preventDuplicates: !0,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                tapToDismiss: !1
            })
        @endif

        @if (Session::has('info'))
        @endif

        @if (Session::has('warning'))
        @endif
    </script>
</body>

</html>
