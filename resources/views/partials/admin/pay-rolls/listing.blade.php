<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Sr# </th>
            <th> Month </th>
            <th> User Count </th>
            <th> Created By </th>
            <th> Created At </th>
            <th> Action </th>
        </thead>
        <tbody>
            @foreach ($data as $index => $info)
                <tr>
                    <td> {{ $data->firstItem() + $index }} </td>
                    <td> {{ date('M, Y', strtotime(str_replace(',', '', $info->date))) }} </td>
                    <td> {{ count($info->payRolls) }} </td>
                    <td> {{ $info->createdBy ? $info->createdBy->full_name : 'N/A' }} </td>
                    <td> {{ date('d M, Y', strtotime($info->created_at)) }} </td>
                    <td>

                        @if (checkPermission(PAY_ROLLS, WRITE))
                            @if (!$info->lock)
                                <a href="#!" data-url="{{ url(config('data.admin.payroll.delete') . '/' . $info->id) }}" class="confirmation-popup btn btn-sm btn-danger" title="Delete">Delete</a>
                            @endif
                            <a href="{{ url(config('data.admin.payroll.detail') . '/' . $info->id) }}" title="Detail" class="btn btn-sm btn-info">View</a>
                            <a href="{{ url(config('data.admin.payroll.tax-listing') . '/' . $info->id) }}" title="Detail" class="btn btn-sm btn-info">View Tax</a>
                            <!-- <a href="{{ url(config('data.admin.payroll.pdf-payroll') . '?id=' . $info->id) }}" target="_blank" title="Payroll PDF" class="btn btn-sm btn-danger">PDF</a> -->
                            <a href="{{ url(config('data.admin.payroll.excel') . '?id=' . $info->id) }}" title="Payroll Excel" class="btn btn-sm btn-primary">Excel</a>
                            <!-- <a href="{{ url(config('data.admin.payroll.pdf') . '?id=' . $info->id) }}" title="Salary Slip PDF" class="btn btn-sm btn-danger">Salary Slip PDF</a> -->
                            <a href="{{ url('admin/pay-rolls/generate-pdf?id=' . $info->id) }}" title="Payroll PDF" class="btn btn-sm btn-danger">Generate PDF</a>
                            <!-- <a href="{{ url(config('data.admin.payroll.attendance-pdf') . '?id=' . $info->id) }}" title="Attendance PDF" class="btn btn-sm btn-info">Attendance PDF</a> -->
                            @if (!$info->lock)
                                <a href="#!" data-url="{{ url(config('data.admin.payroll.recalculate') . '/' . $info->id) }}" class="confirmation-popup btn btn-sm btn-info" title="Recalculate">Recalculate</a>
                                <a href="#!" data-url="{{ url(config('data.admin.payroll.lock') . '/' . $info->id) }}" class="confirmation-popup btn btn-sm btn-dark" title="Lock">Lock</a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
</div>



@section('footer_js')
    @parent
    <script>
        $(document).ready(function() {});
    </script>
@endsection
