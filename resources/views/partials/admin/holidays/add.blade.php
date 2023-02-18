<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 offset-2">

                        <form method="post" action="{{url(config('data.admin.holiday.save'))}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group offset-3">
                                    <label>Name</label>
                                    <input name="name" type="text" class="form-control" required  />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group offset-3">
                                    <label>Type</label>
                                    <select name="type" class="form-control">
                                        <option value="0" selected>Holiday</option>
                                        <option value="1">Working Day</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group offset-3">
                                    <label>Date</label>
                                    <input name="date" type="date" class="form-control" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <a href="{{url(config('data.admin.holiday.listing'))}}"><button type="button" class="btn btn-secondary btn-round">{{__('default_label.back')}}</button></a>
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
