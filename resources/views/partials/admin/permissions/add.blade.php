<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>
            <form method="post" action="{{url('admin/permissions/save')}}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 offset-4">
                            <div class="form-group">
                                <label>Role Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-4 offset-4">
                            <div class="form-group">
                                <label>Role Slug</label>
                                <input type="text" name="slug" class="form-control" required>
                            </div>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="update ml-auto mr-auto">
                            <a href="{{url('admin/permissions/listing')}}"><button type="button" class="btn btn-secondary btn-round">{{__('default_label.back')}}</button></a>
                            <button type="submit" class="btn btn-primary btn-round">{{__('default_label.add')}}</button>
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
</script>

@endsection
