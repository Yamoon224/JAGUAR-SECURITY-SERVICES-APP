<div class="modal fade" id="dotation{{ $dotation->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> @lang('lang.new_dotation')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('dotations.update', $dotation->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">                        
                        <div class="col-12">                                    
                            <select class="form-select mb-3" name="employee_id" required>
                                <option selected>@lang('lang.employee', ['param'=>'']) *</option>
                                @foreach ($employees as $item)
                                    <option value="{{ $item->id }}" {{ $dotation->employee_id == $item->id ? 'selected' : '' }}>{{ $item->firstname." ".$item->name." | ".$item->position }}</option>
                                @endforeach
                            </select>
                            <select class="form-select mb-3" name="equipment_id" required>
                                <option selected>@lang('lang.equipment', ['param'=>'']) *</option>
                                @foreach ($equipments as $item)
                                    <option value="{{ $item->id }}" {{ $dotation->equipment_id == $item->id ? 'selected' : '' }}>{{ $item->name." | ".$item->qty." ".$item->unit." disponible(s)" }}</option>
                                @endforeach
                            </select>
                            <div class="position-relative input-icon mb-3">
                                <input type="number" class="form-control" name="qty" value="{{ $dotation->qty }}" placeholder="@lang('lang.qty') *" min="1" required>
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