<style>
    .card-user .image img {
        border-radius: 0px;
    }

</style>

@php
$permissionId = App\Permission::whereCode(Request::segment(2))->first()->id;
@endphp

<div class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-user">
                <div class="image">
                    <img src="{{asset('assets/image/ds-profile.png')}}" class="mx-auto d-block mt-3 round-0" alt="...">
                </div>
                <div class="card-body">
                    <div class="author">
                        @if(!empty($data->picture))
                        <img class="avatar border-gray" src="{{asset('uploads/employee/'.$data->picture)}}" alt="...">
                        @else
                        <img class="avatar border-gray" src="{{asset('assets/image/default-avatar.png')}}" alt="...">
                        @endif
                        <h5 class="title">{{$data->full_name}}</h5>
                        <p>
                            @if(!empty($data->designation))
                            @if(!empty($data->role))
                            <span class="badge badge-success h6 mr-3">{{ $data->role->title }}</span>
                            @endif
                            <span class="badge badge-primary h6">{{ $data->designation }}</span>
                            </br>
                            </br>
                            @endif
                            {{ $data->email }}
                            @if(!empty($data->phone_number))
                            <br />
                            {{ $data->phone_number }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <div class="button-container">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-4 ml-auto">
                                <h5 class="font-weight-bold"> ID <br><small> {{ $data->employee_id }} </small></h5>
                            </div>
                            <div class="col-lg-4 col-md-4 col-4 ml-auto mr-auto">
                                <h5 class="font-weight-bold">FPID<br><small> {{ $data->finger_print_id ? $data->finger_print_id : '-'  }} </small></h5>
                            </div>
                            <div class="col-lg-4 col-md-4 col-4 mr-auto">
                                <h5 class="font-weight-bold">{{ __('user.gender')}} <br><small> {{ $data->gender }} </small></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-user">
                <div class="card-header">
                    <div class="row border-bottom">
                        <div class="col-md-6">
                            <h5 class="card-title">{{ __('default_label.details') }}</h5>
                        </div>
                        @if(checkPermission(USERS, WRITE))
                        <div class="col-md-6">
                            <a href="{{url(config('data.admin.user.edit').'/'.$data->id)}}"> <button type="button" class="btn btn-primary pull-right" title="{{__('default_label.edit')}}"> {{__('default_label.edit')}}</button></a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col-md-6">
                                <b>{{ __('user.dob') }}</b>
                            </div>
                            <div class="col-md-6 border-left">
                                <p> {{ date('d M', strtotime($data->dob)) }} </p>
                            </div>
                            <div class="col-md-6">
                                <b>{{ __('user.doj') }}</b>
                            </div>
                            <div class="col-md-6 border-left">
                                <p> {{ date('d M, Y', strtotime($data->doj)) }} </p>
                            </div>
                            <div class="col-md-6">
                                <b>{{ __('user.dop') }}</b>
                            </div>
                            <div class="col-md-6 border-left">
                                <p>
                                    {{ !empty($data->dop) ? date('d M, Y', strtotime($data->dop)) : "-" }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <b>{{ __('user.reporting_manager') }}</b>
                            </div>
                            <div class="col-md-6 border-left">
                                <p> {{ $data->manager ? $data->manager->first_name.' '.$user->manager->last_name : "-" }} </p>
                            </div>
                            <div class="col-md-6">
                                <b>{{ __('user.nationality') }}</b>
                            </div>
                            <div class="col-md-6 border-left">
                                <p> {{ $data->nationality }} </p>
                            </div>
                            <div class="col-md-6">
                                <b>{{ __('leave.leave_quota') }}</b>
                            </div>
                            <div class="col-md-6 border-left">
                                @if(!empty($data->leaveQuota))
                                <p>
                                    <span class="badge badge-secondary"> {{ __('leave.sick_used') }}: {{ $data->leaveQuota->used_sick_leaves }} </span>
                                    <span class="badge badge-danger"> {{ __('leave.sick_remaining') }}: {{ $data->leaveQuota->remaining_sick_leaves }} </span>
                                    <span class="badge badge-warning"> {{ __('leave.casual_used') }}: {{ $data->leaveQuota->used_casual_leaves }} </span>
                                    <span class="badge badge-info"> {{ __('leave.casual_remaining') }}: {{ $data->leaveQuota->remaining_casual_leaves }} </span>
                                </p>
                                @else
                                - <br /><br />
                                @endif
                            </div>
                            <div class="col-md-6">
                                <b>{{ __('user.emergency_contact_details') }}</b>
                            </div>
                            <div class="col-md-6 border-left">
                                <p class="small">{{ str_replace("(Optional)", "", __('user.emergency_contact_name')) }} : {{$data->emergency_contact_name ? $data->emergency_contact_name : "N/A"}}</p>
                                <p class="small">{{ str_replace("(Optional)", "", __('user.emergency_contact_relation')) }} : {{$data->emergency_contact_relation ? $data->emergency_contact_relation : "N/A"}}</p>
                                <p class="small">{{ str_replace("(Optional)", "", __('user.emergency_contact_number')) }} : {{$data->emergency_contact_number ? $data->emergency_contact_number : "N/A"}}</p>
                            </div>
                            @if(!empty($data->dot))
                            <div class="col-md-6">
                                <b>{{ __('user.dot') }}</b>
                            </div>
                            <div class="col-md-6 border-left">
                                <p> {{ date('d M, Y', strtotime($data->dot)) }} </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-user">
                <div class="card-header">
                    <div class="row border-bottom">
                        <div class="col-md-6">
                            <h5 class="card-title">{{ __('default_label.user_late_policies') }}</h5>
                        </div>
                        @if(checkPermission(USERS, WRITE))
                        <div class="col-md-6">
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class=" text-primary">
                                        <th> Name </th>
                                        <th> Start Date </th>
                                        <th> End Date </th>
                                        <th> Start Time </th>
                                        <th> End Time </th>
                                        <th> Deduction starts from </th>
                                        <th> Action </th>
                                    </thead>
                                    <tbody>
                                        @foreach($data->latePolicies as $index => $info)
                                        <tr>
                                            <td> {{$info->no_policy ? 'No Policy' : $info->name}} </td>
                                            <td> {{$info->start_date}}</td>
                                            <td> {{$info->end_date}}</td>
                                            <td> {{$info->no_policy ? '--' : $info->start_time}}</td>
                                            <td> {{$info->no_policy ? '--' : $info->end_time}}</td>
                                            <td> {{$info->no_policy ? '--' : $info->relax_time}}</td>
                                            <td>
                                                @if(checkPermission(LATE_POLICY, WRITE))
                                                <a data-id="{{$info->id}}" href="javascript::void(0)" title="Change Policy" class=" change-policy-button btn btn-sm btn-info">Change Policy</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade changePolicy" id="changePolicy" tabindex="-1" role="dialog" aria-labelledby="changePolicyLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Change Policy
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="post" action="{{url(config('data.admin.user.changePolicy'))}}">
                    @csrf
                    <input type="hidden" name="user_policy_id" id="userPolicyId">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <select name="policy_name" id="" class="form-control">
                                @foreach($data['late_policies'] AS $policy)
                                <option value="{{$policy}}">{{$policy}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(checkPermission(LATE_POLICY, WRITE))
                    <div class="row">
                        <div class="update mx-auto">
                            <button type="submit" class="btn btn-primary btn-round">{{__('default_label.update_btn')}}</button>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

@section('footer_js')

@parent
<script>
    $(document).ready(function () {

        $('.change-policy-button').click(function (e) {
            e.preventDefault();
            $('#changePolicy').modal('show');
            $('#userPolicyId').val($(this).data('id'));
        });

    });

</script>
@endsection
