<div class="modal fade" id="dotation-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> @lang('lang.new_dotation')</h5>
                <a class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('dotations.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">           
                        <div class="col-12 text-uppercase text-center"><h6>{{ $employee->firstname." ".$employee->name." | ".$employee->position }}</h6></div>
                        <div class="col-12">    
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <select class="form-select mb-3" name="equipment_id" required>
                                <option value="" selected>@lang('lang.equipment', ['param'=>'']) *</option>
                                @foreach ($equipments as $item)
                                    <option value="{{ $item->id }}" title="{{ $item->qty - $item->dotations->sum('qty') }}">{{ $item->category->name." | ".$item->name." | ".($item->qty - $item->dotations->sum('qty'))." ".$item->unit." disponible(s)" }}</option>
                                @endforeach
                            </select>
                            <div class="position-relative input-icon mb-3">
                                <input type="number" class="form-control" name="qty" id="equipmentQty" placeholder="@lang('lang.qty') *" min="1" disabled required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
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
<script>
    $('[name="equipment_id"]').on('change', function () {
        let selectedItem = $(this).children('option:selected');
        let value = selectedItem.val();
        if (value != '') {
            $('#equipmentQty').prop('disabled', false).prop('max', selectedItem.prop('title'));
        }else {
            $('#equipmentQty').prop('disabled', true);
        }
    })
</script>