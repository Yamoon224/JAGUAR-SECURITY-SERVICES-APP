<div class="modal fade" id="equipment{{ $equipment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-edit-alt"></i> @lang('lang.equipment', ['param'=>''])</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('equipments.update', $equipment->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-purchase-tag"></i> @lang('lang.name') <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ $equipment->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-money"></i> @lang('lang.price')</label>
                        <input type="number" class="form-control" name="price" value="{{ $equipment->price }}" min="0" step="0.01">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label mb-1 fw-semibold"><i class="bx bx-package"></i> @lang('lang.total_qty')</label>
                            <input type="number" class="form-control" name="qty" value="{{ $equipment->qty }}" min="0" step="0.01">
                        </div>
                        <div class="col-6">
                            <label class="form-label mb-1 fw-semibold"><i class="bx bx-error"></i> @lang('lang.deteriorated_qty')</label>
                            <input type="number" class="form-control" name="deteriorated_qty" value="{{ $equipment->deteriorated_qty ?? 0 }}" min="0" step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success"><i class="bx bx-check"></i> @lang('lang.submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
