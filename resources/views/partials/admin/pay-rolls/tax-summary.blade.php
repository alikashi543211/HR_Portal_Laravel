@php
    $period = getPeriod();
    $numbers = [1, 2, 3, 4];
@endphp
<form action="{{ url('admin/pay-rolls/summary') }}" method="get" id="dateForm">
    <div class="row">
        <div class="col-md-4">
            <label for="">From</label>
            <input type="text" autocomplete="off" name="from_date" class="form-control datepicker" value="{{ request('from_date') ? request('from_date') : '' }}" placeholder="From Month">
            <input type="hidden" name="search" value="{{ request('search') }}">
        </div>
        <div class="col-md-4 ">
            <label for="">To</label>
            <input type="text" autocomplete="off" name="to_date" class="form-control datepicker" value="{{ request('to_date') ? request('to_date') : '' }}" placeholder="To Month">
            <input type="hidden" name="search" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <label for="">Select Users</label>
            <select style="width: 100%" class="form-control js-example-basic-multiple" id="employeeSelect" multiple="multiple" name="user_id[]">
                @foreach ($data['users'] as $key => $user)
                    <option value="{{ $user->id }}" @if (request('user_id') && in_array($user->id, request('user_id'))) selected @endif>{{ $user->full_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ URL(Request::path()) }}">
                <button type="button" class="btn btn-info">{{ __('default_label.clear_filter') }} </button>
            </a>
            <button type="submit" class="btn btn-success">{{ __('default_label.submit_filter') }} </button>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-md-9">
        <div class="d-flex justify-content-start align-items-end mb-2">
            <h5>Total Tax : {{ $data['total_tax'] }}</h5>

            {{-- @foreach ($numbers as $item)
                <span class="mr-4 text-center">
                    <i class="fa {{ getLeaveIcon($item) }}" title="{{ getLeaveTitleText($item) }}" aria-hidden="true"></i>

                    <br />{{ getLeaveTitleText($item) }}</span>
            @endforeach --}}
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-white" style="background-color: #51bcda;">
            <th> Sr# </th>
            <th> Name </th>
            @foreach ($data['months'] as $key => $month)
                <th>{{ $month->format('M, Y') }} </th>
            @endforeach
        </thead>
        <tbody>
            @foreach ($data['users'] as $index => $res)
                <tr style="cursor: pointer">
                    <td> {{ $index + 1 }}</td>
                    <td><a href="javascript:void(0)" style="color: black;font-weight:bolder;text-decoration: underline"> {{ $res->last_name }}</a></td>
                    @foreach ($data['months'] as $key => $month)
                        <td class="typo">
                            {{-- <i class="fa {{ getUserLeaveTypeStatus(\Carbon\Carbon::parse($date)->format('Y-m-d'), $res->id) }}" title="{{ getLeaveTitleText($item) }}" style="font-size: 20px" aria-hidden="true"></i>
                            <br> <small>{{ getPeriodType(\Carbon\Carbon::parse($date)->format('Y-m-d'), $res->id) }}</small> --}}
                            {{ getUserTaxPaid($month->format('F, Y'), $res->id) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>

    </table>
    {{-- {{ $data['user']->appends(request()->query())->links() }} --}}
</div>


@include('partials.admin.attendances.modals')

@section('footer_js')
    @parent
    @include('partials.admin.attendances.listing-js');
    <script>
        $(document).ready(function() {

            $('.view-leave-popup').click(function(e) {
                e.preventDefault();
                $('#leaveModal').modal('show');
                $('#UserName').html($(this).data('name') + ' (' + $(this).data('date') + ')');
                $('#leaveUserName').val($(this).data('name') + ' (' + $(this).data('date') + ')');
                $('#leaveUserId').val($(this).data('id'));
                $('#leaveDate').val($(this).data('date'));
                currentUserId = $(this).data('id');
                showDate.html($(this).data('date'));
            });

            @if (session('leave_error'))
                $('#leaveModal').modal('show');
            @endif

            $('.delete-exception-popup').click(function(e) {
                var deleteExceptionUrl = " {{ url(config('data.admin.attendance.delete-exception')) }}" + "/" + $(this).data('id') + "?date=" + $(this).data('date');
                $('.confirmation-yes').attr("href", deleteExceptionUrl);
                $('#confirmationModal').modal('show');
            });
            $('.view-leave-details-popup').click(function(e) {
                $('#leaveDetails').modal('show');
                $('#leave-popup-date').html($(this).data('date'));
                $('#leave-popup-name').html($(this).data('name'));
                $('#leave-popup-period').val($(this).data('leave-period-type'));
                $('#leave-popup-type').val($(this).data('leave-type'));
                $('#leave-popup-adjust').val($(this).data('leave-adjust'));
            })
        });

        // Employee Dropdown Selection
        $(document).ready(function() {

            $(function() {
                $('.datepicker').datepicker({
                    format: 'MM, yyyy',
                    autoclose: true,
                    startView: "months",
                    minViewMode: "months"
                });
            });
            $('#employeeSelect').select2({
                placeholder: 'Select Users',
                allowClear: true
            });
        });
    </script>
@endsection
