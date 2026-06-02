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
            @if(isRightAccess([1]))
            <h6 class="mb-0 text-uppercase">@lang('lang.human_resource')</h6>
            <hr/>
            <div class="row row-cols-1 row-cols-md-3 row-cols-xl-6">
                <div class="col">
                    <div class="card radius-10 border-info border-bottom border-3">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="widgets-icons rounded-circle mx-auto bg-light-info text-info mb-3"><i class='bx bx-briefcase'></i></div>
                                <h6 class="my-1">{{ $employees->where('contract', 'CDI')->count() }}</h6>
                                <p class="mb-0 text-secondary text-sm" style="font-size: 10px">@lang('lang.employee', ['param'=>'s']) CDI</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-success border-bottom border-3">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="widgets-icons rounded-circle mx-auto bg-light-success text-success mb-3"><i class='bx bx-briefcase'></i></div>
                                <h6 class="my-1">{{ $employees->where('contract', 'CDD')->count() }}</h6>
                                <p class="mb-0 text-secondary text-sm" style="font-size: 10px">@lang('lang.employee', ['param'=>'s']) CDD</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-primary border-bottom border-3">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="widgets-icons rounded-circle mx-auto bg-light-primary text-primary mb-3"><i class='bx bx-briefcase-alt'></i></div>
                                <h6 class="my-1">{{ $employees->where('contract', 'Vacataire | Stagiaire')->count() }}</h6>
                                <p class="mb-0 text-secondary text-sm" style="font-size: 10px">Vacataire(s)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-danger border-bottom border-3">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="widgets-icons rounded-circle mx-auto bg-light-danger text-danger mb-3"><i class='bx bx-female'></i></div>
                                <h6 class="my-1">{{ $employees->where('gender', 'Femme')->count() }}</h6>
                                <p class="mb-0 text-secondary text-sm" style="font-size: 10px">Femme(s)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-success border-bottom border-3">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="widgets-icons rounded-circle mx-auto bg-light-success text-success mb-3"><i class='bx bx-user-check'></i></div>
                                <h6 class="my-1">{{ $employees->where('familystatus', 'Marié(e)')->count() }}</h6>
                                <p class="mb-0 text-secondary text-sm" style="font-size: 10px">Marié(es)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-warning border-bottom border-3">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="widgets-icons rounded-circle mx-auto bg-light-warning text-warning mb-3"><i class='bx bx-user-x'></i></div>
                                <h6 class="my-1">{{ $employees->where('familystatus', 'Célibataire')->count() }}</h6>
                                <p class="mb-0 text-secondary text-sm" style="font-size: 10px">Célibataire(s)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <h6 class="mb-0 text-uppercase">@lang('lang.billing') & @lang('lang.payment', ['param'=>'s'])</h6>
            <hr/>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
                <div class="col">
                    <div class="card radius-10 border-info border-bottom border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary text-sm" style="font-size: 9px">@lang('lang.bill', ['param'=>'s'])</p>
                                    <h6 class="my-1 text-uppercase">@lang('lang.total') : {{ $customers->reject( fn ($item) => $item->affectations->count() == 0 )->count() }}</h6>
                                    <p class="mb-0 font-13 text-info text-sm" style="font-size: 9px">{{ moneyFormat($customers->sum( fn ($item) => $item->affectations->count() > 0 ? $item->affectations->sum('price') : 0 )) }}</p>
                                </div>
                                <div class="widgets-icons bg-light-info text-info ms-auto"><i class="bx bxs-wallet"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-success border-bottom border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary text-sm" style="font-size: 9px">@lang('lang.paidbill', ['param'=>''])</p>
                                    <h6 class="my-1 text-uppercase">@lang('lang.total') : {{ $customers->reject( fn ($item) => $item->bills->count() == 0 )->count() }}</h6>
                                    <p class="mb-0 font-13 text-success text-sm" style="font-size: 9px">{{ moneyFormat($customers->sum( fn ($item) => $item->bills->count() > 0 ? $item->bills->sum('amount') : 0 )) }}</p>
                                </div>
                                <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-wallet'></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-primary border-bottom border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary text-sm" style="font-size: 9px">@lang('lang.contract', ['param'=>''])</p>
                                    <h6 class="my-1 text-uppercase">@lang('lang.total') : {{ $customers->count() }}</h6>
                                    <p class="mb-0 font-13 text-primary text-sm" style="font-size: 9px">{{ moneyFormat($customers->sum('amount')) }}</p>
                                </div>
                                <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-briefcase'></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-danger border-bottom border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary text-sm" style="font-size: 9px">@lang('lang.payment', ['param'=>'s'])</p>
                                    <h6 class="my-1 text-uppercase">@lang('lang.total'): {{ $employees->count() }}</h6>
                                    <p class="mb-0 font-13 text-danger text-sm" style="font-size: 9px">{{ moneyFormat($employees->sum('net')) }}</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class='bx bx-money'></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-success border-bottom border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary text-sm" style="font-size: 9px">@lang('lang.payment_soldout')</p>
                                    <h6 class="my-1 text-uppercase">@lang('lang.total'): {{ $employees->reject( fn ($item) => $item->payments->count() == 0 )->count() }}</h6>
                                    <p class="mb-0 font-13 text-success text-sm" style="font-size: 9px">{{ moneyFormat($employees->sum( fn ($item) => $item->payments->count() > 0 ? $item->payments->sum('net') : 0 )) }}</p>
                                </div>
                                <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bx-money'></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-warning border-bottom border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary text-sm" style="font-size: 9px">@lang('lang.benefit')</p>
                                    <h6 class="my-1 text-uppercase">@lang('lang.total'): {{ $employees->count()-$customers->count() }}</h6>
                                    <p class="mb-0 font-13 text-warning text-sm" style="font-size: 9px">{{ moneyFormat($customers->sum('amount')-$employees->sum('net')) }}</p>
                                </div>
                                <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class='bx bx-money'></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div id="chart6"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <h4 class="mb-0 font-weight-bold">@lang('lang.customers_top_five')</h4>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table class="table mb-0 mt-1">
                                    <tbody>
                                        @foreach ($customers->sortByDesc( fn ($itm) => $itm->affectations->sum('price') )->take(5) as $item)
                                        <tr>
                                            <td class="px-0">
                                                <div class="d-flex align-items-center">
                                                    <div><i class="bx bxs-checkbox me-2 font-22 text-warning"></i></div>
                                                    <div>{{ $item->name }}</div>
                                                </div>
                                            </td>
                                            <td>{{ $item->affectations->count() }} @lang('lang.affectation', ['param'=>'s'])</td>
                                            <td class="px-0 text-right">{{ moneyFormat($item->affectations->sum('price')) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script src="{{ asset('assets/admin/plugins/highcharts/js/highcharts.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
<x-chart :employees="$employees"></x-chart>