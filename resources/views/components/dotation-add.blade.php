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
                    <div class="row g-3">                        
                        <div class="col-12">                                    
                            <select class="form-select mb-3" name="employee_id" required>
                                <option selected>@lang('lang.employee', ['param'=>'']) *</option>
                                @foreach ($employees as $item)
                                    <option value="{{ $item->id }}">{{ $item->firstname." ".$item->name." | ".$item->position." | ".$item->matricule }}</option>
                                @endforeach
                            </select>
                            <select class="form-select mb-3" name="equipment_id" required>
                                <option value="" selected>@lang('lang.equipment', ['param'=>'']) *</option>
                                @foreach ($equipments as $item)
                                    <option value="{{ $item->id }}" title="{{ $item->qty - $item->dotations->sum('qty') }}">{{ $item->category->name." | ".$item->name." | ".($item->qty - $item->dotations->sum('qty'))." ".$item->unit." disponible(s)" }}</option>
                                @endforeach
                            </select>
                            <div class="position-relative input-icon mb-3">
                                <input type="number" class="form-control" id="equipmentQty" name="qty" placeholder="@lang('lang.qty') *" min="1" required>
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