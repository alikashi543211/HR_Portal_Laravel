@extends('employees.layouts.app')
@section('title')
    Loan
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/6.2.7/css/tempus-dominus.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
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

        .dublicate_Dates_Error {
            border: 1px solid red !important
        }

        p.duplicate-alert {
            color: red;
            font-size: 0.8rem;
        }

        .custom-height {
            height: 88px !important;
        }

        .btn.btn-secondary.remove-installment.orange {
            width: auto;
            height: 3.5rem;
        }

        .col-md-4.form-group.custom-height {
            display: flex;
            align-items: end
        }

        /* .datepicker-months table.table-condensed,
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
                                                                                                                                                                                                } */
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

                    <form method="POST" action="{{ route('employee.loans.store') }}" id="submitForm">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-md-4 form-group">
                                    <label for="amount">User</label>
                                    <input type="text" value="{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}" readonly class="form-control">
                                </div> --}}
                                <div class="col-md-4 form-group">
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" class="form-control" placeholder="Loan Amount" required>
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
                                <div class="col-md-12 mt-4 installments">
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label for="1_installment">Installment 1 <span class="text-danger">*</span></label>
                                            <input type="number" name="installment[]" id="1_installment" class="form-control installment-amount" placeholder="Installment 1" required>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="1_installment_month">Installment 1 <span class="text-danger">*</span></label>
                                            <input type="text" name="month[]" id="1_installment_month" class="form-control datepicker" placeholder="Installment Month" autocomplete="off" required>
                                            <p class="duplicate-alert d-none">Same installment month can not be selected twice</p>
                                        </div>
                                        <div class="col-md-4 form-group custom-height">
                                            <button class="btn  mt-4 orange" type="button" id="add-installment"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
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
                $('#submitBtn').prop('disabled', false);
            } else {
                $('#add-installment').show();
                $('#submitBtn').prop('disabled', true);
            }
        }

        $(document).on('click', '#add-installment', function() {
            let totalRows = $('.installments').find('.row').length;
            let html = `<div class="row">
            <div class="col-md-4 form-group">
                <label for="${totalRows+1}_installment">Installment ${totalRows+1} <span class="text-danger">*</span></label>
                <input type="number" name="installment[]" id="${totalRows+1}_installment" class="form-control installment-amount" placeholder="Installment ${totalRows+1}" required value="0">
            </div>
            <div class="col-md-4 form-group">
                <label for="${totalRows+1}_installment_month">Installment ${totalRows+1} <span class="text-danger">*</span></label>
                <input type="text" name="month[]" id="${totalRows+1}_installment_month" class="form-control datepicker" placeholder="Installment Month" required autocomplete="off">
                <p class="duplicate-alert d-none">Same installment month can not be selected twice</p>
            </div>
            <div class="col-md-4 form-group custom-height" >
                <button class="btn btn-secondary mt-4 remove-installment orange" style="border-radius: 5px;" type="button"><i class=" fas fa-minus"></i></button>
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

        $('#submitForm').on('submit', function(e) {
            let data = document.querySelectorAll('.form-control.datepicker');
            var dates = Array.from(data)
            var count = 0;
            for (var i = 0; i < dates.length; i++) {
                for (var j = i + 1; j < dates.length; j++) {
                    $(dates[i]).removeClass('dublicate_Dates_Error')
                    $(dates[i]).siblings('.duplicate-alert').addClass('d-none')
                    $(dates[j]).removeClass('dublicate_Dates_Error')
                    $(dates[j]).siblings('.duplicate-alert').addClass('d-none')
                }
            }

            for (var i = 0; i < dates.length; i++) {
                for (var j = i + 1; j < dates.length; j++) {
                    if ($(dates[i]).val() == $(dates[j]).val()) {
                        $(dates[i]).addClass('dublicate_Dates_Error')
                        $(dates[i]).siblings('.duplicate-alert').removeClass('d-none')
                        $(dates[j]).addClass('dublicate_Dates_Error')
                        $(dates[j]).siblings('.duplicate-alert').removeClass('d-none')
                        count++;
                    }
                }
            }
            if (count != 0) {
                e.preventDefault();
            }
        })

        $(document).on('input change', '.col-md-4.form-group .form-control.datepicker', function() {
            $(this).removeClass('dublicate_Dates_Error')
            $(this).siblings('.duplicate-alert').addClass('d-none')

        })
    </script>
@endsection
