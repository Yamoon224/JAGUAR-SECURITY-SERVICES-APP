<ul class="nav nav-tabs nav-default" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
            <div class="d-flex align-items-center">
                <div class="tab-icon"><i class='bx bx-calendar-check font-18 me-1'></i></div>
                <div class="tab-title">@lang('lang.month', ['param'=>'']) : {{ $month }} | @lang('lang.method', ['suffix'=>'']) : {{ $bank }}</div>
            </div>
        </a>
    </li>
</ul>
<div class="tab-content py-3">
    <div class="tab-pane fade show active" id="table-customer" role="tabpanel">
        <div class="text-center mb-2">
            <a class="btn btn-primary mb-3 mb-lg-0" href="{{ route('prints.bank', [$month_id, $bank_id]) }}" id='payByBank'><i class='bx bx-printer'></i>@lang('lang.method', ['suffix'=>'']) : {{ $bank }}</a>
        </div>
        <div class="col">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <td>#</td>
                            <th>@lang('lang.employee', ['param'=>''])</th>
                            <th>@lang('lang.salary')</th>
                            <th>@lang('lang.prime')</th>
                            <th>@lang('lang.amount')</th>
                            <th>@lang('lang.acompte') & @lang('lang.sanction')</th>
                            <th>@lang('lang.tax') (13% @lang('lang.amount'))</th>
                            <th>@lang('lang.pay_net') @lang('lang.ttc')</th>
                            <th>@lang('lang.rib') * @lang('lang.bank_name')</th>
                            <th>@lang('lang.action', ['param'=>'s'])</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php( $sum = 0 )
                        @php( $salaries = 0 )
                        @php( $primes = 0 )
                        @php( $taxs = 0 )
                        @php( $amounts = 0 )
                        @php( $payees = 0 )
                        @php( $sanctions = 0 )
                        @foreach ($employees as $item)
                        @php( $amount = $item->salary+$item->prime )
                        @php( $tax = $amount*($item->cnss+$item->rts)*0.01 )
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->firstname." ".$item->name." * ".$item->phone." * ".$item->position." * ".$item->contract }}</td>
                            <td>{{ moneyFormat($item->salary) }}</td>
                            <td>{{ moneyFormat($item->prime) }}</td>
                            <td>{{ moneyFormat($amount) }}</td>
                            <td>{{ moneyFormat($item->acompte).' | '.moneyFormat($item->sanction) }}</td>
                            <td>{{ moneyFormat($amount >= 1000000 ? $tax : 0) }}</td>
                            <td>{{ moneyFormat($amount >= 1000000 ? ($amount-$tax-$item->acompte-$item->sanction) : $amount-$item->acompte-$item->sanction) }}</td>
                            <td>{{ ($item->rib ?? __('lang.no_rib')) .' * '. ($item->bank ?? __('lang.no_bank')) }}</td>
                            <td>
                                @if(!$item->payments->count())
                                <div class="form-check form-check-success">
                                    <input class="form-check-input makePay" id="pay{{ $item->id }}" type="checkbox" value="{{ $item->id }}">
                                    <label class="form-check-label" for="pay{{ $item->id }}">@lang('lang.pay')</label>
                                </div>
                                @else
                                @php( $payees += 1 )
                                <a href="{{ route('prints.receipt', [$item->id, $month_id]) }}"><span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class='bx bxs-printer me-1'></i>@lang('lang.soldout')</span></a>
                                @endif
                            </td>
                        </tr>
                        @php( $sum += $amount-$tax )
                        @php( $salaries += $item->salary )
                        @php( $primes += $item->prime )
                        @php( $taxs += $amount >= 1000000 ? $tax : 0 )
                        @php( $sanctions += $item->acompte + $item->sanction )
                        @php( $amounts += $amount )
                        <input type="hidden" id="amount{{ $item->id }}" value="{{ $amount }}">
                        <input type="hidden" id="salary{{ $item->id }}" value="{{ $item->salary }}">
                        <input type="hidden" id="prime{{ $item->id }}" value="{{ $item->prime }}">
                        <input type="hidden" id="tax{{ $item->id }}" value="{{ $amount >= 1000000 ? $tax : 0 }}">
                        @endforeach
                    </tbody>
                    <tfoot class="text-uppercase">
                        <tr>
                            <td class="bg-dark"></td>
                            <th>TOTAL : {{ $employees->count() }} @lang('lang.employee', ['param'=>'s']) | {{ $payees }} @lang('lang.payee', ['param'=>'s'])</th>
                            <th>TOTAL : {{ moneyFormat($salaries) }}</th>
                            <th>TOTAL : {{ moneyFormat($primes) }}</th>
                            <th>TOTAL : {{ moneyFormat($amounts) }}</th>
                            <th>TOTAL : {{ moneyFormat($sanctions) }}</th>
                            <th>TOTAL : {{ moneyFormat($taxs) }}</th>
                            <th>TOTAL : {{ moneyFormat($sum) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>     
    </div> 
</div>
<script>
    $('.makePay').each(function () {
        $(this).on('click', function () {
            let employee_id = $(this).val(),
                amount = $('#amount'+employee_id).val(),
                prime = $('#prime'+employee_id).val(),
                salary = $('#salary'+employee_id).val(),
                tax = $('#tax'+employee_id).val();

            let data = {'employee_id':employee_id, 'amount':amount, 'salary':salary, 'tax':tax, 'prime':prime, 'month_id':"{{ $month_id }}", '_token':"{{ csrf_token() }}", 'year_id':"{{ date('Y') }}"}
            $.ajax({type: "POST",
                url: "{{ route('payments.store') }}",
                data: data,
                success: (success) => {
                    notify(success.message)
                    setTimeout(window.location.reload(), 60000);
                },
                error: (error) => console.log(error)
            });
        })
    })
</script>