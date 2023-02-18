<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 offset-2">

                        <form method="post" action="{{url(config('data.admin.allowance.save'))}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group offset-3">
                                    <label>Name</label>
                                    <input name="name" type="text" class="form-control" required value="{{old('name')}}"  />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group offset-3">
                                    <label>Type</label>
                                    <select name="type" class="form-control" id="">
                                        <option value="{{ALLOWANCES_FIXED}}" @if(old('type') == ALLOWANCES_FIXED) selected @endif >Fixed</option>
                                        <option value="{{ALLOWANCES_PERCENTAGE}}" @if(old('type') == ALLOWANCES_PERCENTAGE) selected @endif >Percentage</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group offset-3">
                                    <label>Value (amount or percentage)</label>
                                    <input name="value" type="number" class="form-control" required  value="{{old('value')}}"  />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group offset-3">
                                    <label>For All Users</label>
                                    <select name="for_all" class="form-control" id="">
                                        <option value="1" @if(old('for_all') == '1') selected @endif>Yes</option>
                                        <option value="0" @if(old('for_all') == '0') selected @endif>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <a href="{{url(config('data.admin.allowance.listing'))}}"><button type="button" class="btn btn-secondary btn-round">{{__('default_label.back')}}</button></a>
                                    <button type="submit" class="btn btn-primary btn-round">{{__('default_label.add')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
