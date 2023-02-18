@php
    $letter = $data['letter'];
    $users = $data['users'];
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
                    <form method="post" action="{{url(config('data.admin.letters.mail'))}}">
                        @csrf
                        <div class="row">

                            <div class="col-md-12 form-group">
                                <div class="card card_shadow">
                                    <div class="card-header">
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            {!! $letter->body !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{ $letter->id }}">
                            <input type="hidden" name="body" value="{{ $letter->body }}">
                            <input type="hidden" name="title" value="{{ $letter->title }}">
                            <div class="col-md-12 my-2">
                                <h4>Variables</h4>
                            </div>
                            @foreach ($variables as $key => $var)
                                <div class="col-md-12 form-group">
                                    <label>{{ $key }}</label>
                                    <input type="text" class="form-control" name="variable[{{ $key }}]" placeholder="{{ $var }}">
                                </div>
                            @endforeach

                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                @if(checkPermission(LETTERS, WRITE))
                                <button type="submit" class="btn btn-primary btn-round">{{__('default_label.generate_btn')}}</button>
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
        $('.select2').select2({
            placeholder: 'Select Users',
            allowClear: true,
        });
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
