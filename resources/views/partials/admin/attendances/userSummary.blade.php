@php
$period = getPeriod();
$numbers = [1,2,3,4,5,6,7,8,9,10];
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <b>Total Late Minutes:</b>
                            </div>
                            <div class="col-md-6">
                                <b id="showMinutes">4758</b>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <b>Total Late Deduction:</b>
                            </div>
                            <div class="col-md-6">
                                <b id="showDeduction">4758</b>
                            </div>
                        </div>
                    </div>
                    @if(checkPermission(ATTENDANCES, WRITE))
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-primary" id="edit-time">Edit</button>
                                <button class="btn btn-primary" id="cancel-time" style="display: none">Cancel</button>
                                <button class="btn btn-primary" id="update" style="display: none">Update</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <table class="table table-striped">
                    <thead class=" text-white" style="background-color: #51bcda;">
                        <th> Sr# </th>
                        <th> Date </th>
                        <th> Check In </th>
                        <th> Check Out </th>
                        <th> Late </th>
                        <th> Deduction </th>
                        <th> Status </th>
                        <th> Check in Updated By </th>
                        <th> Check out Updated By </th>
                        <th> Exempted By </th>
                        <th> Adjust Leave </th>
                    </thead>
                    <tbody id="userSummaryTBody">
                        <form action="{{ url('admin/attendances/bulk-update') }}" id="attendance-form" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Request::segment(4) }}">
                            @foreach($period AS $key => $date)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{$date->format('Y-m-d')}}</td>
                                <td><span class="datetime-input" style="display: none"><input type="time" value="{{ $data->getCheckInTime($date) }}" class="form-control" name="checkIn[{{ $date->format('Y-m-d') }}]"></span><span class="datetime-td">{{$data->getCheckInTime($date)}}</span></td>
                                <td><span class="datetime-input" style="display: none"><input type="time" value="{{ $data->getCheckOutTime($date) }}" class="form-control" name="checkOut[{{ $date->format('Y-m-d') }}]"></span><span class="datetime-td">{{$data->getCheckOutTime($date)}}</span></td>
                                @php $res = $data->getLateMinutes($date); @endphp
                                @if(is_array($res))
                                <td class="minutes" data-minute="{{$res['minutes']}}" data-type="read_able" data-readable="{{$res['read_able']}}">{{$res['read_able']}}</td>
                                <td class="deduction" data-deduction="{{$res['deduction']}}">{{$res['deduction']}} pkr</td>
                                @else
                                <td data-minute="0">--</td>
                                <td data-minute="0">--</td>
                                @endif
                                <td>
                                    @php
                                    $attendance = $data->checkAttendance($date);
                                    @endphp
                                    {{getTitleText($attendance)}} <br>
                                    @if($attendance == ON_LEAVE || $attendance == HALF_DAY)
                                    @if($leave = $data->getLeaveDetails($date))
                                    <span class="small">
                                        {{ getLeavePeriodType($leave->period_type) }} <br>
                                        {{ getLeaveType($leave->type) }} <br>
                                        {{ getLeaveAdjust($leave->leave_adjust) }} <br>
                                    </span>
                                    @else
                                    System Marked
                                    @endif
                                    @endif
                                </td>
                                <td>{{getCheckInUpdatedBy($date->format('Y-m-d'), $data->id)}}</td>
                                <td>{{getCheckOutUpdatedBy($date->format('Y-m-d'), $data->id)}}</td>
                                <td>{{getExceptionDetailsUpdatedBy($date->format('Y-m-d'), $data->id)}}</td>
                                <td>
                                    @if(checkPermission(ATTENDANCES, WRITE))
                                    <div class="row">
                                        <div class="col-md-12 justify-content-center d-flex">
                                            @if(getTitleText($data->checkAttendance($date)) == 'Attendance Exception')
                                            <button type="button" data-id="{{ $data->id }}" data-date="{{ $date->format('Y-m-d') }}" class="btn btn-danger exception-attendance delete-exception mt-0">Delete Exception</button>
                                            @else
                                            <a data-id="{{ $data->id }}" data-name="{{ $data->full_name }}" data-date="{{ $date->format('Y-m-d') }}" href="#!" class="btn add-half-summary-leave btn-info push-right mr-1">Half</a>
                                            <a href="#!" data-id="{{ $data->id }}" data-name="{{ $data->full_name }}" data-date="{{ $date->format('Y-m-d') }}" class="btn add-full-summary-leave btn-info push-right mr-1">Full</a>
                                            <button type="button" data-id="{{ $data->id }}" data-date="{{ $date->format('Y-m-d') }}" class="btn btn-sm btn-warning exception-attendance add-exception mt-0">Exception</button>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('partials.admin.attendances.modals')

@include('partials.confirmation-popup')

@section('footer_js')

@parent
@include('partials.admin.attendances.listing-js');
<script>
    $(document).on('click', '#edit-time', function () {
        $(this).hide()
        $('#cancel-time').show();
        $('#update').show();
        $('.datetime-input').show();
        $('.datetime-td').hide();
        $('.add-half-summary-leave,.add-full-summary-leave').hide();
        $('.exception-attendance').hide();
    });
    $(document).on('click', '#cancel-time', function () {
        $('#edit-time').show()
        $('#cancel-time,#update').hide();
        $('.datetime-input').hide();
        $('.datetime-td').show();
        $('.add-half-summary-leave,.add-full-summary-leave').show();
        $('.exception-attendance').show();
    });

    $(document).on('click', '#update', function () {
        $('#attendance-form').submit();
    });

    var totalMinutes = 0;
    $('.minutes').each(function () {
        totalMinutes += $(this).data('minute');
    });
    $('#showMinutes').html(totalMinutes + ' minutes');
    var totalDeduction = 0;
    $('.deduction').each(function () {
        totalDeduction += $(this).data('deduction');
    });
    $('#showDeduction').html((Math.round(totalDeduction * 100) / 100) + ' pkr');

    $(document).on('click', '.add-exception', function () {
        $.ajax({
            url: "{{url('admin/attendances/ajax/add-exception')}}",
            context: document.body,
            method: 'POST',
            data: {
                user_id: $(this).data('id'),
                date: $(this).data('date'),
                _token: csrf_token
            }
        }).done(function (data) {
            location.reload();
        });
    });

    $(document).ready(function () {

        $('.delete-exception').click(function () {
            console.log('here');
            var deleteExceptionUrl = "{{url(config('data.admin.attendance.delete-exception'))}}" + "/" + $(this).data('id') + "?date=" + $(this).data('date');
            console.log(deleteExceptionUrl);
            $('.confirmation-yes').attr("href", deleteExceptionUrl);
            $('#confirmationModal').modal('show');
        });
    });

</script>
@endsection
