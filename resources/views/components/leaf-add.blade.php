<div class="modal fade" id="leaf-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> @lang('lang.new_leaf')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('leaves.store') }}">
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
                            <label for="">@lang('lang.begin') <span class="text-danger">*</span></label>
                            <div class="position-relative input-icon mb-3">
                                <input type="date" class="form-control" name="begin" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-calendar-edit'></i></span>
                            </div>
                            <label for="">@lang('lang.end') <span class="text-danger">*</span></label>
                            <div class="position-relative input-icon mb-3">
                                <input type="date" class="form-control" name="end" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-calendar-edit'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <textarea class="form-control" name="reason" placeholder="@lang('lang.reason')" style="resize: none" required></textarea>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
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