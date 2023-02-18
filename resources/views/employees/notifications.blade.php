@extends('employees.layouts.app')
@section('title')
    Notifications
@endsection

@section('css')
    <style>
        tbody tr td.font-awosome {
            cursor: pointer;
        }

        .fa-trash:before {
            content: "\f1f8";
            font-family: 'FontAwesome';
        }

        .fa-pencil:before {
            content: "\f040";
            font-family: 'FontAwesome';
        }

        input.form-control.custom-height {
            height: 44px;
        }

        .custom-pagination nav {
            float: right;
        }

        .post-details {
            overflow-wrap: anywhere;
        }

        .card.notification-row {
            position: relative;
        }

        .card.notification-row span {
            position: absolute;
            width: 20px;
            height: 20px;
            background: #f47429;
            right: 10px;
            top: 10px;
            border-radius: 50%;
        }

        a.notification-link {
            text-decoration: none;
            color: black
        }

        a.notification-link:hover {
            color: #fff !important
        }

        a.notification-link .row .col-sm-12 .card.notification-row:hover {
            background-color: #f47429;
            color: #fff !important;
        }

        a.notification-link .row .col-sm-12 .card.notification-row:hover span {
            background-color: #fff;
        }

        h3.mb-2 {
            color: inherit;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- row -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row w-100">
                            <div class="col-lg-6">
                                <h4 class="card-title">Notifications</h4>
                            </div>
                            <div class="col-lg-6 text-right">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
@endsection

@section('scripts')
    <script>
        var ENDPOINT = "{{ route('employee.notificationList') }}";
        var page = 1;
        infinteLoadMore(page);

        $('.deleteBtn').on('click', function() {
            var id = $(this).attr('id');
            $('#deleteRequest').attr("href", $(this).attr('data-delete-url'))
        })

        $('#searchBar').on('input change', function() {
            page = 1;
            if ($(this).val() == '') {
                console.log("ia m class");
                infinteLoadMore(1)
            } else
                infinteLoadMore(1, true)
        })





        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                page++;
                infinteLoadMore(page);
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
                        $('.auto-load').html("We don't have more data to display :(");
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


        $(document).on('click', '.notification-link', function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            var url = $(this).attr('href');
            var id = $(this).attr('data-notif-id');
            $.ajax({
                type: 'POST',
                url: "{{ route('employee.NotificationStatus') }}",
                data: {
                    id: id
                },
                success: function(data) {
                    window.location.replace(url);
                }
            });
        })
    </script>

    <script></script>
@endsection
