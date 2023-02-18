<style>
    .card-user .image img {
        border-radius: 0px;
    }
</style>

<div class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-user">
                <div class="image">
                    <img src="{{asset('assets/image/ds-profile.png')}}" class="mx-auto d-block mt-3 round-0" alt="...">
                </div>
                <div class="card-body">
                    <div class="author">
                        @if(!empty($data->logo))
                        <img class="avatar border-gray" src="{{asset('uploads/logo/'.$data->logo)}}" alt="...">
                        @else
                        <img class="avatar border-gray" src="{{asset('assets/image/default-avatar.png')}}" alt="...">
                        @endif
                        <h5 class="title">{{!empty($data->name) ? $data->name : ''}}</h5>
                        <p class="description">
                            {{ !empty($data->address) ? $data->address : ''  }}
                        </p>
                        <p>
                            {{ !empty($data->email) ? $data->email : '' }}
                            @if(!empty($data->phone))
                            <br />
                            {{ $data->phone }}
                            @endif
                        </p>
                    </div>
                </div>
                <!-- <div class="card-footer">
                    <hr>
                    <div class="button-container">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-4 ml-auto">
                                <h5 class="font-weight-bold"> ID <br><small> </small></h5>
                            </div>
                            <div class="col-lg-4 col-md-4 col-4 ml-auto mr-auto">
                                <h5 class="font-weight-bold">FPID<br><small> </small></h5>
                            </div>
                            <div class="col-lg-4 col-md-4 col-4 mr-auto">
                                <h5 class="font-weight-bold">{{ __('user.gender')}} <br><small> </small></h5>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-user">
                <div class="card-header">
                    <div class="row border-bottom">
                        <div class="col-md-6">
                            <h5 class="card-title">{{ __('default_label.details') }}</h5>
                        </div>
                        @if(Auth::user()->role_id == 1)
                        <div class="col-md-6">
                            <a href="{{url(config('data.admin.companyprofile.edit'))}}"> <button type="button" class="btn btn-primary pull-right" title="{{__('default_label.edit')}}"> {{__('default_label.edit')}}</button></a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col-md-6 p-2">
                                <b>{{ __('company-profile.authorized_signaroty') }}</b>
                            </div>
                            <div class="col-md-6 border-left border-bottom p-2">
                                <p class="small">{{ __('company-profile.authorized_name') }} : {{!empty($data->authorized_name) ? $data->authorized_name : "N/A"}}</p>
                                <p class="small">{{ __('company-profile.authorized_designation') }} : {{!empty($data->authorized_designation) ? $data->authorized_designation : "N/A"}}</p>
                            </div>
                            @if(!empty($data->cheque_bank_name))
                            <div class="col-md-6 p-2">
                                <b>{{ __('company-profile.salary_by_bank') }}</b>
                            </div>
                            <div class="col-md-6 border-left border-bottom p-2">
                                <p class="small">{{ __('company-profile.cheque_bank_name') }} : {{!empty($data->cheque_bank_name) ? $data->cheque_bank_name : "N/A"}}</p>
                            </div>
                            <div class="col-md-6 p-2">
                                <b>{{ __('company-profile.respective_bank_person') }}</b>
                            </div>
                            <div class="col-md-6 border-left p-2">
                                <p class="small">{{ __('company-profile.cheque_bank_name') }} : {{!empty($data->cheque_bank_name) ? $data->cheque_bank_name : "N/A"}}</p>
                                <p class="small">{{ __('company-profile.title') }} : {{!empty($data->respective_title) ? $data->respective_title : "N/A"}}</p>
                                <p class="small">{{ __('user.first_name') }} : {{!empty($data->respective_first_name) ? $data->respective_first_name : "N/A"}}</p>
                                <p class="small">{{ __('user.last_name') }} : {{!empty($data->respective_last_name) ? $data->respective_last_name : "N/A"}}</p>
                                <p class="small">{{ __('user.designation') }} : {{!empty($data->respective_designation) ? $data->respective_designation : "N/A"}}</p>
                                <p class="small">{{ __('company-profile.bank_name') }} : {{!empty($data->respective_bank_name) ? $data->respective_bank_name : "N/A"}}</p>
                                <p class="small">{{ __('company-profile.address_1') }} : {{!empty($data->respective_address_1) ? $data->respective_address_1 : "N/A"}}</p>
                                <p class="small">{{ __('company-profile.address_2') }} : {{!empty($data->respective_address_2) ? $data->respective_address_2 : "N/A"}}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@section('footer_js')

@parent
<script>
    $(document).ready(function() {

    });
</script>
@endsection