<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 offset-2">

                        <form method="post" action="{{ url(config('data.admin.payroll.tax-save')) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Name</label>
                                    <input name="name" type="text" autocomplete="off" class="form-control" required />
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Amount</label>
                                    <input name="amount" min="0" type="number" autocomplete="off" class="form-control" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>PayRoll Month</label>
                                    <input name="month" type="text" autocomplete="off" class="form-control" value="{{ $data->date ?? '' }}" readonly />
                                    <input name="pay_roll_id" type="text" hidden value="{{ $data->id }}" />
                                </div>
                                <div class="col-md-6 d-flex">
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input name="image" id="imgInp" type="file" autocomplete="off" class="form-control pay-roll-tax--file-input" required />
                                    </div>
                                    <img src="{{ asset('uploads/employee/picture_4NFURCgKSYUWkSFAgVOHOPvhZ87nn5qeEhsX1rPv.png') }}" id="setImage" class=".custom-image--payroll-tax" alt="Iamge.png">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="update ml-auto mr-auto">
                                    <a href="{{ url(config('data.admin.payroll.listing')) }}"><button type="button" class="btn btn-secondary btn-round">{{ __('default_label.back') }}</button></a>
                                    <button type="submit" class="btn btn-primary btn-round">{{ __('default_label.add') }}</button>
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
        $(document).ready(function() {

            $("#imgInp").on('change', function() {
                var file = $(this).get(0).files[0];
                if (file) {
                    var reader = new FileReader();

                    reader.onload = function() {
                        $("#setImage").attr("src", reader.result);
                    }

                    $('#setImage').attr('src', reader.readAsDataURL(file));
                }

            });


        });
    </script>
@endsection
