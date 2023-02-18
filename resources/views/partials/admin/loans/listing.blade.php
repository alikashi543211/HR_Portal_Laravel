<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Sr# </th>
            <th> Name </th>
            <th> Loan Amount </th>
            <th> Installments </th>
            <th> Started From </th>
            <th> Remaining Amount </th>
            <th> Status </th>
            <th> Actions </th>
        </thead>
        <tbody>
            @foreach ($data as $loan)
                <tr>
                    <td>{{ $loan->id }}</td>
                    <td>{{ $loan->user->full_name ?? 'N/A' }}</td>
                    <td>{{ $loan->amount }}</td>
                    <td>{{ $loan->installments()->count() }}</td>
                    <td>{{ date('Y-m-d', strtotime($loan->installments()->first()->month)) }}</td>
                    <td>{{ $loan->installments()->where('status', PENDING)->sum('amount') }}</td>
                    <td>
                        @if ($loan->status == LOAN_PENDING)
                            Pending
                        @elseif($loan->status == LOAN_APPROVED)
                            Approved
                        @elseif($loan->status == LOAN_REJECTED)
                            Rejected
                        @endif
                    </td>
                    <td style="display: flex;height: 4.1rem;gap: 0.5rem;">
                        @if (checkPermission(LOAN, WRITE))
                            @if ($loan->status == LOAN_PENDING)
                                <form action="{{ url(config('data.admin.loans.status-update')) }}" method="POST">
                                    @csrf
                                    <input type="text" name="id" value="{{ $loan->id }}" hidden>
                                    <input type="text" name="status" value="{{ LOAN_APPROVED }}" hidden>
                                    <button type="submit" class="btn btn-primary border-0">Approve</button>
                                </form>
                                <form action="{{ url(config('data.admin.loans.status-update')) }}" method="POST">
                                    @csrf
                                    <input type="text" name="id" value="{{ $loan->id }}" hidden>
                                    <input type="text" name="status" value="{{ LOAN_REJECTED }}" hidden>
                                    <button type="submit" class="btn btn-danger border-0">Reject</button>
                                </form>

                                <a href="{{ url(config('data.admin.loans.edit') . '/' . $loan->id) }}" class="btn btn-primary">Edit</a>
                            @elseif($loan->status == LOAN_APPROVED || $loan->status == LOAN_REJECTED)
                                <a href="{{ url(config('data.admin.loans.edit') . '/' . $loan->id) }}" class="btn btn-primary">Edit</a>
                                <a href="{{ url(config('data.admin.loans.delete') . '/' . $loan->id) }}" class="btn btn-danger">Delete</a>
                            @endif
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}
</div>
