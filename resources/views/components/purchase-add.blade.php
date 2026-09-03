@php
    // Les erreurs de validation renvoient sur la page d'origine : on ne
    // repioche l'ancienne saisie que si elle vient bien de ce formulaire.
    $isRetry = old('_form') === 'purchase-add';
    $selected = $isRetry ? old('equipment_id') : null;
@endphp
<div class="modal fade" id="purchase-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-cart-add"></i> @lang('lang.new_purchase')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('purchases.store') }}">
                @csrf
                <input type="hidden" name="_form" value="purchase-add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-customize"></i> @lang('lang.equipment', ['param'=>'']) <span class="text-danger">*</span></label>
                        <select class="form-select purchase-equipment @error('equipment_id') is-invalid @enderror" name="equipment_id" required>
                            <option value="">@lang('lang.equipment', ['param'=>'']) *</option>
                            <option value="__new__" {{ $selected === '__new__' ? 'selected' : '' }}>➕ @lang('lang.new_equipment')</option>
                            @foreach ($equipments as $item)
                                <option value="{{ $item->id }}" data-available="{{ $item->available_qty }}" data-unit="{{ $item->unit }}" {{ (string) $selected === (string) $item->id ? 'selected' : '' }}>
                                    {{ $item->name." | ".$item->available_qty." ".$item->unit." en stock" }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipment_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="purchase-new-equipment {{ $selected === '__new__' ? '' : 'd-none' }}">
                        <div class="row g-2 mb-3">
                            <div class="col-8">
                                <label class="form-label mb-1 fw-semibold">@lang('lang.name') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control purchase-new-name @error('new_name') is-invalid @enderror" name="new_name" placeholder="@lang('lang.name')" value="{{ $isRetry ? old('new_name') : '' }}">
                                @error('new_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-4">
                                <label class="form-label mb-1 fw-semibold">@lang('lang.unit')</label>
                                <input type="text" class="form-control purchase-new-unit @error('new_unit') is-invalid @enderror" name="new_unit" placeholder="pcs, L…" value="{{ $isRetry ? old('new_unit') : '' }}">
                                @error('new_unit')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-package"></i> @lang('lang.qty_to_supply') <span class="text-danger">*</span></label>
                        <div class="position-relative input-icon">
                            <input type="number" class="form-control purchase-qty @error('qty') is-invalid @enderror" name="qty" placeholder="@lang('lang.qty') *" value="{{ $isRetry ? old('qty') : '' }}" min="0.01" step="0.01" required>
                            <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-plus'></i></span>
                        </div>
                        @error('qty')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <small class="text-muted purchase-stock-hint d-block mt-1" data-mode="new"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-money"></i> @lang('lang.price') <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" placeholder="@lang('lang.price') *" value="{{ $isRetry ? old('price') : '' }}" min="0" step="0.01" required>
                        @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-1">
                        <label class="form-label mb-1">@lang('lang.purchase_date')</label>
                        <input type="date" class="form-control @error('purchased_at') is-invalid @enderror" name="purchased_at" value="{{ ($isRetry ? old('purchased_at') : null) ?? date('Y-m-d') }}" required>
                        @error('purchased_at')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success"><i class="bx bx-cart-add"></i> @lang('lang.submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($isRetry && $errors->any())
@push('js-view')
<script>
    // Erreur de validation de l'achat : on rouvre le formulaire sur la saisie.
    jQuery(function ($) { new bootstrap.Modal(document.getElementById('purchase-add')).show(); });
</script>
@endpush
@endif
