@extends('employees.layouts.app')
@section('title')
    Attendance
@endsection

@section('css')
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

        .btn.btn-sm.orange {
            border-radius: 5px
        }

        .back-icon {
            background: #F47429;
            color: #fff;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-style: none;
        }

        .fa-arrow-left:before {
            content: "\f060";
            font-family: 'FontAwesome';
            font-style: initial;
            font-weight: initial;
        }

        a.back-icon:hover {
            color: #fff;
            text-decoration: unset;
        }

        .flex-center {
            display: flex;
            justify-content: center;
            align-items: center;
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
                        <h4 class="card-title flex-center">
                            <a href="{{ route('employee.attendance.listing') }}" class="back-icon mr-2">
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>
                            <p class="card-title">Attandance Record</p>
                        </h4>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th class="width80"><strong>Date</strong></th>
                                        <th class="width80"><strong>Check in time</strong></th>
                                        <th class="width80"><strong>Check out time</strong></th>
                                        <th class="width80"><strong>Minutes</strong></th>
                                        <th class="width80"><strong>Deduction</strong></th>
                                        <th class="width80"><strong>Status</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['attendance'] as $item)
                                        <tr>
                                            <td>{{ $item['date'] }}</td>
                                            <td>{{ $item['check_in_time'] }}</td>
                                            <td>{{ $item['check_out_time'] }}</td>
                                            <td>{{ $item['minutes'] }}</td>
                                            <td>{{ $item['deduction'] }}</td>
                                            <td>{{ $item['attendance_title'] }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>e
@endsection

@section('scripts')
@endsection
