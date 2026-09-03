<div class="modal fade" id="purchase{{ $purchase->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-edit-alt"></i> @lang('lang.purchase', ['param'=>''])</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('purchases.update', $purchase->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-customize"></i> @lang('lang.equipment', ['param'=>'']) <span class="text-danger">*</span></label>
                        <select class="form-select purchase-equipment" name="equipment_id" required>
                            <option value="">@lang('lang.equipment', ['param'=>'']) *</option>
                            @foreach ($equipments as $item)
                                <option value="{{ $item->id }}" data-available="{{ $item->available_qty }}" {{ $purchase->equipment_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->name." | ".$item->available_qty." en stock" }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-package"></i> @lang('lang.qty_to_supply') <span class="text-danger">*</span></label>
                        <div class="position-relative input-icon">
                            <input type="number" class="form-control purchase-qty" name="qty" placeholder="@lang('lang.qty') *" value="{{ $purchase->qty }}" min="0.01" step="0.01" required>
                            <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-plus'></i></span>
                        </div>
                        <small class="text-muted purchase-stock-hint d-block mt-1" data-mode="edit"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-money"></i> @lang('lang.price') <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="price" placeholder="@lang('lang.price') *" value="{{ $purchase->price }}" min="0" step="0.01" required>
                    </div>

                    <div class="mb-1">
                        <label class="form-label mb-1">@lang('lang.purchase_date')</label>
                        <input type="date" class="form-control" name="purchased_at" value="{{ \Carbon\Carbon::parse($purchase->purchased_at)->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success"><i class="bx bx-check"></i> @lang('lang.submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
