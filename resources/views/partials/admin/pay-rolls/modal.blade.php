<div class="modal fade openUserPayRollClass bd-example-modal-lg" id="openUserPayRoll{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="openUserPayRollLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $info->user->full_name ?? '' }} ({{ date('F, Y', strtotime($info->payRoll->date)) }})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td> Gross Salary</td>
                            <td> Rs {{ convertValueToCommaSeparated($info->gross_salary) }} </td>
                            <td> Base Salary </td>
                            <td> Rs {{ convertValueToCommaSeparated($info->base_salary) }} </td>
                        </tr>
                        <tr>
                            <td> Hosue Rent</td>
                            <td> Rs {{ convertValueToCommaSeparated($info->house_rent) }} </td>
                            <td> Utility Bills</td>
                            <td> Rs {{ convertValueToCommaSeparated($info->utility) }} </td>
                        </tr>
                        <tr>
                            <td> Allowances</td>
                            <td> Rs {{ convertValueToCommaSeparated($info->allowances) }} </td>
                            <td> Late Deductions </td>
                            <td> Rs {{ convertValueToCommaSeparated($info->late_deduction) }} </td>
                        </tr>
                        <tr>
                            <td> Leave Deductions</td>
                            <td> Rs {{ convertValueToCommaSeparated($info->leave_deduction) }} </td>
                            <td> Other Contributions </td>
                            <td> Rs {{ convertValueToCommaSeparated($info->otherContributions()) }} </td>
                        </tr>
                        <tr>
                            <td> Loan Deductions</td>
                            <td> Rs {{ convertValueToCommaSeparated($info->loan_deduction) }} </td>
                            <td> Govt Tax</td>
                            <td> Rs {{ convertValueToCommaSeparated($info->govrt_tax) }} </td>
                        </tr>
                        <tr>
                            <td> Other Deductions </td>
                            <td> Rs {{ convertValueToCommaSeparated($info->otherDeductions()) }} </td>
                            <td> </td>
                            <td> </td>
                        </tr>
                    </tbody>
                </table>
                {{-- allowances --}}
                <div class="row form-group">
                    <div class="col-md-6">
                        Allowances
                    </div>
                    @if (!$info->payRoll->lock)
                        <div class="col-md-6 text-right">
                            <button class="btn btn-success btn-sm" data-target="#openAllowancesModal{{ $key }}" data-toggle="modal">Add</button>
                        </div>
                    @endif
                </div>
                <table class="table table-striped">
                    <thead class=" text-white" style="background-color: #51bcda;">
                        <th> Name </th>
                        <th> Amount </th>
                        <th> Action </th>
                    </thead>
                    <tbody>
                        @foreach ($info->getAllowances as $index => $allow)
                            <tr>
                                <td> {{ $allow->name }} </td>
                                <td> {{ convertValueToCommaSeparated($allow->pivot->amount) }} </td>
                                <td> <a href="#!" data-url="{{ url(config('data.admin.payroll.userallowancedelete') . '/' . $allow->pivot->id) }}" class="confirmation-popup btn-sm btn btn-danger" title="Delete">Delete</a> </td>
                            </tr>
                        @endforeach
                        @if (count($info->getAllowances) == 0)
                            <tr>
                                <td colspan="3" style="text-align: center">No Record Found</td>
                            </tr>
                        @endif
                    </tbody>

                </table>
                {{-- deductions --}}
                <div class="row form-group">
                    <div class="col-md-6">
                        Contributions & Deductions
                    </div>
                    <div class="col-md-6 text-right">
                        {{-- <button class="btn btn-success btn-sm" data-target="#openAllowancesModal{{$key}}" data-toggle="modal">Add</button> --}}
                    </div>
                </div>
                <table class="table table-striped">
                    <thead class=" text-white" style="background-color: #51bcda;">
                        <th> Name </th>
                        <th> Amount </th>
                        <th> Type </th>
                        <th> Action </th>
                    </thead>
                    <tbody>
                        @foreach ($info->extras as $exIndex => $extra)
                            <tr>
                                <td> {{ $extra->name }} </td>
                                <td> {{ convertValueToCommaSeparated($extra->amount) }} </td>
                                <td> {{ $extra->type }} </td>
                                <td> <a href="#!" data-url="{{ url(config('data.admin.payroll.userextradelete') . '/' . $extra->id) }}" class="confirmation-popup btn-sm btn btn-danger" title="Delete">Delete</a> </td>
                            </tr>
                        @endforeach
                        @if (count($info->extras) == 0)
                            <tr>
                                <td colspan="4" style="text-align: center">No Record Found</td>
                            </tr>
                        @endif
                        @if (!$info->payRoll->lock)
                            <form action="{{ url(config('data.admin.payroll.userextrasave')) }}" method="post" class="extra-form">
                                @csrf
                                <input type="hidden" name="user_pay_roll_id" value="{{ $info->id }}">
                                <tr>
                                    <td> <input autocomplete="off" type="text" class="form-control" required name="name"> </td>
                                    <td> <input autocomplete="off" type="number" class="form-control" required name="amount"> </td>
                                    <td>
                                        <select name="type" id="" class="form-control">
                                            <option value="{{ EXTRA_CONTRIBUTIONS }}">{{ EXTRA_CONTRIBUTIONS }}</option>
                                            <option value="{{ EXTRA_DEDUCTIONS }}">{{ EXTRA_DEDUCTIONS }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm" type="submit">Add</button>
                                    </td>
                                </tr>
                            </form>
                        @endif
                    </tbody>

                </table>
                <hr>
                @if (!$info->payRoll->lock)
                    <form action="{{ url(config('data.admin.payroll.usergovrttax')) }}" method="post" class="extra-form">
                        @csrf
                        <input type="hidden" name="user_pay_roll_id" value="{{ $info->id }}">
                        <div class="row">
                            <div class="col-md-2">
                                Govt Tax
                            </div>
                            <div class="col-md-6">
                                <input type="number" placeholder="Govt tax" class="form-control" required name="govrt_tax" value="{{ $info->govrt_tax }}">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-success btn-sm" type="submit">Update</button>
                            </div>
                        </div>
                    </form>
                @endif
                <hr>
                <div class="row form-group">
                    <div class="col-md-4">
                        <div class="text-center p-3 font-weight-bolder bg-success text-white">
                            <span class="d-block">Gross Salary</span>
                            {{ convertValueToCommaSeparated($info->gross_salary) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 font-weight-bolder bg-primary text-white">
                            <span class="d-block">Base Salary</span>
                            {{ convertValueToCommaSeparated($info->base_salary) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 font-weight-bolder bg-danger text-white">
                            <span class="d-block">Net Salary</span>
                            {{ convertValueToCommaSeparated($info->net_salary) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@php
    $allowances = App\Allowance::whereDoesntHave('userPayRolls', function ($q) use ($info) {
        $q->where('user_pay_rolls.user_id', $info->user_id);
    })->get();
@endphp

<div class="modal fade openAllowancesModalClass bd-example-modal-lg" id="openAllowancesModal{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="openAllowancesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Allowance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url(config('data.admin.payroll.userallowancesave')) }}" enctype="multipart/form-data">
                    <input type="hidden" name="user_pay_roll_id" value="{{ $info->id }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group offset-3">
                            <label>Select Allowance</label>
                            <select name="allowance_id" id="" class="form-control" required>
                                @foreach ($allowances as $res)
                                    <option value="{{ $res->id }}">{{ $res->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="update ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary btn-round">{{ __('default_label.add') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
