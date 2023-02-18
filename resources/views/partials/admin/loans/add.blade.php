@section('header')
    @parent
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
        }
    </style>
@endsection
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ $page_title }}</h4>
            </div>

            <form method="post" action="{{ url(config('data.admin.loans.save')) }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="amount">User</label>
                            <select style="width: 100%" class="form-control js-example-basic" id="employeeSelect" name="user_id" required>
                                @foreach ($data as $key => $user)
                                    <option value="{{ $user->id }}" @if ($user->status != 'Other') selected @endif>{{ $user->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" placeholder="Loan Amount" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="remaining_amount">Remaining Amount</label>
                            <h4 id="remaining_amount" class="my-0"></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Installments</h5>
                        </div>
                        <div class="col-md-12 installments">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="1_installment">Installment 1</label>
                                    <input type="number" name="installment[]" id="1_installment" class="form-control installment-amount" placeholder="Installment 1" required>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="1_installment_month">Installment 1</label>
                                    <input type="text" name="month[]" id="1_installment_month" class="form-control datepicker" placeholder="Installment Month" required>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary mt-4" type="button" id="add-installment"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="submit" value="Submit" class="btn btn-primary" disabled>
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
