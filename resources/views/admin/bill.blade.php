<ul class="nav nav-tabs nav-default" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
            <div class="d-flex align-items-center">
                <div class="tab-icon"><i class='bx bx-calendar-check font-18 me-1'></i></div>
                <div class="tab-title">@lang('lang.month', ['param'=>'']) : {{ $month }}</div>
            </div>
        </a>
    </li>
</ul>
<div class="tab-content py-3">
    <div class="tab-pane fade show active" id="table-customer" role="tabpanel">
        <div class="col">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <td>#</td>
                            <th>@lang('lang.customer', ['param'=>''])</th>
                            <th>@lang('lang.affectation', array('param'=>'s'))</th>
                            <th>@lang('lang.amount')</th>
                            <th>@lang('lang.discount')</th>
                            <th>@lang('lang.arrears')</th>
                            <th>@lang('lang.contract_amount')</th>
                            <th>@lang('lang.action', ['param'=>'s'])</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php( $sum = 0 )
                        @php( $affectations = 0 )
                        @foreach ($customers as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name." | ".$item->phone." | ".($item->email ?? 'Email')." | ".$item->responsible }}</td>
                            <td>{{ $item->affectations->count() }} @lang('lang.employee', ['param'=>'s'])</td>
                            <td>{{ moneyFormat($item->affectations->sum('price') - ($item->bills->count() ? $item->bills->first()->discount : 0) ) }}</td>
                            <td>
                                <input type="hidden" id="net{{ $item->id }}" class="form-control" value="{{ $item->affectations->sum('price') }}">
                                <input type="number" id="discount{{ $item->id }}" list="{{ $item->bills->count() ? $item->bills->first()->id : 0 }}"  class="form-control form-control-sm discount" value="{{ $item->bills->count() ? $item->bills->first()->discount : 0 }}">
                            </td>
                            <td id="arrears{{ $item->id }}">
                                <input type="number" id="arrears{{ $item->id }}" list="{{ $item->bills->count() ? $item->bills->first()->id : 0 }}"  class="form-control form-control-sm arrears" value="{{ $item->bills->count() ? $item->bills->first()->arrears : 0 }}">
                            </td>
                            <td id="amount{{ $item->id }}">{{ moneyFormat($item->amount) }}</td>
                            <td>
                                @if(!$item->bills->count())
                                <div class="form-check form-check-success">
                                    <input class="form-check-input makePay" type="checkbox" value="{{ $item->id }}" id="makePay{{ $item->id }}">
                                    <label class="form-check-label" for="makePay{{ $item->id }}">@lang('lang.pay')</label>
                                </div>
                                @else
                                <a href="{{ route('prints.bill', [$item->id, date('Y'), $month_id, 1]) }}"><span class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class='bx bxs-printer me-1'></i>@lang('lang.receipt')</span></a>
                                @endif
                            </td>
                        </tr>
                        @php( $affectations += $item->affectations->count() )
                        @php( $sum += $item->affectations->sum('price') )
                        @endforeach
                    </tbody>
                    <tfoot class="text-uppercase">
                        <tr>
                            <td class="bg-dark"></td>
                            <th>TOTAL : {{ $customers->count() }} @lang('lang.customer', ['param'=>'s'])</th>
                            <th>TOTAL : {{ $affectations }} @lang('lang.employee', ['param'=>'s'])</th>
                            <th>TOTAL : {{ moneyFormat($sum) }}</th>
                            <th>TOTAL : {{ moneyFormat(0) }}</th>
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
    var customerId = (target) => {
        var nombres = target.match(/\d+/g);
        if (nombres) 
            nombres = nombres.map(Number); 
        return nombres;
    }
    $('.makePay').each(function () {
        $(this).on('click', function () {
            let customer_id = $(this).val(),
                amount = $('#amount'+customer_id).text(),
                net = $('#net'+customer_id).val(),
                arrears = $('#arrears'+customer_id).val(),
                discount = $('#discount'+customer_id).val();

            let data = {'customer_id':customer_id, 'arrears':arrears, 'net':net, 'amount':amount, 'discount':discount, 'month_id':"{{ $month_id }}", '_token':"{{ csrf_token() }}", 'year_id':"{{ date('Y') }}"}
            $.ajax({type: "POST",
                url: "{{ route('bills.store') }}",
                data: data,
                success: (success) => {
                    notify(success.message) 
                    if(success?.redirect) {
                        window.location.href = success.redirect;
                    }else {
                        setTimeout(location.reload(), 5000);
                    }
                },
                error: (error) => console.log(error)
            });
        })
    })
    $('.discount').each(function () {
        $(this).on('change', function () {
            let discount = $(this).val(),
                id = $(this).attr('list');
                
            let customer_id = customerId($(this).prop('id'))[0];
            let data = {
                'discount':discount, '_token':"{{ csrf_token() }}", '_method':"PUT", 'customer_id':customer_id,
                'id':id, 'net':$('#net'+customer_id).val(), 'amount':$('#amount'+customer_id).val(), 
                'month_id':"{{ $month_id }}", 'year_id':"{{ date('Y') }}", 'arrears':$('#arrears'+customer_id).val()
            }
            $.ajax({type: "POST",
                url: "https://admin.jss-gn.com/admin/bills/"+id,
                data: data,
                success: (success) => {
                    notify(success.message);
                    if(success?.redirect) {
                        window.location.href = success.redirect;
                    }else {
                        setTimeout(location.reload(), 5000);
                    }
                },
                error: (error) => console.log(error)
            });
        })
    })
    $('.arrears').each(function () {
        $(this).on('change', function () {
            let arrears = $(this).val(),
                id = $(this).attr('list');
                
            let customer_id = customerId($(this).prop('id'))[0];
            let data = {
                'arrears':arrears, '_token':"{{ csrf_token() }}", '_method':"PUT", 'id':id, 
                'net':$('#net'+customer_id).val(), 'amount':$('#amount'+customer_id).val(), 'discount':$('#discount'+customer_id).val(), 
                'month_id':"{{ $month_id }}", 'year_id':"{{ date('Y') }}", 'customer_id':customer_id
            }
            $.ajax({type: "POST",
                url: "https://admin.jss-gn.com/admin/bills/"+id,
                data: data,
                success: (success) => {
                    notify(success.message);
                    if(success?.redirect) {
                        window.location.href = success.redirect;
                    }else {
                        setTimeout(location.reload(), 5000);
                    }
                },
                error: (error) => console.log(error)
            });
        })
    })
</script>