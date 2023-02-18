@php
$period = getPeriod();
$numbers = [1,2,3,4,5,6,7,8,9,10];
@endphp
<div class="row">
    <div class="col-md-3 ">
        <form action="{{url('admin/attendances/listing')}}" method="get" id="dateForm">
            <input type="text" autocomplete="off" name="date" id="datepicker" class="form-control" value="{{request('date') ? request('date') : date('F, Y')}}">
            <input type="hidden" name="search" value="{{request('search')}}">
        </form>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-end align-items-top mb-4">
            @foreach ($numbers as $item)
            <span class="mr-4 text-center"><i title="{{getTitleText($item)}}" class="mr-2 fa {{getAttendanceIcon($item)}} {{getTextColor($item)}}"></i><br />{{getTitleText($item)}}</span>
            @endforeach
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-white" style="background-color: #51bcda;">
            <th> Sr# </th>
            <th> Name </th>
            @foreach($period AS $key => $date)
            <th>{{$date->format('d')}} <br> {{substr($date->format('D'), 0, 1)}}</th>
            @endforeach
        </thead>
        <tbody>
            @foreach($data AS $index => $res)

            <tr style="cursor: pointer">
                <td> {{$data->firstItem() + $index}} </td>
                <td><a href="{{ checkPermission(LEAVES, READ) ? url('admin/attendances/summary/'.$res->id.'?month='.request('date')) : 'javascript:void(0)'}}" style="color: black;font-weight:bolder;text-decoration: underline">{{$res->full_name}}</a></td>
                @foreach($period AS $key => $date)
                @php
                $number = $res->checkAttendance($date);
                if($number == ON_LEAVE){
                $leave = $res->getLeaveDetails($date);
                }
                @endphp
                <td><i data-name="{{$res->full_name}}" data-date="{{$date->format('Y-m-d')}}" data-id="{{$res->id}}" title="{{getTitleText($number)}}" class="{{getModalClass($number)}} fa {{getAttendanceIcon($number)}} {{getTextColor($number)}} @if($number==ON_LEAVE) view-leave-details-popup @endif" @if($number==ON_LEAVE) data-leave-period-type="{{ getLeavePeriodType($leave->period_type) }}" data-leave-period="{{ getLeavePeriod($leave->period) }}" data-leave-type="{{ getLeaveType($leave->type) }}" data-leave-adjust="{{ getLeaveAdjust($leave->leave_adjust) }}" @endif></i></td>
                @endforeach
            </tr>
            @endforeach
        </tbody>

    </table>
    {{ $data->appends(request()->query())->links() }}
</div>


@include('partials.admin.attendances.modals')

@section('footer_js')

@parent
@include('partials.admin.attendances.listing-js');
<script>
    $(document).ready(function () {

        $('.view-leave-popup').click(function (e) {
            e.preventDefault();
            $('#leaveModal').modal('show');
            $('#UserName').html($(this).data('name') + ' (' + $(this).data('date') + ')');
            $('#leaveUserName').val($(this).data('name') + ' (' + $(this).data('date') + ')');
            $('#leaveUserId').val($(this).data('id'));
            $('#leaveDate').val($(this).data('date'));
            currentUserId = $(this).data('id');
            showDate.html($(this).data('date'));
        });

        @if(session('leave_error'))
        $('#leaveModal').modal('show');
        @endif

        $('.delete-exception-popup').click(function (e) {
            var deleteExceptionUrl = " {{url(config('data.admin.attendance.delete-exception'))}}" + "/" + $(this).data('id') + "?date=" + $(this).data('date');
            $('.confirmation-yes').attr("href", deleteExceptionUrl);
            $('#confirmationModal').modal('show');
        });
        $('.view-leave-details-popup').click(function (e) {
            $('#leaveDetails').modal('show');
            $('#leave-popup-date').html($(this).data('date'));
            $('#leave-popup-name').html($(this).data('name'));
            $('#leave-popup-period').val($(this).data('leave-period-type'));
            $('#leave-popup-type').val($(this).data('leave-type'));
            $('#leave-popup-adjust').val($(this).data('leave-adjust'));
        })
    });

</script>
@endsection
