 <table class="table table-responsive-md">
     <thead>
         <tr>
             <th class="width80"><strong>Sr.No</strong></th>
             <th><strong>Amount</strong></th>
             <th><strong>Installments</strong></th>
             <th><strong>Start From</strong></th>
             <th><strong>Remaining Amount</strong></th>
             <th><strong>Status</strong></th>
             <th><strong>Actions</strong></th>
         </tr>
     </thead>
     <tbody>
         @foreach ($loans as $key => $loan)
             <tr>
                 <td><strong>{{ $key + $loans->firstItem() }}</strong></td>
                 <td>{{ $loan->amount }}</td>
                 <td>{{ $loan->installments()->count() }}</td>
                 <td>{{ date('M, Y',strtotime($loan->installments()->orderBy('month', 'ASC')->first()->month)) }}</td>
                 <td>{{ $loan->installments()->where('status', PENDING)->sum('amount') }}</td>
                 <td>
                     @if ($loan->status == LOAN_PENDING)
                         <div class="badge pending">
                             Pending
                         </div>
                     @elseif($loan->status == LOAN_APPROVED)
                         <div class="badge approved">
                             Approved
                         </div>
                     @elseif($loan->status == LOAN_REJECTED)
                         <div class="badge rejected">
                             Rejected
                         </div>
                     @endif
                 </td>
                 {{-- <td>
                     <a href="{{ url(config('data.admin.loans.edit') . '/' . $loan->id) }}" class="btn btn-primary">Edit</a>
                     <a href="{{ url(config('data.admin.loans.delete') . '/' . $loan->id) }}" class="btn btn-danger">Delete</a>
                 </td> --}}
                 {{-- </tr> --}}
                 <td class="font-awosome">
                     @if ($loan->status == LOAN_PENDING)
                         <a href="{{ route('employee.loans.edit', $loan->id) }}" class="mr-2"><i class="fa-regular fa-pencil"></i></a>
                         <a class="deleteBtn" data-toggle="modal" data-target="#basicModal" data-id="{{ $loan->id }}" data-delete-url="{{ route('employee.loans.delete', $loan->id) }}"><i class="fa-regular fa-trash"></i></a>
                     @elseif($loan->status == LOAN_APPROVED)
                         <a href="{{ route('employee.loans.detail', $loan->id) }}" class="mr-2"><i class="fa-regular fa-eye"></i></a>
                     @endif
                 </td>
             </tr>
         @endforeach
     </tbody>
 </table>
 <div class="custom-pagination">
     {!! $loans->appends($_GET)->links() !!}
 </div>
