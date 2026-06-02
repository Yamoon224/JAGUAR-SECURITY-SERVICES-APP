<div class="modal fade" id="suspension-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> @lang('lang.new_suspension')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('suspensions.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">                        
                        <div class="col-12">                                    
                            <select class="form-select mb-3" name="employee_id" required>
                                <option value="" selected>@lang('lang.employee', ['param'=>'']) *</option>
                                @foreach ($employees as $item)
                                    <option value="{{ $item->id }}">{{ $item->firstname." ".$item->name." | ".$item->position }}</option>
                                @endforeach
                            </select>
                            <div class="position-relative input-icon mb-3">
                                <textarea class="form-control" name="reason" placeholder="@lang('lang.reason')" style="resize: none" required></textarea>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="number" class="form-control" name="duration" placeholder="@lang('lang.duration') *" min="1" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
                            </div>
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" name="unit" placeholder="@lang('lang.unit')" required>
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