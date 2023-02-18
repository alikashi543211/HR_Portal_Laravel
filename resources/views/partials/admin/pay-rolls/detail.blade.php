<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Full Name </th>
            <th> Gross Salary </th>
            <th> Base Salary </th>
            <th> House Rent </th>
            <th> Utility Bills </th>
            <th> Allowances </th>
            <th> Late Deductions </th>
            <th> Leave Deductions </th>
            <th> Net Salary </th>
            <th> Action </th>
        </thead>
        <tbody>
            @foreach($data as $key => $info)
            <tr>
                <td> {{ $info->user->FullName ?? 'N/A'  }} </td>
                <td> {{ convertValueToCommaSeparated($info->gross_salary) }} </td>
                <td> {{ convertValueToCommaSeparated($info->base_salary) }} </td>
                <td> {{ convertValueToCommaSeparated($info->house_rent) }} </td>
                <td> {{ convertValueToCommaSeparated($info->utility) }} </td>
                <td> {{ convertValueToCommaSeparated($info->allowances) }} </td>
                <td> {{ convertValueToCommaSeparated($info->late_deduction) }} </td>
                <td> {{ convertValueToCommaSeparated($info->leave_deduction) }} </td>
                <td> {{ convertValueToCommaSeparated($info->net_salary) }} </td>
                <td>
                    @if(checkPermission(PAY_ROLLS, WRITE))
                    @if(!$info->payRoll->lock)
                    <a href="#!" data-url="{{url(config('data.admin.payroll.userpayrolldelete').'/'.$info->id)}}" class="confirmation-popup btn btn-sm btn-danger" title="Delete">Delete</a>
                    @endif
                    <a href="#!" data-target="#openUserPayRoll{{$key}}" data-toggle="modal" id="view-userpayroll-modal{{$info->user->id ?? ''}}" title="Detail" class="btn btn-sm btn-info">View</a>
                    <a href="{{url(config('data.admin.payroll.salarySlipSingle').'?id='.$info->id)}}" title="Salary Slip" class="btn btn-sm btn-info">Salary Slip</a>
                    @include('partials.admin.pay-rolls.modal')
                    @endif
                </td>
            </tr> @endforeach </tbody>
    </table>
</div>



@section('footer_js')
@parent
<script>
    $(document).ready(function () {

        @if(session('user_pay_roll_success'))
        $('#view-userpayroll-modal' + '{{session("id")}}').click();
        @endif

    });

</script>
@endsection
