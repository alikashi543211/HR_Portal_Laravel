@section('header')
    @parent
    <style>
        .select2-selection__rendered {
            margin-top: 5px !important;
        }
    </style>
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>

            <form method="post" action="{{ url(config('data.admin.user.update')) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $data['data']->id }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.role') }}</label> <span class="text-danger ml-1">*</span>
                                <select name="role_id" id="" class="form-control" required>
                                    <option value="">Please Select</option>
                                    @foreach ($data['roles'] as $key => $role)
                                        @if ($role['id'] > SUPER_ADMIN)
                                            <option value="{{ $role['id'] }}" @if ($data['data']->role_id == $role['id']) selected @endif @if ($role['id'] < Auth::User()->role_id) disabled @endif> {{ $role['title'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.status') }}</label> <span class="text-danger ">*</span>
                                <select name="status" id="userStatus" class="form-control" required>
                                    <option value="">Please Select</option>
                                    <option value="Permanent" @if ($data['data']->status == 'Permanent') selected @endif>Permanent</option>
                                    <option value="1M Probation" @if ($data['data']->status == '1M Probation') selected @endif>1M Probation</option>
                                    <option value="2M Probation" @if ($data['data']->status == '2M Probation') selected @endif>2M Probation</option>
                                    <option value="3M Probation" @if ($data['data']->status == '3M Probation') selected @endif>3M Probation</option>
                                    <option value="Terminate" @if ($data['data']->status == 'Terminate') selected @endif>Terminate</option>
                                    <option value="Other" @if ($data['data']->status == 'Other') selected @endif>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{ __('user.email') }}</label><span class="text-danger ml-1">*</span>
                                <input name="email" type="email" class="form-control" placeholder="Enter Email" required value="{{ $data['data']->email }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.first_name') }}</label><span class="text-danger ml-1">*</span>
                                <input name="first_name" type="text" title="Enter Only Alphabets" class="form-control" placeholder="Enter First Name" required value="{{ $data['data']->first_name }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{ __('user.last_name') }}</label><span class="text-danger ml-1">*</span>
                                <input name="last_name" type="text" title="Enter Only Alphabets" class="form-control" placeholder="Enter Last Name" required value="{{ $data['data']->last_name }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.gender') }}</label><span class="text-danger ml-1">*</span>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="Male" name="gender" @if ($data['data']->gender == 'Male') checked @endif>Male
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="Female" name="gender" @if ($data['data']->gender == 'Female') checked @endif>Female
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{ __('user.phone_no') }}</label><span class="text-danger ml-1">*</span>
                                <input name="phone_number" type="text" class="form-control" placeholder="Enter Phone Number" required value="{{ $data['data']->phone_number }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.cnic') }}</label><span class="text-danger ml-1">*</span>
                                <input name="cnic" type="text" class="form-control" placeholder="Enter CNIC" required value="{{ $data['data']->cnic }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{ __('user.personal_email') }}</label><span class="text-danger ml-1">*</span>
                                <input name="personal_email" type="email" class="form-control" placeholder="Enter Personal Email" required value="{{ $data['data']->personal_email }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.doj') }}</label><span class="text-danger ml-1">*</span>
                                <input name="doj" type="date" class="form-control" placeholder="Enter Joining Date" required value="{{ $data['data']->doj }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{ __('user.designation') }}</label>
                                <input name="designation" type="text" class="form-control" placeholder="Enter Personal Email" required value="{{ $data['data']->designation }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.dob') }}</label><span class="text-danger ml-1">*</span>
                                <input name="dob" type="date" class="form-control" placeholder="Enter Date Of Birdth" required value="{{ $data['data']->dob }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{ __('user.dop') }}</label>
                                <input @if ($data['data']->status == 'Permanent') required @endif id="dop" name="dop" type="date" class="form-control" placeholder="Enter Permanent Date" value="{{ $data['data']->dop }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.employee_id') }}</label><span class="text-danger ml-1">*</span>
                                <input name="employee_id" type="text" class="form-control" placeholder="Enter Employee ID" required value="{{ $data['data']->employee_id }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{ __('user.finger_print_id') }}</label><span class="text-danger ml-1">*</span>
                                <input name="finger_print_id" type="text" class="form-control" placeholder="Enter Finger Print ID" value="{{ $data['data']->finger_print_id }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.nationality') }}</label><span class="text-danger ml-1">*</span>
                                <input name="nationality" type="text" class="form-control" placeholder="Enter Nationality" required value="{{ $data['data']->nationality }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{ __('user.picture') }}</label><br>
                                <input name="picture" type="file" class="form-control" style="display: none" />
                                <button type="button" class="btn btn-info upload-button" style="margin-top: 0px;">Upload Profile Picture</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.reporting_manager') }}</label>
                                <select name="manager_id" class="form-control select2">
                                    <option></option>
                                    @foreach ($data['users'] as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == $data['data']->manager_id ? 'selected' : '' }}>{{ $user->first_name . ' ' . $user->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.base_salary') }}</label><span class="text-danger ml-1">*</span>
                                <input name="base_salary" type="number" class="form-control" placeholder="Enter Base Salary" required value="{{ $data['data']->base_salary }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.account_no') }}</label>
                                <input name="account_no" type="text" class="form-control" placeholder="Enter Bank Account No." value="{{ $data['data']->account_no }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{ __('user.emergency_contact_name') }}</label>
                                <input name="emergency_contact_name" type="text" class="form-control" placeholder="Enter Emergency Contact Name" value="{{ $data['data']->emergency_contact_name }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.emergency_contact_relation') }}</label>
                                <input name="emergency_contact_relation" type="text" class="form-control" placeholder="Enter Emergency Contact Relation" value="{{ $data['data']->emergency_contact_relation }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{ __('user.emergency_contact_number') }}</label>
                                <input name="emergency_contact_number" type="number" class="form-control" placeholder="Enter Emergency Contact Number" value="{{ $data['data']->emergency_contact_number }}" />
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">

                                <label>{{ __('user.password') }} (Optional)</label><span class="text-danger ml-1">*</span>
                                <input name="password" type="password" class="form-control" placeholder="Enter Password" />
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label>{{ __('user.cpassword') }} (Optional)</label><span class="text-danger ml-1">*</span>
                                <input name="password_confirmation" type="password" class="form-control" placeholder="Enter Confirm Password" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>{{ __('user.dot') }}</label>
                                <input @if ($data['data']->status == 'Terminate') required @endif id="dot" name="dot" type="date" class="form-control" placeholder="Enter Date Of Termination" value="{{ $data['data']->dot }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.govrt_tax') }}</label>
                                <input id="govrt_tax" name="govrt_tax" type="number" class="form-control" placeholder="Monthly Tax Amount" value="{{ $data['data']->govrt_tax }}" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="update ml-auto mr-auto">
                            <a href="{{ url(config('data.admin.user.listing')) }}"><button type="button" class="btn btn-secondary btn-round">{{ __('default_label.back') }}</button></a>
                            <button type="submit" class="btn btn-primary btn-round">{{ __('default_label.update_btn') }}</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

@section('footer_js')
    @parent
    <script>
        $(document).ready(function() {

            $('.upload-button').click(function(e) {
                e.preventDefault();
                $('input[type=file]').click();

            });
            $('.select2').select2({
                placeholder: 'Select Reporting Manager',
                allowClear: true
            });

            $('#userStatus').on('change', function(e) {
                var selectedOptionText = $(this).val();
                $('#dop, #dot').removeAttr("required");
                if (selectedOptionText == 'Permanent') {
                    $('#dop').attr('required', true);
                } else if (selectedOptionText == 'Terminate') {
                    $('#dot').attr('required', true);
                }
            });
        });
    </script>
@endsection
