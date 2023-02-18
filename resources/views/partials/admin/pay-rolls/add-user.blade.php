@php
    $users = $data['users'];
    $payroll = $data['payroll'];
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 offset-2">

                        <form method="post" action="{{url(config('data.admin.payroll.user-save'))}}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $payroll->id }}">
                            <div class="row">
                                <div class="col-md-6 form-group offset-3">
                                    <label>Select Employees</label>
                                    <select style="width: 100%" class="form-control js-example-basic-multiple" id="employeeSelect" multiple="multiple" name="user_id[]" required>
                                        @foreach($users AS $key => $user)
                                        <option value="{{$user->id}}" @if($user->status != 'Other') selected @endif>{{$user->full_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="update ml-auto mr-auto">
                                    <a href="{{url(config('data.admin.payroll.detail-add-user').'/'.$payroll->id)}}"><button type="button" class="btn btn-secondary btn-round">{{__('default_label.back')}}</button></a>
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


@section('footer_js')

@parent
<script>
    $(document).ready(function () {

        // $(function () {
        //     $('#datepicker').datepicker({
        //         zIndexOffset: 9999,
        //         endDate: '-1m',
        //         minViewMode: 'months',
        //         format: 'MM, yyyy',
        //         autoclose: true
        //     });
        // });
        $('#employeeSelect').select2({
            placeholder: 'Select Users',
            allowClear: true
        });
    });

</script>
@endsection
