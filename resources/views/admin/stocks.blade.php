<x-admin-layout>

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.logistic_archive')</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-danger" href="{{ route('prints.inventory.report') }}" target="_blank"><i class="bx bx-printer"></i> PDF @lang('lang.detailed_inventory')</a>
    </div>
</div>
<hr/>

<div class="row row-cols-2 row-cols-md-4 g-3 mb-3">
    <div class="col">
        <div class="card radius-15 h-100 border-dark border-bottom border-3">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['equipments'] }}</h3>
                <p class="mb-0 text-muted">@lang('lang.equipment', ['param'=>'s'])</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-15 h-100 border-success border-bottom border-3">
            <div class="card-body text-center">
                <h3 class="mb-0 text-success">{{ $stats['available'] }}</h3>
                <p class="mb-0 text-muted">@lang('lang.available_stock')</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-15 h-100 border-danger border-bottom border-3">
            <div class="card-body text-center">
                <h3 class="mb-0 text-danger">{{ $stats['depleted'] }}</h3>
                <p class="mb-0 text-muted">@lang('lang.depleted_stock')</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-15 h-100 border-dark border-bottom border-3">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['movements_month'] }}</h3>
                <p class="mb-0 text-muted">@lang('lang.stock_movement') — {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body">
        <ul class="nav nav-tabs nav-default" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#tab-movements" role="tab">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-transfer font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.stock_movement')</div>
                    </div>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-inventory" role="tab">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-package font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.detailed_inventory')</div>
                    </div>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-available" role="tab">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-check-circle font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.available_stock')</div>
                    </div>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-depleted" role="tab">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-error font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.depleted_stock')</div>
                    </div>
                </a>
            </li>
        </ul>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="tab-movements" role="tabpanel">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-12 col-md-4">
                        <select name="equipment_id" class="form-select">
                            <option value="">@lang('lang.equipment', ['param'=>'s'])</option>
                            @foreach ($equipments as $equipment)
                                <option value="{{ $equipment->id }}" {{ (string) request('equipment_id') === (string) $equipment->id ? 'selected' : '' }}>{{ $equipment->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <select name="reason" class="form-select">
                            <option value="">@lang('lang.reason')</option>
                            @foreach ($reasons as $value => $label)
                                <option value="{{ $value }}" {{ request('reason') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4 d-flex gap-2">
                        <button class="btn btn-dark"><i class="bx bx-filter-alt"></i> @lang('lang.search')</button>
                        <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">@lang('lang.reset')</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <td>#</td>
                                <th>@lang('lang.date')</th>
                                <th>@lang('lang.equipment', ['param'=>''])</th>
                                <th>@lang('lang.stock_direction')</th>
                                <th>@lang('lang.reason')</th>
                                <th>@lang('lang.qty')</th>
                                <th>@lang('lang.stock_after')</th>
                                <th>@lang('lang.employee', ['param'=>''])</th>
                                <th>@lang('lang.notice')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($movements as $item)
                            <tr @class(['table-danger' => $item->reason === \App\Models\StockMovement::REASON_DEPLETION])>
                                <td>{{ $movements->firstItem() + $loop->index }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                <td>{{ $item->equipment?->name }}</td>
                                <td>
                                    @if($item->is_inbound)
                                        <span class="badge bg-success"><i class="bx bx-down-arrow-alt"></i> @lang('lang.stock_in')</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bx bx-up-arrow-alt"></i> @lang('lang.stock_out')</span>
                                    @endif
                                </td>
                                <td>{{ $item->reason_label }}</td>
                                <td>{{ $item->quantity }} {{ $item->equipment?->unit }}</td>
                                <td>{{ $item->stock_after }} {{ $item->equipment?->unit }}</td>
                                <td>{{ $item->employee ? $item->employee->firstname.' '.$item->employee->name : '—' }}</td>
                                <td class="small text-muted">{{ $item->note }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center">@lang('lang.no_stock_movement')</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-pagination :paginator="$movements" />
            </div>

            <div class="tab-pane fade" id="tab-inventory" role="tabpanel">
                @php $grandValue = 0; @endphp
                <div class="table-responsive">
                    <table class="table table-bordered w-100 align-middle">
                        <thead>
                            <tr class="table-dark">
                                <th>@lang('lang.equipment', ['param'=>''])</th>
                                <th class="text-end">@lang('lang.price')</th>
                                <th class="text-end">@lang('lang.total_qty')</th>
                                <th class="text-end">@lang('lang.allocated_qty')</th>
                                <th class="text-end">@lang('lang.deteriorated_qty')</th>
                                <th class="text-end">@lang('lang.available')</th>
                                <th class="text-end">@lang('lang.stock_value')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inventory as $categoryName => $items)
                                @php
                                    $catQty = 0; $catAllocated = 0; $catDeteriorated = 0; $catAvailable = 0; $catValue = 0;
                                @endphp
                                <tr class="table-secondary">
                                    <td colspan="7" class="fw-bold text-uppercase">
                                        <i class="bx bx-bookmark"></i> {{ $categoryName }} <span class="text-muted">({{ $items->count() }})</span>
                                    </td>
                                </tr>
                                @foreach ($items as $equipment)
                                    @php
                                        $allocated = $equipment->dotations->sum('qty');
                                        $deteriorated = $equipment->deteriorated_qty ?? 0;
                                        $available = $equipment->available_qty;
                                        $value = $available * $equipment->price;
                                        $catQty += $equipment->qty; $catAllocated += $allocated;
                                        $catDeteriorated += $deteriorated; $catAvailable += $available; $catValue += $value;
                                        $grandValue += $value;
                                    @endphp
                                    <tr @class(['table-warning' => $available <= 0])>
                                        <td>{{ $equipment->name }}</td>
                                        <td class="text-end">{{ moneyFormat($equipment->price) }}</td>
                                        <td class="text-end">{{ $equipment->qty }} {{ $equipment->unit }}</td>
                                        <td class="text-end">{{ $allocated }} {{ $equipment->unit }}</td>
                                        <td class="text-end">{{ $deteriorated }} {{ $equipment->unit }}</td>
                                        <td class="text-end {{ $available <= 0 ? 'text-danger fw-semibold' : '' }}">{{ $available }} {{ $equipment->unit }}</td>
                                        <td class="text-end">{{ moneyFormat($value) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="fw-semibold">
                                    <td class="text-end">@lang('lang.subtotal')</td>
                                    <td></td>
                                    <td class="text-end">{{ $catQty }}</td>
                                    <td class="text-end">{{ $catAllocated }}</td>
                                    <td class="text-end">{{ $catDeteriorated }}</td>
                                    <td class="text-end">{{ $catAvailable }}</td>
                                    <td class="text-end">{{ moneyFormat($catValue) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">@lang('lang.no_stock_movement')</td></tr>
                            @endforelse
                        </tbody>
                        @if($inventory->isNotEmpty())
                        <tfoot>
                            <tr class="table-dark fw-bold">
                                <td colspan="6" class="text-end">@lang('lang.stock_value') @lang('lang.total')</td>
                                <td class="text-end">{{ moneyFormat($grandValue) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-available" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered w-100">
                        <thead class="table-light">
                            <tr>
                                <td>#</td>
                                <th>@lang('lang.category', ['param'=>''])</th>
                                <th>@lang('lang.equipment', ['param'=>''])</th>
                                <th class="text-end">@lang('lang.total_qty')</th>
                                <th class="text-end">@lang('lang.allocated_qty')</th>
                                <th class="text-end">@lang('lang.deteriorated_qty')</th>
                                <th class="text-end">@lang('lang.available')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($availableEquipments as $equipment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $equipment->category?->name ?? '—' }}</td>
                                <td>{{ $equipment->name }}</td>
                                <td class="text-end">{{ $equipment->qty." ".$equipment->unit }}</td>
                                <td class="text-end">{{ $equipment->dotations->sum('qty')." ".$equipment->unit }}</td>
                                <td class="text-end">{{ ($equipment->deteriorated_qty ?? 0)." ".$equipment->unit }}</td>
                                <td class="text-end text-success fw-semibold">{{ $equipment->available_qty." ".$equipment->unit }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center">@lang('lang.no_available_stock')</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-depleted" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <td>#</td>
                                <th>@lang('lang.category', ['param'=>''])</th>
                                <th>@lang('lang.equipment', ['param'=>''])</th>
                                <th>@lang('lang.qty')</th>
                                <th>@lang('lang.available')</th>
                                <th>@lang('lang.deteriorated_qty')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($depletedEquipments as $equipment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $equipment->category?->name }}</td>
                                <td>{{ $equipment->name }}</td>
                                <td>{{ $equipment->qty." ".$equipment->unit }}</td>
                                <td class="text-danger">{{ $equipment->available_qty." ".$equipment->unit }}</td>
                                <td>{{ ($equipment->deteriorated_qty ?? 0)." ".$equipment->unit }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center">@lang('lang.no_depleted_stock')</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
