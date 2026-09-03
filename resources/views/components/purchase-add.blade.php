<div class="modal fade" id="purchase-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-cart-add"></i> @lang('lang.new_purchase')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('purchases.store') }}">
                @csrf
                <div class="modal-body">
                    @if ($errors->purchaseAdd->any())
                    <div class="alert alert-danger py-2 px-3">
                        <ul class="mb-0 ps-3">@foreach ($errors->purchaseAdd->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                    @endif

                    {{-- Chaque achat crée sa propre fiche équipement : nom et unité sont toujours saisis. --}}
                    <div class="row g-2 mb-3">
                        <div class="col-8">
                            <label class="form-label mb-1 fw-semibold"><i class="bx bx-customize"></i> @lang('lang.name') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="@lang('lang.name') *" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label mb-1 fw-semibold">@lang('lang.unit') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="unit" value="{{ old('unit') }}" placeholder="pcs, L…" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-package"></i> @lang('lang.qty_to_supply') <span class="text-danger">*</span></label>
                        <div class="position-relative input-icon">
                            <input type="number" class="form-control purchase-qty" name="qty" value="{{ old('qty') }}" placeholder="@lang('lang.qty') *" min="0.01" step="0.01" required>
                            <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-plus'></i></span>
                        </div>
                        <small class="text-muted purchase-stock-hint d-block mt-1" data-mode="new"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-money"></i> @lang('lang.price') <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="price" value="{{ old('price') }}" placeholder="@lang('lang.price') *" min="0" step="0.01" required>
                    </div>

                    <div class="mb-1">
                        <label class="form-label mb-1">@lang('lang.purchase_date')</label>
                        <input type="date" class="form-control" name="purchased_at" value="{{ old('purchased_at', date('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success"><i class="bx bx-cart-add"></i> @lang('lang.submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->purchaseAdd->any())
@push('js-view')
<script>
    // Validation refusée : on rouvre le modal avec la saisie et les messages.
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('purchase-add')).show();
    });
</script>
@endpush
@endif
