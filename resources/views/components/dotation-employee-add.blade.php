<div class="modal fade" id="dotation-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> @lang('lang.new_dotation')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('dotations.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="text-uppercase text-center mb-4">
                        <h6 class="mb-0">{{ $employee->firstname." ".$employee->name }}</h6>
                        <small class="text-muted">{{ $employee->position }}</small>
                    </div>

                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                    <div class="mb-4">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-customize"></i> @lang('lang.equipment', ['param'=>'']) <span class="text-danger">*</span></label>
                        <select class="form-select" name="equipment_id" required>
                            <option value="" selected>@lang('lang.equipment', ['param'=>'']) *</option>
                            @foreach ($equipments as $item)
                                <option value="{{ $item->id }}" title="{{ $item->available_qty }}">{{ $item->name." | ".$item->available_qty." ".$item->unit." disponible(s)" }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label mb-1 fw-semibold"><i class="bx bx-package"></i> @lang('lang.qty') <span class="text-danger">*</span></label>
                        <div class="position-relative input-icon">
                            <input type="number" class="form-control" name="qty" id="equipmentQty" placeholder="@lang('lang.qty') *" min="1" disabled required>
                            <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
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
<script>
    $('#dotation-add [name="equipment_id"]').on('change', function () {
        let selectedItem = $(this).children('option:selected');
        let value = selectedItem.val();
        if (value != '') {
            $('#equipmentQty').prop('disabled', false).prop('max', selectedItem.prop('title'));
        }else {
            $('#equipmentQty').prop('disabled', true);
        }
    })
</script>
