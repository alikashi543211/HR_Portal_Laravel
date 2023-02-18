@extends('employees.layouts.app')
@section('title')
    Loans
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css" />
    <style>
        input.form-control {
            border-radius: 5px;
        }

        input.btn.btn-primary.rounded-2 {
            border-radius: 5px;
        }

        button#add-installment {
            border-radius: 5px
        }

        button.orange,
        input.orange {
            background: #F47429 !important;
            color: #fff !important;
            border: 1px solid #F47429 !important;
            outline: none !important;
        }

        button.orange:hover,
        input.orange:hover {
            background: #fff !important;
            color: #F47429 !important;
            border: 1px solid #F47429 !important;
            outline: none !important;

        }

        button.orange:active,
        input.orange:active {
            background: #fff !important;
            color: #F47429 !important;
            border: 1px solid #F47429 !important;
            outline: none !important;
        }

        button.orange:disabled,
        input.orange:disabled {
            background: #f7ac81 !important;
            color: #fff !important;
            border: 1px solid #f7ac81 !important;
        }

        .datepicker-months table.table-condensed,
        .datepicker-years .table-condensed {
            background: #282525c9;
        }

        .table-condensed tbody tr td span.month.disabled {
            color: #fff !important;
        }

        .datepicker.datepicker-dropdown th.datepicker-switch,
        .datepicker.datepicker-dropdown th.next,
        .datepicker.datepicker-dropdown th.prev {
            font-weight: 300;
            color: #fff;
        }

        .datepicker table tr td span {
            color: #fff !important;

        }

        .datepicker table tr td span.focused,
        .datepicker table tr td span:hover {
            background: #F47429 !important;
            color: #fff !important;
        }

        .datepicker table tr td span.disabled {
            background: transparent !important;
            color: #fff !important;
        }

        .datepicker .datepicker-switch:hover,
        .datepicker .next:hover,
        .datepicker .prev:hover,
        .datepicker tfoot tr th:hover {
            background: #F47429 !important;
            color: #fff !important;
        }

        .datepicker.datepicker-dropdown td.day:hover,
        .datepicker.datepicker-dropdown th.next:hover,
        .datepicker.datepicker-dropdown th.prev:hover {
            background: #F47429 !important;
            color: #fff !important;
            box-shadow: none;

        }

        .btn.btn-sm.orange {
            border-radius: 5px
        }

        .btn.btn-rounded.btn-sm.btn-outline-primary:hover {
            background: #fff !important;
            color: #F47429 !important;
            border: 1px solid #F47429 !important;
            outline: none !important;

        }

        .btn.btn-rounded.btn-sm.btn-outline-primary:disabled {
            background: #f7ac81 !important;
            color: #fff !important;
            border: 1px solid #f7ac81 !important;

        }

        .btn.status-paid-badge {
            background: #78c0f3;
            color: #0965a7;
            border-radius: 3rem;
        }

        .btn.status-pending-badge {
            background-color: #F5CD6F;
            color: #91611D;
            border-radius: 3rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- row -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> Loan Request</h4>
                    </div>

                    <form method="POST" action="{{ route('employee.loans.store') }}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-md-4 form-group">
                                    <label for="amount">User</label>
                                    <input type="text" value="{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}" readonly class="form-control">
                                </div> --}}
                                <div class="col-md-4 form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" id="amount" value="{{ $loan->amount }}" class="form-control" placeholder="Loan Amount" disabled required>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-4">
                                    <h4 class="text-black"><strong>Installments</strong></h4>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="remaining_amount">Remaining Amount</label>
                                    <h4 id="remaining_amount" class="my-0"></h4>
                                </div>
                                {{-- {{ dd() }} --}}
                                <div class="col-md-12 mt-4 installments">
                                    @foreach ($loan->installments as $key => $installment)
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label for="1_installment">Installment {{ $key + 1 }}</label>
                                                <input type="number" name="installment[]" id="{{ $key + 1 }}_installment" class="form-control installment-amount" placeholder="Installment {{ $key + 1 }}" required value="{{ $installment->amount }}" @if ($installment->status == PAID)  @endif disabled>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="{{ $key + 1 }}_installment_month">Installment {{ $key + 1 }}</label>
                                                <input type="text" name="month[]" id="{{ $key + 1 }}_installment_month" class="form-control datepicker" placeholder="Installment Month" required value="{{ date('F, Y', strtotime($installment->month)) }}" @if ($installment->status == PAID)  @endif disabled>
                                            </div>
                                            {{-- <div class="col-md-4 form-group" style="margin-top: auto">
                                                @if ($loop->first)
                                                    <button class="btn btn-primary mt-4 orange" type="button" id="add-installment" style="display: none" disabled><i class="fas fa-plus"></i></button>
                                                @else
                                                    @if ($installment->status == PENDING)
                                                        <button class="btn btn-secondary mt-4 remove-installment orange" type="button" disabled><i class=" fas fa-minus"></i></button>
                                                    @endif
                                                @endif
                                            </div> --}}
                                            <div class="col-md-4 form-group" style="margin-top: auto">
                                                @if ($installment->status == PAID)
                                                    <button class="btn status-paid-badge">Paid</button>
                                                @elseif ($installment->status == PENDING)
                                                    <button class="btn status-pending-badge">Pending</button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-12 text-right form-group">
                                    {{-- <input type="submit" value="Submit" class="btn btn-primary rounded-2 orange" disabled> --}}
                                    <a href="{{ route('employee.loans.listing') }}" class="btn btn-rounded btn-sm  btn-outline-primary">Back</a>
                                    <button class="btn btn-rounded btn-sm btn-outline-primary" id="submitBtn" disabled> Submit </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="http://127.0.0.1:8000/assets/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            datePickerTrigger($('.datepicker'));

            $('#employeeSelect').select2({
                placeholder: 'Select User',
                allowClear: true
            });
        });

        function datePickerTrigger(selector) {
            $(selector).datepicker({
                zIndexOffset: 9999,
                startDate: '+1m',
                minViewMode: 'months',
                format: 'MM, yyyy',
                autoclose: true
            });
        }

        $(document).on('blur', '#amount', function() {
            $('#remaining_amount').html($(this).val());
            $('#1_installment').val($(this).val());
            calcTotalInstallment();
        });

        $(document).on('input', '.installment-amount', function() {
            calcTotalInstallment();
        });



        function calcTotalInstallment() {
            let totalAmount = parseInt($('#amount').val());
            let installmentsAmount = 0;
            $('.installment-amount').each(function() {
                installmentsAmount += (parseInt($(this).val()) ? parseInt($(this).val()) : 0);
            });
            console.log(totalAmount, installmentsAmount);
            $('#remaining_amount').html(totalAmount - installmentsAmount);
            if (totalAmount == installmentsAmount) {
                $('#add-installment').hide();
                $('input[type=submit]').prop('disabled', false);
            } else {
                $('#add-installment').show();
                $('input[type=submit]').prop('disabled', true);
            }
        }

        $(document).on('click', '#add-installment', function() {
            let totalRows = $('.installments').find('.row').length;
            let html = `<div class="row">
            <div class="col-md-4 form-group">
                <label for="${totalRows+1}_installment">Installment ${totalRows+1}</label>
                <input type="number" name="installment[]" id="${totalRows+1}_installment" class="form-control installment-amount" placeholder="Installment ${totalRows+1}" required value="0">
            </div>
            <div class="col-md-4 form-group">
                <label for="${totalRows+1}_installment_month">Installment ${totalRows+1}</label>
                <input type="text" name="month[]" id="${totalRows+1}_installment_month" class="form-control datepicker" placeholder="Installment Month" required>
            </div>
            <div class="col-md-4">
                <button class="btn btn-secondary mt-4 remove-installment" type="button"><i class=" fas fa-minus"></i></button>
            </div>
        </div>`;
            $('.installments').append(html);
            datePickerTrigger($('#' + (totalRows + 1) + '_installment_month'));
            calcTotalInstallment()

        });

        $(document).on('click', '.remove-installment', function() {
            $(this).closest('.row').remove();
            let index = 1;
            $('.installments').find('.row').each(function() {
                $(this).find('input[type=number]').attr('id', index + '_installment').attr('placeholder', 'Installment ' + index);
                $(this).find('input[type=text]').attr('id', index + '_installment');
                $(this).find('label').first().attr('for', index + '_installment').html('Installment ' + index);
                $(this).find('label').last().attr('for', index + '_installment_month').html('Installment ' + index);
                index++;
            })
            calcTotalInstallment();
        });
    </script>
@endsection
