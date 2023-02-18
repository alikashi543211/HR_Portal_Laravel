<div class="table-responsive">
    <table class="table table-striped">
        <thead class=" text-primary">
            <th> Employee id </th>
            <th> Name </th>
            <th> FP id </th>
            <th> Official Email </th>
            <th> Date Of Joining </th>
            <th> Status </th>
            <th> Action </th>
        </thead>
        <tbody>
            @foreach($data as $index => $info)
            <tr>
                <td> {{$info->employee_id}} </td>
                <td> <a href="{{url(config('data.admin.user.details').'/'.$info->id)}}">{{ isset($info->full_name) ? $info->full_name : ''  }}</a>
                </td>
                <td> {{ $info->finger_print_id }} </td>
                <td> {{ isset($info->email) ? $info->email : ''  }} </td>
                <td> {{ isset($info->doj) ? date('d M, Y', strtotime($info->doj)) : ''  }} </td>
                <td> {{ $info->status }} </td>
                <td>
                    @if(checkPermission(ATTENDANCES, WRITE) && $info->status != 'Terminate')
                        <a href="#!" data-url="{{url(config('data.admin.attendance.save').'/'.$info->id)}}" class="mark-attendance-popup btn btn-sm btn-success" data-id="{{$info->id}}" title="Mark Attendence">Mark Attendance</a>
                    @endif
                    @if(checkPermission(USERS, WRITE))
                        <a href="#!" data-url="{{url(config('data.admin.user.delete').'/'.$info->id)}}" class="confirmation-popup btn btn-sm btn-danger" title="Delete">Delete</a>
                        <a href="{{url(config('data.admin.user.edit').'/'.$info->id)}}" title="Edit" class="btn btn-sm btn-info">Edit</a>
                        @if($info->status != 'Terminate')
                            <a href="{{url(config('data.admin.user.sendJoinMail').'/'.$info->id)}}" title="Join Email" class="btn btn-sm btn-info">{{ $info->verification_code ? 'Resend Email' : 'Join Email' }}</a>
                        @endif
                    @endif
                </td>
            </tr> @endforeach </tbody>
    </table>
    {{ $data->links() }}
</div>

<div class="modal fade markAttendance" id="markAttendance" tabindex="-1" role="dialog" aria-labelledby="markAttendanceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">{{__('attendance.mark_attendance') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form method="post" action="{{url(config('data.admin.attendance.save'))}}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="user_id" id="user_id">
                                <div class="row">
                                    <div class="col-md-8 offset-2">
                                        <div class="form-group">
                                            <label>{{__('attendance.type')}}</label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" required value="Check In" name="type" @if(old('type')=='Check In' ) checked @endif @if(empty(old('type'))) checked @endif>{{__('attendance.check_in')}}
                                            </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" required value="Check Out" name="type" @if(old('type')=='Check Out' ) checked @endif>{{__('attendance.check_out')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 offset-2">
                                        <div class="form-group">
                                            <label>{{__('attendance.time')}}</label>
                                            <input name="action_time" type="datetime" autocomplete="off" id="datetimepicker1" class="form-control" required value="{{ !empty(old('time')) ? old('time') : date('Y-m-d h:i') }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="update mx-auto">
                                        <a href="#!" class="col-md-6 confirmation-no">
                                            <button type="button" class="btn btn-success ">Cancel</button>
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-round">{{__('attendance.mark_attendance')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@section('footer_js')
@parent
<script>
    $(document).ready(function () {
        $(function () {
            $('#datetimepicker1').datetimepicker({
                autoClose: true
            });
        });
        $('.mark-attendance-popup').click(function (e) {
            e.preventDefault();
            $('#markAttendance').modal('show');
            $('.confirmation-yes').attr("href", $(this).attr('data-url'));
            $('#user_id').val($(this).data('id'));
        });
        $('.mark-attendance-no').click(function (e) {
            e.preventDefault();
            $('#markAttendance').modal('hide');
        });
    });

</script>
@endsection
