<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Full Name </th>
            <th> Type </th>
            <th> Period Type </th>
            <th> From Date </th>
            <th> To Date </th>
            <th> Reason </th>
            <th> Status </th>
            @if (checkPermission(LEAVE_REQUEST, WRITE))
                <th> Action </th>
            @endif
        </thead>

        <tbody>
            @foreach ($data as $index => $info)
                <tr>
                    <td> {{ $info->user->FullName ?? 'N/A' }} </td>
                    <td> {{ $info->type == 0 ? 'Sick' : 'Casual' }} </td>
                    <td> {{ $info->period_type == 0 ? 'Full Day' : ($info->period_type == 1 ? 'First Half' : 'Second Half') }} </td>
                    <td> {{ date('d-M-Y', strtotime($info->from_date)) }} </td>
                    <td> {{ date('d-M-Y', strtotime($info->to_date)) }} </td>
                    <td> {{ strlen($info->reason > 30) ? substr($info->reason, 0, 30) . '...' : $info->reason }} </td>
                    <td> {{ $info->status == PENDING ? 'Pending' : ($info->status == ACCEPTED ? 'Accepted' : 'Rejected') }} </td>
                    <td>
                        <a href="{{ url(config('data.admin.leave-request.details') . '/' . $info->id) }}" class="btn btn-success btn-sm">Details</a>
                        {{-- <a href="#!" data-url="{{ url(config('data.admin.leave-request.delete') . '/' . $info->id) }}" class="confirmation-popup btn btn-danger btn-sm">Delete</a> --}}
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->appends(request()->query())->links() }}
</div>

@section('footer_js')
    @parent
    <script></script>
@endsection
