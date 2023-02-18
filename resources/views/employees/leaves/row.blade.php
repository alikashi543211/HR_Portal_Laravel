  <table class="table table-responsive-md">
      <thead>
          <tr>
              <th class="width80"><strong>Sr.No</strong></th>
              <th><strong>Type</strong></th>
              <th><strong>Period Type</strong></th>
              <th><strong>From Date</strong></th>
              <th><strong>To Date</strong></th>
              <th><strong>Status</strong></th>
              <th><strong>Actions</strong></th>
          </tr>
      </thead>
      <tbody>
          @foreach ($leaves as $key => $leave)
              <tr>
                  <td><strong>{{ $key + $leaves->firstItem() }}</strong></td>
                  <td>{{ $leave->type == SICK_LEAVE ? 'Sick Leave' : 'Casual Leave' }}</td>
                  <td>
                      @if ($leave->period_type == FULL_DAY)
                          Full Day
                      @elseif ($leave->period_type == FIRST_HALF)
                          First Half
                      @elseif ($leave->period_type == SECOND_HALF)
                          Second Half
                      @endif
                  </td>
                  <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('Y-m-d') }}</td>
                  <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('Y-m-d') }}</td>
                  <td>
                      @if ($leave->status == 0)
                          <div class="badge pending">
                              Pending
                          </div>
                      @elseif ($leave->status == 1)
                          <div class="badge approved">
                              Approved
                          </div>
                      @elseif ($leave->status == 2)
                          <div class="badge rejected">
                              Rejected
                          </div>
                      @endif
                  </td>
                  <td class="font-awosome">

                      @if ($leave->status == 0)
                          <a href="{{ route('employee.leaves.edit', $leave->id) }}" class="mr-2"><i class="fa-regular fa-pencil"></i></a>
                          <a class="deleteBtn" data-toggle="modal" data-target="#basicModal" data-id="{{ $leave->id }}" data-delete-url="{{ route('employee.leaves.delete', $leave->id) }}"><i class="fa-regular fa-trash"></i></a>
                      @else
                          <a href="{{ route('employee.leaves.details', $leave->id) }}" class="mr-2"><i class="fa-regular fa-eye"></i></a>
                      @endif
                  </td>
              </tr>
          @endforeach

      </tbody>
  </table>
  <div class="custom-pagination">
      {!! $leaves->appends($_GET)->links() !!}
  </div>
