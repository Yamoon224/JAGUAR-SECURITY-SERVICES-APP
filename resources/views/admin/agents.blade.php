<x-admin-layout>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12">
                        <a class="btn btn-dark mb-3 mb-lg-0"><i class='bx bx-blanket'></i>@lang('lang.employee', ['param'=>'s'])</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card border-dark border-bottom border-3">
    <div class="card-body">
        <ul class="nav nav-tabs nav-default" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-calendar-check font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.agent', ['param'=>'s'])</div>
                    </div>
                </a>
            </li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="table-customer" role="tabpanel">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <th>@lang('lang.employee', ['param'=>''])</th>
                                    <th>@lang('lang.salary')</th>
                                    <th>@lang('lang.prime')</th>
                                    <th>@lang('lang.amount')</th>
                                    <th>@lang('lang.tax') (13% @lang('lang.amount'))</th>
                                    <th>@lang('lang.pay_net') @lang('lang.ttc')</th>
                                    <th>@lang('lang.rib') * @lang('lang.bank_name')</th>
                                    <th>@lang('lang.affectation', ['param'=>''])</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agents as $item)
                                @php( $amount = $item->salary+$item->prime )
                                @php( $tax = $amount*0.13 )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->firstname." ".$item->name." * ".$item->phone." * ".$item->matricule }}</td>
                                    <td>{{ moneyFormat($item->salary) }}</td>
                                    <td>{{ moneyFormat($item->prime) }}</td>
                                    <td>{{ moneyFormat($amount) }}</td>
                                    <td>{{ moneyFormat($amount >= 1000000 ? $tax : 0) }}</td>
                                    <td>{{ moneyFormat($amount >= 1000000 ? ($amount-$tax) : $amount) }}</td>
                                    <td>{{ ($item->rib ?? __('lang.no_rib')) .' * '. ($item->bank ?? __('lang.no_bank')) }}</td>
                                    <td>{{ !is_null($item->affectations->first()) ? $item->affectations->first()->customer->name : __('lang.no_affectation') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>     
            </div> 
        </div>
    </div>
</div>
</x-admin-layout>
