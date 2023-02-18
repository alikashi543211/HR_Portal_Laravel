@php
$period = getPeriod();
@endphp
<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-white" style="background-color: #51bcda;">
            <tr>
                <th colspan="{{ cal_days_in_month(CAL_GREGORIAN, date('m', !empty(request('date')) ? strtotime(request('date')) : time()), date('Y', !empty(request('date')) ? strtotime(request('date')) : time()))+1 }}" style="font-size: 30px; font-weight:bolder;height:50px;align-items:center">Employee Attendance Sheet - {{ date('M Y', !empty(request('date')) ? strtotime(request('date')) : time()) }}</th>
            </tr>
            <tr>
                <th width="20"> Name </th>
                @foreach($period AS $key => $date)
                <th width="20">{{$date->format('d')}} <br> {{substr($date->format('D'), 0, 1)}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr></tr>
            @foreach($data AS $index => $res)
            <tr style="cursor: pointer">
                <td>{{$res->full_name}}</td>
                @foreach($period AS $key => $date)
                @php $title = getTitleText($res->checkAttendance($date)) @endphp
                <td>{{ $title }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
