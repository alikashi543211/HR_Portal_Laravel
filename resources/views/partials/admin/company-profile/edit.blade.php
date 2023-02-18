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

            <form method="post" action="{{url(config('data.admin.companyprofile.update'))}}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('company-profile.company_name')}}</label>
                                <input name="name" type="text" title="Enter Only Alphabets" class="form-control" placeholder="Enter Company Name" required value="{{  old('name', (!empty($data->name)? $data->name : '')) }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('company-profile.company_phone')}}</label>
                                <input name="phone" type="text" class="form-control" placeholder="Enter Phone Number" required value="{{  old('phone', (!empty($data->phone)? $data->phone : '')) }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('company-profile.company_address')}}</label>
                                <textarea name="address" class="form-control" required>{{ old('address', (!empty($data->address)? $data->address : '')) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group d-inline">
                                <label>{{__('company-profile.company_logo')}}</label><br>
                                <input name="logo" type="file" class="form-control logo" style="display: none" />
                                <button type="button" class="btn btn-info upload-button" style="margin-top: 0px;">Upload Logo</button>
                            </div>
                            <div class="d-inline ml-5 d-none" id="logo-preview">
                            </div>
                        </div>
                    </div>
                    <div class="row border-top pt-3 mt-1">
                        <div class="col-md-12">
                            <h6>{{__('company-profile.authorized_signaroty')}}</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('user.full_name')}}</label>
                                <input name="authorized_name" type="text" class="form-control" placeholder="Enter Full Name" required value="{{  old('authorized_name', (!empty($data->authorized_name)? $data->authorized_name : '')) }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('user.designation')}}</label>
                                <input name="authorized_designation" type="text" class="form-control" placeholder="Enter Designation" required value="{{  old('authorized_designation', (!empty($data->authorized_designation)? $data->authorized_designation : '')) }}" />
                            </div>
                        </div>
                    </div>
                    <div class="row border-top pt-3 mt-1">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="salary_details" id="show-salary-detail" value="1" {{!empty($data->cheque_bank_name) ? 'checked' : ''}} />
                                    <label class="custom-control-label h3 pt-1" for="show-salary-detail">{{__('company-profile.show_salary_by_bank')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row border-top pt-3 mt-1 {{!empty($data->cheque_bank_name) ? '' : 'd-none'}}" id="salary-details-form">
                        <div class="col-md-12">
                            <h6>{{__('company-profile.salary_by_bank')}}</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('company-profile.cheque_bank_name')}}</label>
                                <input name="cheque_bank_name" type="text" class="form-control" placeholder="Enter Cheque Bank Name" value="{{  old('cheque_bank_name', (!empty($data->cheque_bank_name)? $data->cheque_bank_name : '')) }}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p><b>{{__('company-profile.respective_bank_person')}}</b></p>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('company-profile.bank_name')}}</label>
                                <input name="respective_bank_name" type="text" title="Enter Only Alphabets" class="form-control" placeholder="Enter Bank Name" value="{{  old('respective_bank_name', (!empty($data->respective_bank_name)? $data->respective_bank_name : '')) }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('company-profile.title')}}</label>
                                <select name="respective_title" id="" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Mr." @if( !empty($data->respective_title) && $data->respective_title == 'Mr.') selected @endif >Mr.</option>
                                    <option value="Ms." @if( !empty($data->respective_title) && $data->respective_title == 'Ms.') selected @endif >Ms.</option>
                                    <option value="Mrs." @if( !empty($data->respective_title) && $data->respective_title == 'Mrs.') selected @endif >Mrs.</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('user.first_name')}}</label>
                                <input name="respective_first_name" type="text" title="Enter Only Alphabets" class="form-control" placeholder="Enter First Name" value="{{  old('respective_first_name', (!empty($data->respective_first_name)? $data->respective_first_name : '')) }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('user.last_name')}}</label>
                                <input name="respective_last_name" type="text" title="Enter Only Alphabets" class="form-control" placeholder="Enter Last Name" value="{{  old('respective_last_name', (!empty($data->respective_last_name)? $data->respective_last_name : '')) }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('user.designation')}}</label>
                                <input name="respective_designation" type="text" class="form-control" placeholder="Enter Designation" value="{{  old('respective_designation', (!empty($data->respective_designation)? $data->respective_designation : '')) }}" />
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('company-profile.address_1')}}</label>
                                <textarea name="respective_address_1" class="form-control">{{ old('respective_address_1', (!empty($data->respective_address_1)? $data->respective_address_1 : '')) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('company-profile.address_2')}}</label>
                                <textarea name="respective_address_2" class="form-control">{{ old('respective_address_2', (!empty($data->respective_address_2)? $data->respective_address_2 : '')) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="update ml-auto mr-auto">
                            <a href="{{url(config('data.admin.companyprofile.detail'))}}"><button type="button" class="btn btn-secondary btn-round">{{__('default_label.back')}}</button></a>
                            <button type="submit" class="btn btn-primary btn-round">Update</button>
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

        $(".logo").on('change', function(e) {
            $('#logo-preview').removeClass('d-none');
            if (e.target.files[0].type.includes('image')) {
                $("#logo-preview").html(`
                                        <img src="` + URL.createObjectURL(e.target.files[0]) + `" alt="img" width="80px">
                                    `);
            }
        });

        $("#show-salary-detail").on('click', function() {
            if ($(this).is(':checked')) {
                $("#salary-details-form").removeClass('d-none')
                $("#salary-details-form").find('input').prop('required', true);
            } else {
                $("#salary-details-form").addClass('d-none')
                $("#salary-details-form").find('input').prop('required', false);
            }
        });
    });
</script>
@endsection