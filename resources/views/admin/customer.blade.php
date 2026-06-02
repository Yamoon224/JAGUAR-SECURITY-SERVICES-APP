<x-admin-layout>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">@lang('lang.profil') @lang('lang.customer', ['param'=>''])</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('customers.index') }}" title="La liste des @lang('lang.customer', ['param'=>'s'])"><i class="bx bx-table"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $customer->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="row g-0">
        <div class="col-md-12">
            <div class="card-body">
                <div id="invoice">
                    <div class="invoice">
                        <div>
                            <header>
                                <div class="row">
                                    <div class="col">
                                        <a href="javascript:;">
                                            <img src="{{ asset('images/logo-icon.png') }}" width="80" alt="" />
                                        </a>
                                    </div>
                                    <div class="col company-details">
                                        <h2 class="name">
                                            <a class="text-dark">JSS SARL</a>
                                        </h2>
                                        <div>Aéroport Ahmed Sékou Touré, Gbessia</div>
                                        <div>+224 625 12 32 32 | jaguar28jss@gmail.com</div>
                                        <div>@lang('lang.social_capital') : {{ moneyFormat(10000000) }}</div>
                                    </div>
                                </div>
                            </header>
                            <main>
                                <div class="row contacts">
                                    <div class="col invoice-to">
                                        <div class="text-gray-light text-uppercase">@lang('lang.invoice_to'):</div>
                                        <h2 class="to">{{ $customer->name }}</h2>
                                        <div class="address">{{ $customer->phone.", ".$customer->address }}</div>
                                        <div class="email"><a href="mailto:{{ $customer->email }}" class="text-dark">{{ $customer->email }}</a></div>
                                    </div>
                                    <div class="col invoice-details">
                                        <div class="date">@lang('lang.year', ['param'=>'']): {{ date('Y') }}</div>
                                        <div class="date text-uppercase">@lang('date', ['param'=>'']): {{ date('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </main>
                        </div>
                    </div>
                </div> 
                <table class="table table-striped table-bordered w-100">
                    <thead class="bg-dark text-white text-uppercase">
                        <tr>
                            <th>@lang('lang.month')</th>
                            <th>@lang('lang.bill', ['param'=>''])</th>
                            <th>@lang('lang.amount') HT</th>
                            <th>@lang('lang.tva')</th>
                            <th>@lang('lang.ttc')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php( $sum = 0 )
                        @php( $begin = Carbon\Carbon::parse($customer->start_date) )
                        @php( $end = Carbon\Carbon::parse($customer->end_date) )
                        @while ($begin->lte($end))
                        <tr>
                            <td>@lang('lang.'.getMonthName((int)$begin->format('m'))) / {{ $begin->format('Y') }}</td>
                            <td><a href="{{ route('prints.bill', [$customer->id, (int)$begin->format('Y'), (int)$begin->format('m')]) }}" class="badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3"><i class='bx bx-cloud-download me-1'></i>@lang('lang.download')</a></td>
                            <td>{{ moneyFormat(getBillAmount($customer->id, $begin->format('m'), $begin->format('Y'))) }}</td>
                            <td>{{ moneyFormat(0) }}</td>
                            <td>{{ moneyFormat(getBillAmount($customer->id, $begin->format('m'), $begin->format('Y'))+0) }}</td>
                        </tr>
                        @php( $sum += getBillAmount($customer->id, $begin->format('m'), $begin->format('Y')) )
                        @php( $begin->addMonth() )
                        @endwhile
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-uppercase">@lang('lang.subtotal')</td>
                            <td>{{ moneyFormat($sum) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-uppercase">@lang('lang.tva')</td>
                            <td>{{ moneyFormat(0) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-uppercase">@lang('lang.total')</td>
                            <td>{{ moneyFormat($sum) }}</td>
                        </tr>
                    </tfoot>
                </table>                               
            </div>
        </div>
    </div>
</div>
</x-admin-layout>

