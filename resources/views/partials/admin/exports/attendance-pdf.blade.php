@php
$period = getPeriod($payroll->date);
$month = date('m', strtotime($payroll->date));
$year = date('Y', strtotime($payroll->date));
$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month,$year);
@endphp
<table class="table table-striped" border="1" style="width:100%" cellspacing="0" cellpadding="0">
    <thead class=" text-white" style="background-color: #51bcda;">
        <tr>
            <th colspan="{{ $totalDaysInMonth }}" style="font-size: 30px; font-weight:bolder;height:50px;align-items:center">Employee Attendance Sheet - {{ $payroll->date }}</th>
        </tr>
        <tr>
            <th style="text-align: center"> Sr. </th>
            <th> Name </th>
            @foreach($period AS $key => $date)
            <th style="text-align: center">{{$date->format('d')}} <br> {{substr($date->format('D'), 0, 1)}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr></tr>
        @foreach($data AS $index => $res)
        <tr>
            <td style="text-align: center;font-size:14px">{{ $index+1 }}</td>
            <td style="padding-left:5px;font-size:14px"><b>{{$res->full_name}}</b></td>
            @foreach($period AS $key => $date)
            @php
            $number = $res->checkAttendance($date);
            $leave = $res->getLeaveDetails($date);
            if($number == ON_LEAVE || $number == HALF_DAY){
            $title = getPayrollLeaveSymbol(LEAVES,$leave);
            }else if($number == EXCEPTION_ATTENDANCE){
            $title = getPayrollLeaveSymbol(EXCEPTION_ATTENDANCE);
            }else if($number == ABSENT){
            $title = getPayrollLeaveSymbol(ABSENT);
            }else if($number == PUBLIC_HOLIDAY){
            $title = getPayrollLeaveSymbol(PUBLIC_HOLIDAY);
            }else $title = '--'
            @endphp
            <td style="text-align: center;font-size:13px"><b>{{ $title }}</b></td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
