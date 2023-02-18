@php
    $variables = config('data.letter_variables');
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>
            <div>
                <div class="col-md-12">
                    <form method="post" action="{{url(config('data.admin.letters.store'))}}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Title</label>
                                <input type="text" autocomplete="off" name="title" class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <label>
                                    Allowed Variabled
                                </label>
                                <p class="custom_text_primary">
                                    @foreach($variables as $var)
                                        {{ $var }},
                                    @endforeach
                                </p>
                            </div>
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            <div class="col-md-12 form-group">
                                <label>Description</label>
                                <textarea name="body" id="description" class="form-control" rows="20">{{ old('description') ? old('description') : '' }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                @if(checkPermission(LETTERS, WRITE))
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
<script src="{{ url('assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ url('assets/ckeditor/adapters/jquery.js') }}"></script>
<script>
    var usersData = [];

    $(document).ready(function () {
        $('#description').ckeditor();
    });

    $('button.btn-sm').on('click', function () {
        className = $(this).data('class');
        if (className == 'all') {
            updateSelect2(usersData);
        }
        if (className == 'active') {
            data = usersData.filter((value, index) => {
                value.selected = true;
                return (value.status == 'Permanent' || value.status == '1M Probation' || value.status == '2M Probation' || value.status == '3M Probation');
            })
            // console.log(className, data);
            updateSelect2(data);
        }

    });

    function updateSelect2(data) {
        $('.emails.select2 option').remove();
        if (data.length == 0) {
            $('.emails.select2').val(null).trigger('change');

        } else {
            data.forEach(element => {
                var newOption = new Option(element.text, element.id, true, true);
                $('.emails.select2').append(newOption).trigger('change');
            });

        }
    }

</script>
@endsection
