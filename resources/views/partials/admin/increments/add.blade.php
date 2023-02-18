<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>
            <div>
                <div class="col-md-8 offset-2">
                    {{-- {{ dd(config('data.admin.increments.save')) }} --}}
                    <form method="post" action="{{url(config('data.admin.increments.save'))}}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Name</label>
                                <select name="user_id" class="form-control select2">
                                    @foreach ($data as $user)
                                    <option data-salary="{{ $user->base_salary }}" value="{{ $user->id }}" @if(old('user_id')==$user->id) selected @endif>{{ $user->first_name . ' ' . $user->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Month</label>
                                <input type="text" autocomplete="off" name="month" id="datetimepicker1" class="form-control" value="{{old('month') ? old('month') : date('F, Y')}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Current</label>
                                <input name="previous" type="number" class="form-control" readonly value="{{ old('previous') ? old('previous'):$data[0]->base_salary  }}" />
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Increment</label>
                                <input name="increment" class="form-control" required value="{{ old('increment') }}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                @if(checkPermission(INCREMENTS, WRITE))
                                <button type="submit" class="btn btn-primary btn-round">{{__('default_label.add')}}</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
@section('footer_js')
@parent
<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('#datetimepicker1').datepicker({
            autoClose: true,
            zIndexOffset: 9999,
            minViewMode: 'months',
            format: 'MM, yyyy'
        });
    });

    $(document).on('change', 'select[name=user_id]', function () {
        $('input[name=previous]').val($('select[name=user_id] option:selected').data('salary'));
    })

</script>
@endsection
