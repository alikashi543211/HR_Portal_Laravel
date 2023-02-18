@extends('employees.layouts.app')
@section('title')
    Announcements
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

        .row.mt-2 p {
            margin-left: 1rem;
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

        /* fa-regular fa-clock */
        /* .fa-calendar-alt:before,
                                                                                                                        .fa-calendar-days:before {
                                                                                                                            content: "\f073";
                                                                                                                            font-family: 'Font Awesome 5 Free';
                                                                                                                            font-style: normal;
                                                                                                                            margin-right: 0.3rem;
                                                                                                                            margin-left: 1rem;
                                                                                                                        } */
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
                                <h4 class="card-title">Announcements</h4>
                            </div>
                            <div class="col-lg-6 text-right">
                                <form action="" id="SearchForm">
                                    <div class="input-group search-area d-lg-inline-flex d-none">
                                        <input type="text" class="form-control custom-height" id="searchBar" name="search" value="{{ request('search') }}" placeholder="Search by Title">
                                        <div class="input-group-append">
                                            <button class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                                        </div>
                                    </div>
                                </form>
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

    <div class="modal fade show" id="basicModal" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Leave Request</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure want to delete it.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light model-button-close" data-dismiss="modal">Close</button>
                    <a href="" id="deleteRequest" class="btn btn-primary model-button-continue">Continue</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var ENDPOINT = "{{ route('employee.announcements.listing') }}";
        var page = 1;
        infinteLoadMore(page);

        $('.deleteBtn').on('click', function() {
            var id = $(this).attr('id');
            $('#deleteRequest').attr("href", $(this).attr('data-delete-url'))
        })

        $('#searchBar').on('input', function() {
            page = 1;
            if ($(this).val() == '') {
                $("#data-wrapper").html('');
                infinteLoadMore(1);
            } else {
                infinteLoadMore(1, true);
            }

        })





        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                page++;
                infinteLoadMore(page);
            }
        });

        function infinteLoadMore(page, search = null) {
            var searchSub = null;
            if (searchSub) {
                searchSub.abort();
            }
            searchSub = $.ajax({
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
                        $("#data-wrapper").html(response);
                        $('.auto-load').html("We don't have more data to display :(");
                        $('.auto-load').show()
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

        // var searchSub = ''

        // function ajaxcall() {
        //     if (searchSub) {
        //         searchSub.abort();
        //     }
        //     searchSub = $.ajax({
        //         type: 'GET',
        //         url: "{{ route('employee.announcements.listing') }}",
        //         headers: {
        //             'X-CSRF-TOKEN': "{{ csrf_token() }}"
        //         },
        //         data: $('#SearchForm').serialize(),
        //         success: function(data) {
        //             $('#TableList').html(data.html)
        //         }
        //     });
        // }
    </script>

    <script></script>
@endsection
