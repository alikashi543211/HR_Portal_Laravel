<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 offset-2">

                        <form method="post" action="{{url('admin/profile/update')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group offset-3">
                                    <label>Old Password</label>
                                    <input name="old_password" type="password" class="form-control" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group offset-3">
                                    <label>New Password</label>
                                    <input name="password" type="password" class="form-control" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group offset-3">
                                    <label>Confirm Password</label>
                                    <input name="password_confirmation" type="password" class="form-control" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <a href="{{url('admin/dashboard')}}"><button type="button" class="btn btn-secondary btn-round">{{__('default_label.back')}}</button></a>
                                    <button type="submit" class="btn btn-primary btn-round">{{__('default_label.update_btn')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>