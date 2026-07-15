<x-admin-layout>

<div class="col">
    <div class="d-none d-sm-flex align-items-center">
        <h6 class="mb-0 text-uppercase">@lang('lang.employee_management')</h6>
        <div class="ms-auto">
            <a class="btn btn-sm btn-danger" href="{{ route('prints.operations.report') }}" target="_blank"><i class="bx bx-printer"></i> @lang('lang.download') PDF RH</a>
            @if(isRightAccess([1, 3]))
            <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#employee-add"><i class="bx bx-user-plus"></i> @lang('lang.new_employee')</a>
            @endif
        </div>
    </div>
    <hr/>
    <div class="card border-dark border-bottom border-3">
        <div class="card-body">
            <ul class="nav nav-tabs nav-default" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-group font-18 me-1'></i>
                            </div>
                            <div class="tab-title">@lang('lang.employee', ['param'=>'s'])</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#affectation" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-user-check font-18 me-1'></i>
                            </div>
                            <div class="tab-title">@lang('lang.affectation', ['param'=>'s'])</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#dotation" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-user-plus font-18 me-1'></i>
                            </div>
                            <div class="tab-title">@lang('lang.dotation', ['param'=>'s'])</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#leave" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-user-minus font-18 me-1'></i>
                            </div>
                            <div class="tab-title">@lang('lang.leaf', ['param'=>'s'])</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#suspension" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-user-minus font-18 me-1'></i>
                            </div>
                            <div class="tab-title">@lang('lang.suspension', ['param'=>'s'])</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#contracts" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-file font-18 me-1'></i>
                            </div>
                            <div class="tab-title">@lang('lang.contract', ['param' => 's'])</div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="successhome" role="tabpanel">
                    <div class="row">
                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="input-group input-group mb-3"> <span class="input-group-text" id="inputGroup-sizing"><i class="bx bx-search-alt"></i></span>
                				<input type="text" class="form-control" id="searchKey" aria-label="Sizing example input" aria-describedby="inputGroup-sizing" placeholder="@lang('lang.search')">
                			</div>
                        </div>
                    </div>
                    <div class="row search-result"></div>
                    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4" id="no-search">
                        @foreach ($employees->items() as $item)
                        <div class="col d-flex">
                            <div class="card border-bottom border-dark radius-15 h-100 w-100">
                                <div class="card-body p-3 text-center d-flex flex-column align-items-center">
                                    <img src="{{ asset($item->photo ?? 'images/avatar.png') }}" width="110" height="110" class="rounded-circle shadow" alt="">
                                    <h6 class="mb-0 mt-2">{{ $item->firstname." ".$item->name }}</h6>
                                    <p class="mb-1">{{ $item->position." ".$item->matricule }}</p>
                                    <div class="list-inline mb-2"> 
                                        <a href="tel:{{ $item->phone }}" class="list-inline-item text-dark"><i class="bx bx-phone"></i> {{ $item->phone }}</a>
                                        @if(isRightAccess([1]))
                                        <a href="{{ route('employees.notaffected', $item->id) }}" onclick="if(!confirm('Confirmez-Vous cette suppression')) return false" class="list-inline-item text-danger"><i class="bx bx-trash"></i></a>
                                        @endif
                                    </div>
                                    <div class="d-grid mt-auto w-100">
                                        @if(isRightAccess([1, 2]))
                                        <a href="{{ route('employees.show', $item->id) }}" class="btn btn-outline-dark radius-15">@lang('lang.see_more')</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <hr class="w-100">
                        <x-pagination :paginator="$employees" :count="$count" />
                    </div>
                </div>

                <div class="tab-pane fade" id="affectation" role="tabpanel">
                    @if(isRightAccess([1]))
                    <a class="btn btn-sm btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#affectation-add"><i class="bx bx-user-plus"></i> @lang('lang.new_affectation')</a>
                    <hr/>
                    @endif
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <th>@lang('lang.date')</th>
                                        <th>@lang('lang.employee', ['param'=>''])</th>
                                        <th>@lang('lang.customer', ['param'=>''])</th>
                                        <th>@lang('lang.price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($affectations as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->employee->firstname." ".$item->employee->name." | ".$item->employee->position." | ".$item->employee->phone }}</td>
                                        <td>{{ $item->customer->name." | ".$item->customer->responsible." | ".$item->customer->phone }}</td>
                                        <td>{{ moneyFormat($item->price) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>     
                </div> 

                <div class="tab-pane fade" id="dotation" role="tabpanel">
                    @if(isRightAccess([1, 5]))
                    <a class="btn btn-sm btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#dotation-add"><i class="bx bx-user-plus"></i> @lang('lang.new_dotation')</a>
                    <hr/>
                    @endif
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <th>@lang('lang.employee', ['param'=>''])</th>
                                        <th>@lang('lang.equipment', ['param'=>''])</th>
                                        <th>@lang('lang.qty')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dotations as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->employee?->firstname." ".$item->employee?->name." | ".$item->employee?->position." | ".$item->employee?->phone }}</td>
                                        <td>{{ $item->equipment?->name }}</td>
                                        <td>{{ $item->qty." ".$item->equipment?->unit }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="leave" role="tabpanel">
                    @if(isRightAccess([1, 3]))
                    <a class="btn btn-sm btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#leaf-add"><i class="bx bx-user-plus"></i> @lang('lang.new_leaf')</a>
                    <hr/>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <th>@lang('lang.date')</th>
                                    <th>@lang('lang.employee', ['param'=>''])</th>
                                    <th>@lang('lang.begin')</th>
                                    <th>@lang('lang.end')</th>
                                    <th>@lang('lang.reason')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaves as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->employee->firstname." ".$item->employee->name." | ".$item->employee->position." | ".$item->employee->phone }}</td>
                                    <td>{{ $item->begin }}</td>
                                    <td>{{ $item->end }}</td>
                                    <td>{{ $item->reason }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="suspension" role="tabpanel">
                    @if(isRightAccess([1, 3]))
                    <a class="btn btn-sm btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#suspension-add"><i class="bx bx-user-plus"></i> @lang('lang.new_suspension')</a>
                    <hr/>
                    @endif
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <th>@lang('lang.date')</th>
                                        <th>@lang('lang.employee', ['param'=>''])</th>
                                        <th>@lang('lang.duration')</th>
                                        <th>@lang('lang.reason')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suspensions as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->employee->firstname." ".$item->employee->name." | ".$item->employee->position." | ".$item->employee->phone }}</td>
                                        <td>{{ $item->duration." ".$item->unit }}</td>
                                        <td>{{ $item->reason }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>      
                </div>

                <div class="tab-pane fade" id="contracts" role="tabpanel">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <button class="btn btn-sm btn-outline-dark contract-filter" data-filter="all">Tous</button>
                        <button class="btn btn-sm btn-outline-primary contract-filter" data-filter="CDD">CDD</button>
                        <button class="btn btn-sm btn-outline-success contract-filter" data-filter="CDI">CDI</button>
                        <button class="btn btn-sm btn-outline-warning contract-filter" data-filter="stagiaire">Stagiaires</button>
                        <button class="btn btn-sm btn-outline-danger contract-filter" data-filter="expired">Expirés</button>
                        <a class="btn btn-sm btn-dark" href="{{ route('prints.operations.report') }}" target="_blank">
                            <i class="bx bx-download"></i> @lang('lang.download') PDF
                        </a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('prints.operations.report', ['contract_type' => 'CDD']) }}" target="_blank">
                            PDF CDD
                        </a>
                        <a class="btn btn-sm btn-outline-success" href="{{ route('prints.operations.report', ['contract_type' => 'CDI']) }}" target="_blank">
                            PDF CDI
                        </a>
                    </div>

                    <div class="alert alert-light border mb-3">
                        <strong>Total:</strong> {{ $contracts->count() }} |
                        <strong>CDD:</strong> {{ $cddContracts->count() }} |
                        <strong>CDI:</strong> {{ $cdiContracts->count() }} |
                        <strong>Stagiaires:</strong> {{ $internContracts->count() }} |
                        <strong>Expirés:</strong> {{ $expiredContracts->count() }}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('lang.employee', ['param'=>''])</th>
                                    <th>@lang('lang.contract_type')</th>
                                    <th>@lang('lang.start_date')</th>
                                    <th>@lang('lang.end_date')</th>
                                    <th>@lang('lang.status')</th>
                                    <th>@lang('lang.action', ['param' => ''])</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contracts as $item)
                                @php
                                    $isExpired = !empty($item->contract_end_at) && \Carbon\Carbon::parse($item->contract_end_at)->lt(\Carbon\Carbon::today());
                                    $contractType = stripos((string) $item->contract, 'stagiaire') !== false ? 'stagiaire' : strtoupper((string) $item->contract);
                                @endphp
                                <tr class="contract-row"
                                    data-contract="{{ $contractType }}"
                                    data-expired="{{ $isExpired ? '1' : '0' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->firstname.' '.$item->name.' | '.$item->matricule }}</td>
                                    <td>{{ $item->contract }}</td>
                                    <td>{{ !empty($item->contract_start_at) ? \Carbon\Carbon::parse($item->contract_start_at)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ !empty($item->contract_end_at) ? \Carbon\Carbon::parse($item->contract_end_at)->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($isExpired)
                                            <span class="badge bg-danger">@lang('lang.expired')</span>
                                        @else
                                            <span class="badge bg-success">@lang('lang.ongoing')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-dark" href="{{ route('prints.contract.employee', $item->id) }}" target="_blank">
                                            <i class="bx bx-download"></i> PDF
                                        </a>
                                    </td>
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

@if(isRightAccess([1, 3]))
<x-employee-add :applicants="$applicants" />
<x-suspension-add :employees="$employees" />
<x-leaf-add :employees="$employees" />
@endif

<x-dotation-add :employees="$employees" :equipments="$equipments" />

@if(isRightAccess([1]))
<x-affectation-add :employees="$employees" :customers="$customers" />
@endif

@push('js-view')
<script>
    $('#searchKey').on('keyup', function () {
        let search = $(this).val();
        if(search != '') {
            $("#no-search").hide();
            $('.search-result').load("{{ route('employees.search') }}", {'_token':"{{ csrf_token() }}", 'search':search});
        } else {
            $('.search-result').html("");
            $("#no-search").show();
        }
    })

    $('.contract-filter').on('click', function () {
        const filter = $(this).data('filter');

        $('.contract-row').each(function () {
            const contract = ($(this).data('contract') || '').toString();
            const isExpired = ($(this).data('expired') || '').toString() === '1';

            if (filter === 'all') {
                $(this).show();
                return;
            }

            if (filter === 'expired') {
                isExpired ? $(this).show() : $(this).hide();
                return;
            }

            contract === filter.toString() ? $(this).show() : $(this).hide();
        });
    });
</script>
@endpush
</x-admin-layout>
