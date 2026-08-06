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
                    <div class="row g-3">
                        <div class="col-12">
                            <select class="form-select mb-3" name="equipment_id" required>
                                <option value="" selected>@lang('lang.equipment', ['param'=>'s']) *</option>
                                @foreach ($equipments as $item)
                                    <option value="{{ $item->id }}" {{ $purchase->equipment_id == $item->id ? 'selected' : '' }}>{{ $item->name." | ".$item->unit }}</option>
                                @endforeach
                            </select>
                            <div class="position-relative input-icon mb-3">
                                <input type="number" class="form-control" name="qty" placeholder="@lang('lang.qty') *" value="{{ $purchase->qty }}" min="0.01" step="0.01" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-package'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="number" class="form-control" name="price" placeholder="@lang('lang.price') *" value="{{ $purchase->price }}" min="0" step="0.01" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
                            </div>
                            <label class="form-label mb-1">@lang('lang.purchase_date')</label>
                            <div class="position-relative input-icon">
                                <input type="date" class="form-control" name="purchased_at" value="{{ \Carbon\Carbon::parse($purchase->purchased_at)->format('Y-m-d') }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-calendar'></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success"><i class="bx bx-user-check"></i> @lang('lang.submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
