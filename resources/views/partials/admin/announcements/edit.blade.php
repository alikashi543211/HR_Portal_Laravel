@php
$announcement= $data['announcement'];
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>
            <div>
                <div class="col-md-12">
                    {{-- {{ dd(config('data.admin.increments.save')) }} --}}
                    <form method="post" action="{{url(config('data.admin.announcements.update'))}}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $announcement->id }}">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Title</label>
                                <input type="text" autocomplete="off" name="title" class="form-control" value="{{ $announcement->title }}">
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Description</label>
                                <textarea name="description" id="description" class="form-control" rows="20">{{ $announcement->description }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="update ml-auto mr-auto">
                                @if(checkPermission(ANNOUNCEMENTS, WRITE))
                                <button type="submit" class="btn btn-primary btn-round">{{__('default_label.update_btn')}}</button>
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
    $(document).ready(function () {
        $('#description').ckeditor();
    });

</script>
@endsection
