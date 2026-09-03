<x-admin-layout>

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.equipment', ['param'=>'s'])</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#purchase-add"><i class="bx bx-cart-add"></i> @lang('lang.new_purchase')</a>
        <a class="btn btn-sm btn-danger" href="{{ route('prints.equipments.report') }}" target="_blank"><i class="bx bx-printer"></i> PDF @lang('lang.equipment', ['param'=>'s'])</a>
    </div>
</div>
<hr/>
<div class="card border-dark border-bottom border-3">
    <div class="card-body">
        <ul class="nav nav-tabs nav-default" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-customize font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.equipment', ['param'=>'s'])</div>
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
                                    <th>@lang('lang.name')</th>
                                    <th class="text-end">@lang('lang.price')</th>
                                    <th class="text-end">@lang('lang.qty')</th>
                                    <th class="text-end">@lang('lang.available')</th>
                                    <th class="text-end">@lang('lang.action', ['param'=>'s'])</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($equipments as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-end">{{ moneyFormat($item->price) }}</td>
                                    <td class="text-end">{{ $item->qty." ".$item->unit }}</td>
                                    <td class="text-end {{ $item->available_qty <= 0 ? 'text-danger fw-semibold' : '' }}">{{ $item->available_qty." ".$item->unit }}</td>
                                    <td class="text-end text-nowrap">
                                        <a data-bs-toggle="modal" data-bs-target="#equipment{{ $item->id }}" class="btn btn-sm btn-outline-primary" title="@lang('lang.edit')">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <x-equipment-edit :equipment="$item" />

                                        <form action="{{ route('equipments.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="@lang('lang.delete')" onclick="return confirm('Confirmez-Vous cette suppression')"><i class="bx bx-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">Aucun équipement — ajoutez-en un via un approvisionnement.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-purchase-add />

@push('js-view')
@include('admin.partials.purchase-form-script')
@endpush
</x-admin-layout>
