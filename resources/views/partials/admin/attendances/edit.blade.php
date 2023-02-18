<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>
            <form method="post" action="{{url(config('data.admin.attendance.update'))}}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <input type="hidden" name="id" value="{{$data->id}}">
                    <div class="row">
                        <div class="col-md-4 offset-4">
                            <div class="form-group">
                                <label>{{__('attendance.type')}}</label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" required value="Check In" name="type" @if($data->type == CHECK_IN) checked @endif >{{__('attendance.check_in')}}
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" required value="Check Out" name="type" @if($data->type == CHECK_OUT) checked @endif >{{__('attendance.check_out')}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 offset-4">
                            <div class="form-group">
                                <label>{{__('attendance.time')}}</label>
                                <input name="action_time" type="datetime" autocomplete="off" id="datetimepicker1" class="form-control" required value="{{ $data->action_time }}" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="update ml-auto mr-auto">
                            <a href="{{url(config('data.admin.attendance.listing'))}}"><button type="button" class="btn btn-secondary btn-round">{{__('default_label.back')}}</button></a>
                            <button type="submit" class="btn btn-primary btn-round">{{__('default_label.update_btn')}}</button>
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
        $('.employee-select').select2();
        $(function() {
            $('#datetimepicker1').datetimepicker();
        });
        $('#employeeSelect').select2({
            ajax: {
                url: "{{ URL('admin/helper/search-employee') }}",
                dataType: "json",
                processResults: function(data) {
                    return {
                        results: data.result
                    }
                }
            }
        });
    });
</script>

@endsection