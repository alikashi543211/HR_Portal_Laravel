@section('header')
    @parent
    <style>
        label.form-check-label {
            padding-left: 10px !important;
        }

        textarea.form-control {
            padding: 0.375rem 0.75rem !important;
        }

        .custom-files {
            display: flex;
            justify-content: space-between;
            width: 65%;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }


        .files_row.d-flex {
            justify-content: flex-start;
            margin-top: 10px;
            width: fit-content;
            flex-wrap: wrap;
        }

        .files__list {
            padding: 0.5rem 1rem 0.5rem 1rem;
            background: #191919;
            margin-right: 1rem;
            text-align: center;
            border-radius: 1rem;
            color: #fff;
            position: relative;
            width: 12rem;
            display: flex;
            flex-basis: 12rem;
            margin-top: 0.5rem;
        }



        .files__list a {
            color: #fff;
        }

        .files__list a:hover {
            color: #fff;
        }
    </style>
@endsection
@php
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $data->user->first_name ?? 'N/A' . "'s Leave" }} ({{ $data->status == PENDING ? 'Pending' : ($data->status == ACCEPTED ? 'Accepted' : 'Rejected') }})</h4>
            </div>

            <form method="post" action="{{ url(config('data.admin.leave-request.update')) }}">
                @csrf
                <input type="hidden" name="id" value="{{ $data->id }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label style="font-size: 16px;">Type</label>
                            <div class="form-check">
                                <input type="radio" disabled name="type" {{ $data->type == 0 ? 'checked' : '' }} id="flexRadioDefault1" />
                                <label for="flexRadioDefault1"> Sick </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" disabled name="type" {{ $data->type == 1 ? 'checked' : '' }} id="flexRadioDefault2" />
                                <label for="flexRadioDefault2"> Casual </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label style="font-size: 16px;">Period</label>
                            <div class="form-check">
                                <input type="radio" disabled name="type" {{ $data->period_type == 0 ? 'checked' : '' }} id="flexRadioDefault3" />
                                <label for="flexRadioDefault3"> Full Day </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" disabled name="type" {{ $data->period_type == 1 ? 'checked' : '' }} id="flexRadioDefault4" />
                                <label for="flexRadioDefault4"> First Half </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" disabled name="type" {{ $data->period_type == 2 ? 'checked' : '' }} id="flexRadioDefault5" />
                                <label for="flexRadioDefault5"> Second Half </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label style="font-size: 16px;">From Date</label>
                            <input type="text" name="from_date" value="{{ date('d-M-Y', strtotime($data->from_date)) }}" readonly class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label style="font-size: 16px;">To Date</label>
                            <input type="text" name="to_date" value="{{ date('d-M-Y', strtotime($data->to_date)) }}" readonly class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label style="font-size: 16px;">Reason</label>
                            <textarea disabled class="form-control">{{ $data->reason }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label style="font-size: 16px;">Files</label>

                            <div class="files_row d-flex">
                                @foreach ($data->leaveRequestAttachments as $key => $item)
                                    <div class="files__list">
                                        <a href="{{ asset($item->file) }}" target="_blank">Document_{{ $key + 1 }}</a>
                                    </div>
                                @endforeach
                            </div>
                            {{-- <div class="custom-files">
                                @foreach ($data->leaveRequestAttachments as $key => $item)
                                    <a href="{{ asset('uploads/leave-request-attachments/' . $item->file) }}" target="_blank">{{ 'Document_' . $key + 1 }}</a>
                                @endforeach



                            </div> --}}
                        </div>
                        @if (checkPermission(LEAVE_REQUEST, WRITE) && $data->status == PENDING)
                            <div class="col-md-6">
                                <label style="font-size: 16px;">Leave Adjustment</label>
                                <div class="form-check">
                                    <input type="radio" name="leave_adjust" checked id="flexRadioDefault6" value="1" />
                                    <label for="flexRadioDefault6"> Adjust </label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="leave_adjust" id="flexRadioDefault7" value="0" />
                                    <label for="flexRadioDefault7"> Not Adjust </label>
                                </div>
                            </div>
                            <div class="col-md-6 align-items-end justify-content-end d-flex">
                                <input type="submit" value="Accepted" class="btn btn-primary">
                                <a href="{{ url(config('data.admin.leave-request.reject') . '/' . $data->id) }}"><button type="button" class="btn btn-danger">Rejected</button></a>
                            </div>
                        @endif
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
            //
        });
    </script>
@endsection
