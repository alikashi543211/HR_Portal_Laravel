<div class="row">
    <div class="col-md-12">
        <h4 class="card-title"> {{ $page_title }} </h4>
    </div>
</div>

<div class="content">
    <div class="row">
        @foreach ($data->widget as $index => $details)
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="fas {{ $details['icon'] }} {{ $details['text-color'] }}"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category"> {{ ucfirst($index) }} </p>
                                    <p class="card-title"> {{ $details['count'] }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title"> {{ __('dashboard.upcoming_birthdays') }} </h4>
            </div>
            <div class="card-body" id="upcommingBirthdays-calender">
                {{-- @if (count($data->upcomingDob) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class=" text-primary">
                                <th> Sr# </th>
                                <th> Name </th>
                                <th> Date Of Birth </th>
                            </thead>
                            <tbody>
                                @foreach ($data->upcomingDob as $index => $info)
                                    <tr>
                                        <td> {{ $index + 1 }} </td>
                                        <td>
                                            <a href="{{ url(config('data.admin.user.details') . '/' . $info->id) }}">{{ isset($info->first_name) ? $info->first_name : '' }} {{ isset($info->last_name) ? $info->last_name : '' }}</a>
                                        </td>
                                        <td> {{ isset($info->dob) ? date('jS M', strtotime($info->dob)) : '' }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center"> {{ __('default_label.no_record_found') }} </p>
                @endif --}}
            </div>
        </div>
    </div>
</div>
