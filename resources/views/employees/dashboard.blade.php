@extends('employees.layouts.app')
@section('title')
    Dashboard
@endsection
@section('css')
    <style>
        .card.bg-checkin {
            background-color: #484A9F;
        }

        .card.bg-checkout {
            background-color: #484A9F;
        }

        .bg-used-sick {
            background-color: #4BBE9A;
        }

        .bg-left-sick {
            background-color: #059669;
        }

        .bg-used-casual {
            background-color: #FC7477;
        }

        .bg-left-casual {
            background-color: #ED4548;
        }

        .card-body.dashboard {
            padding: 1.2rem;
        }


        .media.align-items-center {
            position: relative;
            align-items: flex-start !important;
        }

        .dashboard-icon {
            width: 4rem;
        }

        .card.bg-checkin,
        .card.bg-checkout,
        .card.bg-used-sick,
        .card.bg-left-sick,
        .card.bg-used-casual,
        .card.bg-left-casual {
            margin-left: 1.2rem;
            width: 270px;
            padding: 0.2rem 1rem;
        }

        .image-icon.p-3.mr-3 {}

        .image-icon.p-3.mr-3 img {
            width: 40px;
            height: 40px;
        }

        .fs-48.text-white.font-w600.numbers {
            font-size: 2rem !important;
        }

        .fs-18.text-white.mb-2 {
            font-size: 15px !important;
        }

        .media-body {
            text-align: center !important
        }

        .fa-calendar-alt:before,
        .fa-calendar-days:before {
            content: "\f073";
            font-family: 'Font Awesome 5 Free';
            font-style: normal;
            margin-right: 0.3rem;
            margin-left: 1rem;
        }

        .fa-clock-four:before,
        .fa-clock:before {
            margin-right: 0.3rem;
        }

        .fa-user:before {
            content: "\f007";
            font-size: 15px;
            margin-right: 0.3rem;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="card bg-checkin">
                <div class="card-body dashboard dashboard">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="fs-18 text-white mb-2">Today's Check-in </p>
                            <span class="fs-48 text-white font-w600 numbers">{{ $data->todayCheckIn }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-checkout">
                <div class="card-body dashboard">
                    <div class="media align-items-center">
                        </span>
                        <div class="media-body">
                            <p class="fs-18 text-white mb-2">Today's Check-out</p>
                            <span class="fs-48 text-white font-w600 numbers">{{ $data->todayCheckOut }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-used-sick">
                <div class="card-body dashboard">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="fs-18 text-white mb-2">Availed Sick Leaves</p>
                            <span class="fs-48 text-white font-w600 numbers">{{ $data->leaveQuota->used_sick_leaves ?? ' ' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-left-sick">
                <div class="card-body dashboard">
                    <div class="media align-items-center">
                        </span>
                        <div class="media-body">
                            <p class="fs-18 text-white mb-2">Remaining Sick Leaves</p>
                            <span class="fs-48 text-white font-w600 numbers">{{ $data->leaveQuota->remaining_sick_leaves ?? ' ' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-used-casual">
                <div class="card-body dashboard">
                    <div class="media align-items-center">

                        </span>
                        <div class="media-body">
                            <p class="fs-18 text-white mb-2">Availed Casual Leaves</p>
                            <span class="fs-48 text-white font-w600 numbers">{{ $data->leaveQuota->used_casual_leaves ?? ' ' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-left-casual">
                <div class="card-body dashboard">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="fs-18 text-white mb-2">Remaining Casual Leaves</p>
                            <span class="fs-48 text-white font-w600 numbers">{{ $data->leaveQuota->remaining_casual_leaves ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-lg-12">
                <h4 class="card-title">Announcements</h4>
                {{-- <div class="card">
                    <div class="card-header">
                        <div class="row w-100">
                            <div class="col-lg-6">

                            </div>
                            <div class="col-lg-6 text-right">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div> --}}
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div id="data-wrapper">
                    {{-- @foreach ($announcements as $key => $announcement)
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="post-details">
                                    <h3 class="mb-2 text-black">{{ $announcement->title }}</h3>
                                    <ul class="mb-4 post-meta">
                                        <li class="post-author">{{ $announcement->updatedBy->first_name . ' ' . $announcement->updatedBy->last_name }}</li>
                                        <li class="post-date"><i class="fa fa-calender"></i>{{ \Carbon\Carbon::parse($announcement->created_at)->format('d M Y') }}</li>
                                    </ul>
                                    <div class="row mt-2">
                                        {!! $announcement->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach --}}
                </div>

                <!-- Data Loader -->
                <div class="auto-load text-center">
                    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <path fill="#000" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                            <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        var ENDPOINT = "{{ route('employee.dashboard') }}";
        var page = 1;
        infinteLoadMore(page);

        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                page++;
                if (page == 1) {
                    infinteLoadMore(page);
                }
            }
            if (page > 1) {
                $('.auto-load').html("<a href='{{ route('employee.announcements.listing') }}' class='btn btn-rounded btn-sm btn-outline-primary ml-3'> See more <a>");
                $('.auto-load').show();
            }
        });

        function infinteLoadMore(page, search = null) {
            $.ajax({
                    url: ENDPOINT + "?page=" + page,
                    datatype: "html",
                    type: "get",
                    beforeSend: function() {
                        $('.auto-load').show();
                    },
                    data: $('#SearchForm').serialize()
                })
                .done(function(response) {
                    if (response.length == 0) {
                        $('.auto-load').html("");
                        return;
                    }
                    $('.auto-load').hide();
                    if (search) {
                        $("#data-wrapper").html(response);
                        search = false;
                    } else {
                        $("#data-wrapper").append(response);
                    }
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occured');
                });
        }
    </script>
@endsection
