@extends('employees.layouts.app')
@section('title')
    Attendance
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <style>
        .fa-calendar-minus-o:before {
            font-family: "FontAwesome";
        }

        .fa-user-minus:before {
            content: '\f503';
            font-family: "FontAwesome";
        }

        table tbody tr {
            cursor: pointer;
        }

        .fa-warning:before,
        .fa-exclamation-triangle:before {
            font-family: 'FontAwesome';
        }

        .fa-clock-o:before {
            font-family: 'FontAwesome';
        }

        .fa-user-circle:before {
            font-family: 'FontAwesome';
        }

        .d-flex.display-icon {
            width: 14rem;
            justify-content: ;
            align-items: center;
            margin-top: 1rem;
        }

        .d-flex.justify-content-around.icon-div {
            flex-wrap: wrap;
            width: 70rem;
            align-self: center;
        }

        .d-flex.display-icon i {
            margin-right: 5px
        }

        .early_out {
            background-image: url("{{ asset('employeesAsset/icons/early_out.svg') }}");
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
        }

        .lateness {
            background-image: url("{{ asset('employeesAsset/icons/lateness.svg') }}");
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
        }

        .holiday {
            background-image: url("{{ asset('employeesAsset/icons/holiday.svg') }}");
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
        }

        .present {
            background-image: url("{{ asset('employeesAsset/icons/present.svg') }}");
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
        }

        .absent {
            background-image: url("{{ asset('employeesAsset/icons/absent.svg') }}");
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
        }

        .on_leave {
            background-image: url("{{ asset('employeesAsset/icons/on_leave.svg') }}");
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
        }

        .weekend {
            background-image: url("{{ asset('employeesAsset/icons/weekend.svg') }}");
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
        }

        .half_day {
            background-image: url("{{ asset('employeesAsset/icons/half_day.svg') }}");
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
        }

        .no_attendence_policy {
            background-image: url("{{ asset('employeesAsset/icons/no_attendance_policy.svg') }}");
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
        }

        .attendence_exception {
            background-image: url("{{ asset('employeesAsset/icons/attendance_exception.svg') }}");
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
        }

        .icon-text {
            margin-bottom: 0px;
            margin-left: 1rem;
        }

        /* filter section */

        button.btn.dropdown-toggle.btn-light.bs-placeholder {
            padding: 8px;
            border-radius: 7px;
        }

        .dropdown.bootstrap-select.form-control.default-select.show {
            margin-right: 20px;
        }

        button.btn.dropdown-toggle.btn-light.bs-placeholder {
            margin-top: 0.5rem;
        }

        .btn.dropdown-toggle.btn-light .filter-option {
            height: unset;
        }

        button.btn.dropdown-toggle.btn-light {
            height: 42px;
            margin-top: 8px;
        }

        .dropdown.bootstrap-select.form-control.default-select {
            border: none !important;
        }

        #searchForm .row.w-100 .col-lg-3 {
            margin: auto;
        }

        button.btn.dropdown-toggle.btn-light {
            border-radius: 8px;
            padding: 8px
        }

        .filter.d-flex {
            width: 30rem;
        }
    </style>
    <style>
        #TableList#main.jumbotron-custom {
            /* background: #fff; */
        }

        .jumbotron-custom {
            padding: 1rem 2rem;
            margin-bottom: 1rem;
            background-color: #e9ecef;
            border-radius: 0.3rem;
        }

        #right.fa,
        #left.fa {
            font-size: 0.5em;
        }

        .row .col-sm-10.col-sm-offset-1 table.table {
            width: 100%;
        }

        table.table th {
            text-align: center;
        }

        table.table tr td,
        table.table th td {
            width: 120px;
            height: 100px;
            text-align: center;
            line-height: 100px !important;
            font-size: 1.5em;
        }

        table.table tr td,
        table.table th td,
        #year {
            font-family: monospace;
        }

        .hover {
            background: #eee;
        }

        #about {
            font-size: 5em !important;
            position: absolute;
            top: -35px;
            right: 15px;
        }

        #about a {
            text-decoration: none;
        }
    </style>
    {{-- calender style --}}
    <style>
        .box {
            display: flex;
            flex-direction: column;
        }

        td.day .box .info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            /* padding: 0; */
            /* margin: 0; */
            /* height: auto; */
        }


        .info p {
            font-size: 0.8rem;
            color: black;
            padding: 0;
            margin: 0;
        }

        p.date {
            color: black;
            font-weight: bold;
            padding: 0;
            margin: 0;
            font-size: 1.4rem;
        }

        .border-1 {
            border: 1px solid #b3b1b1 !important;
        }


        .total_minutes_display {
            margin-left: 2rem;
        }

        .total_deduc_display {
            margin-left: 1rem;
        }


        .flip-card-inner {
            overflow: hidden
        }

        .day.border-1.flip-card {
            padding: 0 !important
        }

        .box.flip-card-front,
        .box.flip-card-back {
            padding: 0 5px;
        }

        .box.flip-card-back {
            position: relative;
            bottom: -47px;
            transition-property: bottom;
            transition-duration: 0.5s;
            transition-delay: 0.2s;
        }

        .flip-card-inner:hover .box.flip-card-back {
            bottom: 0;
            transition-property: bottom;
            transition-duration: 0.5s;
            transition-delay: 0.2s;

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
                        <h4 class="card-title">Attendance Record</h4>
                        <div class="filter d-flex align-items-center ">
                            <label for="" class="mr-2"><strong class="text-black">Year: </strong></label>
                            <div class="dropdown bootstrap-select form-control default-select show">
                                <select class="form-control default-select" id="year" tabindex="-98">
                                    @foreach ($years as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="" class="ml-2 mr-2"><strong class="text-black">Month: </strong></label>
                            <div class="dropdown bootstrap-select form-control default-select show">
                                <select class="form-control default-select" id="month" tabindex="-98">
                                    <option value="">Select Month</option>
                                    @foreach ($month as $m)
                                        <option value="{{ $m->format('m') }}" {{ $m->format('m') == \Carbon\Carbon::now()->format('m') ? 'Selected' : '' }}>{{ $m->format('M') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around icon-div">
                        <div class="d-flex display-icon">
                            <div class="early_out">

                            </div>
                            {{-- class="" <div>
                                </div> --}}
                            <h5 class="icon-text">Early Out</h5>
                        </div>
                        <div class="d-flex display-icon">
                            <div class="lateness">

                            </div>
                            <h5 class="icon-text" class="ml-2">Late In</h5>
                        </div>
                        <div class="d-flex display-icon">
                            <div class="holiday">

                            </div>
                            <h5 class="icon-text">Holiday</h5>
                        </div>
                        <div class="d-flex display-icon">
                            <div class="present">

                            </div>
                            <h5 class="icon-text">Present</h5>
                        </div>
                        <div class="d-flex display-icon">
                            <div class="absent">

                            </div>
                            <h5 class="icon-text">Absent</h5>
                        </div>
                        <div class="d-flex display-icon">
                            <div class="on_leave">

                            </div>
                            <h5 class="icon-text">On Leave</h5>
                        </div>
                        <div class="d-flex display-icon">
                            <div class="weekend">

                            </div>
                            <h5 class="icon-text">Weekend</h5>
                        </div>
                        <div class="d-flex display-icon">
                            <div class="half_day">

                            </div>
                            <h5 class="icon-text">Half Day</h5>
                        </div>
                        <div class="d-flex display-icon">
                            <div class="no_attendence_policy">

                            </div>
                            <h5 class="icon-text">No Attendance Policy</h5>
                        </div>
                        <div class="d-flex display-icon">
                            <div class="attendence_exception">

                            </div>
                            <h5 class="icon-text">Attendance Exception</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="TableList">
                            <div class="container-custom" id="main">
                                <div class="jumbotron-custom">
                                    <h1 class="text-center">
                                        <button id="left" class="border-0">
                                            <i class="fa fa-chevron-left"> </i>
                                        </button><span>&nbsp;</span>
                                        <span id="monthHead">
                                        </span>
                                        <span>&nbsp;</span>
                                        <span id="yearHead"> </span>
                                        <span>&nbsp;</span>
                                        <button id="right" class="border-0" disabled="true">
                                            <i class="fa fa-chevron-right"> </i>
                                        </button>
                                    </h1>
                                </div>
                                <div class="row d-flex">
                                    <div class="total_minutes_display">
                                        <h4 class="total_minutes_heading">Total Late Minutes</h4>
                                        <h4 class="total_minutes_count">-</h4>
                                    </div>
                                    <div class="total_deduc_display">
                                        <h4 class="total_deduc_heading">Total Deduction</h4>
                                        <h4 class="total_deduc_count">-</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-sm-offset-1">
                                        <table class="table"></table>
                                    </div>
                                </div>
                            </div><span id="about"><a href="#" target="_blank"><i class="fa fa-question-circ"></i></a></span>
                            {{-- @include('employees.attendance.row') --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        var currentDate = new Date();
        getDataSource($('#month').val(), $('#year').val());

        function generateCalendar(d, jsonData) {
            function monthDays(month, year) {
                var result = [];
                var days = new Date(year, month, 0).getDate();
                for (var i = 1; i <= days; i++) {
                    result.push(i);
                }
                return result;
            }

            Date.prototype.monthDays = function() {
                var d = new Date(this.getFullYear(), this.getMonth() + 1, 0);
                return d.getDate();
            };
            var details = {
                // totalDays: monthDays(d.getMonth(), d.getFullYear()),
                totalDays: d.monthDays(),
                weekDays: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            };
            var start = new Date(d.getFullYear(), d.getMonth()).getDay();
            var cal = [];
            var day = 1;
            var jsonIndex = 0;
            for (var i = 0; i <= 6; i++) {
                cal.push(['<tr>']);
                for (var j = 0; j < 7; j++) {
                    if (i === 0) {
                        cal[i].push('<td>' + details.weekDays[j] + '</td>');
                    } else if (day > details.totalDays) {
                        cal[i].push('<td>&nbsp;</td>');
                    } else {
                        if (i === 1 && j < start) {
                            cal[i].push('<td>&nbsp;</td>');
                        } else {
                            dayNum = day++;
                            cal[i].push(`<td class="day border-1 flip-card" title="${jsonData[jsonIndex].title}">
                                                <div class="flip-card-inner">
                                                    <div class="box flip-card-front">
                                                        <div class="info">
                                                            <i class="${jsonData[jsonIndex].icon}"></i>
                                                            <p class="date">${dayNum}</p>
                                                        </div>
                                                        <div class="info">
                                                            <p class="checkIn">CheckIn</p>
                                                            <p class="checkIn--time">${jsonData[i].check_in_time}</p>
                                                        </div>
                                                        <div class="info">
                                                            <p class="checkOut">CheckOut</p>
                                                            <p class="checkOut--time">${jsonData[i].check_out_time}</p>
                                                        </div>
                                                    </div>
                                                    <div class="box flip-card-back">
                                                        <div class="info">
                                                            <p class="checkIn">Minutes:</p>
                                                            <p class="checkIn--time">${jsonData[i].minutes}</p>
                                                        </div>
                                                        <div class="info">
                                                            <p class="checkOut">Deduction</p>
                                                            <p class="checkOut--time">${jsonData[i].deduction}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                        </td>`)
                            jsonIndex++;
                        }
                    }
                }
                cal[i].push('</tr>');
            }
            cal = cal.reduce(function(a, b) {
                return a.concat(b);
            }, []).join('');
            $('table').append(cal);
            $('.jumbotron-custom .text-center #monthHead').text(details.months[d.getMonth()]);
            $('.jumbotron-custom .text-center #yearHead').text(d.getFullYear());
            $('td.day').mouseover(function() {
                $(this).addClass('hover');
            }).mouseout(function() {
                $(this).removeClass('hover');
            });
        }

        // generateCalendar(currentDate);

        $('#left').click(function() {
            $('#month').selectpicker('destory');
            $('#month').val("");
            $('#month').selectpicker('refresh');

            $('table').text('');
            $("#right").attr('disabled', false);

            if (currentDate.getMonth() === 0 || currentDate.getMonth() === -1) {
                currentDate = new Date(currentDate.getFullYear() - 1, 11);
                getDataSource(12, currentDate.getFullYear());
            } else {
                currentDate = new Date(currentDate.getFullYear(), (currentDate.getMonth() - 1))
                getDataSource(currentDate.getMonth() + 1, currentDate.getFullYear());
            }
        });
        $('#right').click(function() {
            $('#month').selectpicker('destory');
            $('#month').val("");
            $('#month').selectpicker('refresh');


            $('table').html('<tr></tr>');
            $('#month').val("")
            let today = new Date();

            if ((currentDate.getMonth()) >= (today.getMonth() - 1) && currentDate.getFullYear() === today.getFullYear()) {
                $(this).attr('disabled', 'true');
            }
            if (currentDate.getMonth() === 11) {
                currentDate = new Date(currentDate.getFullYear() + 1, 0);
                // console.log("==11 " + currentDate.getFullYear() + 1 + " " + 12);
                // generateCalendar(currentDate);
                getDataSource(1, currentDate.getFullYear());
            } else {
                currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1)
                // console.log("==else " + currentDate.getFullYear() + " " + (currentDate.getMonth()));
                getDataSource(currentDate.getMonth() + 1, currentDate.getFullYear());
            }
        });

        let year = null
        let month = null

        // adding calender

        // fileter
        $('#year').on('change', function() {
            $('#month').selectpicker('destory');
            getMonth($(this).val());
        })

        //filter
        $('#month').on('change', function() {
            year = $('#year').val();
            month = $(this).val();
            let today = new Date();
            if (parseInt(month) - 1 === today.getMonth() && today.getFullYear() == year) {
                $('#right').attr('disabled', true);
            } else {
                $('#right').prop('disabled', false);
            }
            getDataSource(month, year);
        })




        function getMonth(year) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('employee.attendance.getMonths') }}",
                data: {
                    year: year,
                },
                success: function(data) {
                    console.log(data.months);
                    $('#month').html(data.months);
                    $('#month').selectpicker('refresh');
                }
            });
        }

        function getDataSource(month, year) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('employee.attendance.detail') }}",
                data: {
                    month: month,
                    year: year,
                },
                success: function(data) {
                    console.log(data);
                    let ajaxData = JSON.parse(data.data);
                    $('.total_minutes_count').html(ajaxData.total.deduction);
                    $('.total_deduc_count').html(ajaxData.total.minutes);
                    $('table').html('<tr></tr>');
                    if (parseInt(month) === 11) {
                        currentDate = new Date(parseInt(year), parseInt(month) - 1, 1);
                        generateCalendar(currentDate, ajaxData.attendance);

                    } else {
                        currentDate = new Date(year, parseInt(month) - 1, 1);
                        generateCalendar(currentDate, ajaxData.attendance);
                    }

                }
            });
        }

        function dataGetting(data) {
            console.log(data);
        }
    </script>
@endsection
