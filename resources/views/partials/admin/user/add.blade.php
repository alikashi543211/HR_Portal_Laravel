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

            <form method="post" action="{{ url(config('data.admin.user.save')) }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.role') }}</label> <span class="text-danger">*</span>
                                <select name="role_id" id="" class="form-control" required>
                                    <option value="">Please Select</option>
                                    @foreach ($data['roles'] as $key => $role)
                                        @if ($role['id'] > Auth::User()->role_id)
                                            <option value="{{ $role['id'] }}" @if (old('role_id') == $role['id']) selected @endif>{{ $role['title'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.status') }}</label><span class="text-danger ml-1">*</span>
                                <select name="status" id="userStatus" class="form-control" required>
                                    <option value="">Please Select</option>
                                    <option value="Permanent" @if (old('status') == 'Permanent') selected @endif>Permanent</option>
                                    <option value="1M Probation" @if (old('status') == '1M Probation') selected @endif>1M Probation</option>
                                    <option value="2M Probation" @if (old('status') == '2M Probation') selected @endif>2M Probation</option>
                                    <option value="3M Probation" @if (old('status') == '3M Probation') selected @endif>3M Probation</option>
                                    <option value="Terminate" @if (old('status') == 'Terminate') selected @endif>Terminate</option>
                                    <option value="Other" @if (old('status') == 'Other') selected @endif>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.email') }}</label><span class="text-danger ml-1">*</span>
                                <input name="email" type="email" class="form-control" placeholder="Enter Email" required value="{{ !empty(old('email')) ? old('email') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.first_name') }}</label><span class="text-danger ml-1">*</span>
                                <input name="first_name" type="text" title="Enter Only Alphabets" class="form-control" placeholder="Enter First Name" required value="{{ !empty(old('first_name')) ? old('first_name') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.last_name') }}</label><span class="text-danger ml-1">*</span>
                                <input name="last_name" type="text" title="Enter Only Alphabets" class="form-control" placeholder="Enter Last Name" required value="{{ !empty(old('last_name')) ? old('last_name') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.gender') }}</label><span class="text-danger ml-1">*</span>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" required value="Male" name="gender" @if (old('gender') == 'Male') checked @endif>Male
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" required value="Female" name="gender" @if (old('gender') == 'Female') checked @endif>Female
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.phone_no') }}</label><span class="text-danger ml-1">*</span>
                                <input name="phone_number" type="text" class="form-control" placeholder="Enter Phone Number" required value="{{ !empty(old('phone_number')) ? old('phone_number') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.cnic') }}</label><span class="text-danger ml-1">*</span>
                                <input name="cnic" type="text" class="form-control" placeholder="Enter CNIC" required value="{{ !empty(old('cnic')) ? old('cnic') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.personal_email') }}</label><span class="text-danger ml-1">*</span>
                                <input name="personal_email" type="email" class="form-control" placeholder="Enter Personal Email" required value="{{ !empty(old('personal_email')) ? old('personal_email') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.doj') }}</label><span class="text-danger ml-1">*</span>
                                <input name="doj" type="date" class="form-control" placeholder="Enter Joining Date" required value="{{ !empty(old('doj')) ? old('doj') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.designation') }}</label><span class="text-danger ml-1">*</span>
                                <input name="designation" type="text" class="form-control" placeholder="Enter Designation" required value="{{ !empty(old('designation')) ? old('designation') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.dob') }}</label><span class="text-danger ml-1">*</span>
                                <input name="dob" type="date" class="form-control" placeholder="Enter Date Of Birth" required value="{{ !empty(old('dob')) ? old('dob') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.dop') }}</label>
                                <input id="dop" name="dop" type="date" class="form-control" placeholder="Enter Joining Date" value="{{ !empty(old('dop')) ? old('dop') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.employee_id') }}</label><span class="text-danger ml-1">*</span>
                                <input name="employee_id" type="text" class="form-control" placeholder="Enter Employee ID" required value="{{ !empty(old('employee_id')) ? old('employee_id') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.finger_print_id') }} </label>
                                <input name="finger_print_id" type="text" class="form-control" placeholder="Enter Finger Print ID" value="{{ !empty(old('finger_print_id')) ? old('finger_print_id') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.nationality') }}</label><span class="text-danger ml-1">*</span>
                                <input name="nationality" type="text" class="form-control" placeholder="Enter Nationality" required value="{{ !empty(old('nationality')) ? old('nationality') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.picture') }} </label><br>
                                <input name="picture" type="file" class="form-control" style="display: none" />
                                <button class="btn btn-info upload-button" style="margin-top: 0px;">Upload Profile Picture</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.reporting_manager') }}</label>
                                <select name="manager_id" class="form-control select2">
                                    <option></option>
                                    @foreach ($data['users'] as $user)
                                        <option value="{{ $user->id }}" {{ old('manager_id') == $user->id ?? 'selected' }}>{{ $user->first_name . ' ' . $user->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.base_salary') }}</label><span class="text-danger ml-1">*</span>
                                <input name="base_salary" type="number" class="form-control" placeholder="Enter Gross Salary" required value="{{ !empty(old('base_salary')) ? old('base_salary') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.account_no') }}</label>
                                <input name="account_no" type="text" class="form-control" placeholder="Enter Bank Account No." value="{{ !empty(old('account_no')) ? old('account_no') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.emergency_contact_name') }} </label>
                                <input name="emergency_contact_name" type="text" class="form-control" placeholder="Enter Emergency Contact Name" value="{{ !empty(old('emergency_contact_name')) ? old('emergency_contact_name') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.emergency_contact_relation') }} </label>
                                <input name="emergency_contact_relation" type="text" class="form-control" placeholder="Enter Emergency Contact Relation" value="{{ !empty(old('emergency_contact_relation')) ? old('emergency_contact_relation') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.emergency_contact_number') }} </label>
                                <input name="emergency_contact_number" type="number" class="form-control" placeholder="Enter Emergency Contact Number" value="{{ !empty(old('emergency_contact_number')) ? old('emergency_contact_number') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.password') }} </label><span class="text-danger ml-1">*</span>
                                <input name="password" type="password" class="form-control" placeholder="Enter Password" required autocomplete="false" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.cpassword') }}</label><span class="text-danger ml-1">*</span>
                                <input name="password_confirmation" type="password" class="form-control" placeholder="Enter Confirm Password" required autocomplete="false" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.dot') }}</label>
                                <input id="dot" name="dot" type="date" class="form-control" placeholder="Enter Date Of Termination" value="{{ !empty(old('dot')) ? old('dot') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.govrt_tax') }}</label>
                                <input id="govrt_tax" name="govrt_tax" type="number" class="form-control" placeholder="Monthly Tax Amount" value="{{ !empty(old('govrt_tax')) ? old('govrt_tax') : '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('user.policy_select') }}</label><span class="text-danger ml-1">*</span>
                                <select name="policy_id" required class="form-control select3">
                                    <option></option>
                                    @foreach ($data['policies'] as $policy)
                                        <option value="{{ $policy->id }}" {{ old('policy_id') == $policy->id ? 'selected' : '' }}>{{ $policy->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="update ml-auto mr-auto">
                            <a href="{{ url(config('data.admin.user.listing')) }}"><button type="button" class="btn btn-secondary btn-round">{{ __('default_label.back') }}</button></a>
                            <button type="submit" class="btn btn-primary btn-round">{{ __('default_label.add') }}</button>
                        </div>
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
            $('.select3').select2({
                placeholder: 'Select Attendance Policy',
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
