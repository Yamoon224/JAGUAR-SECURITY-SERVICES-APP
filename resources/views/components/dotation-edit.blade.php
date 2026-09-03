<div class="modal fade" id="dotation{{ $dotation->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-edit-alt"></i> @lang('lang.dotation')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('dotations.update', $dotation->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-user"></i> @lang('lang.employee', ['param'=>'']) <span class="text-danger">*</span></label>
                        <select class="form-select" name="employee_id" required>
                            <option value="">@lang('lang.employee', ['param'=>'']) *</option>
                            @foreach ($employees as $item)
                                <option value="{{ $item->id }}" {{ $dotation->employee_id == $item->id ? 'selected' : '' }}>{{ $item->firstname." ".$item->name." | ".$item->position }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-customize"></i> @lang('lang.equipment', ['param'=>'']) <span class="text-danger">*</span></label>
                        <select class="form-select" name="equipment_id" required>
                            <option value="">@lang('lang.equipment', ['param'=>'']) *</option>
                            @foreach ($equipments as $item)
                                @php $isCurrent = $dotation->equipment_id == $item->id; @endphp
                                <option value="{{ $item->id }}" title="{{ $item->available_qty }}"
                                    {{ $isCurrent ? 'selected' : '' }}
                                    {{ (!$isCurrent && $item->available_qty <= 0) ? 'disabled' : '' }}>
                                    {{ $item->name." | ".$item->available_qty." disponible(s)" }}{{ $item->available_qty <= 0 ? ' — épuisé' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-package"></i> @lang('lang.qty') <span class="text-danger">*</span></label>
                        <div class="position-relative input-icon">
                            <input type="number" class="form-control" name="qty" value="{{ $dotation->qty }}" placeholder="@lang('lang.qty') *" min="1" step="0.01" required>
                            <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
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
