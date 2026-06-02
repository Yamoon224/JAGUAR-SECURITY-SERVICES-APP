<div class="modal fade" id="recruitment{{ $recruitment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> @lang('lang.recruitment', ['param'=>''])</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('recruitments.update', $recruitment->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="">@lang('lang.start_date') <span class="text-danger">*</span></label>
                            <div class="position-relative input-icon mb-3">
                                <input type="date" class="form-control" name="start_date" value="{{ $recruitment->start_date }}" placeholder="@lang('lang.start_date') *" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-calendar'></i></span>
                            </div>
                            <label for="">@lang('lang.end_date') <span class="text-danger">*</span></label>
                            <div class="position-relative input-icon mb-3">
                                <input type="date" class="form-control" name="end_date" min="{{ $recruitment->start_date }}" value="{{ $recruitment->end_date }}" placeholder="@lang('lang.end_date') *" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-calendar'></i></span>
                            </div>
                            
                            <label for="">@lang('lang.path')</label>
                            <div class="position-relative input-icon mb-3">
                                <input type="file" class="form-control" name="path">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-file'></i></span>
                            </div>
                            <label for="">@lang('lang.description') <span class="text-danger">*</span></label>
                            <div class="position-relative input-icon mb-3">
                                <textarea class="form-control" name="description" placeholder="@lang('lang.description')" style="resize: none" required>{{ $recruitment->description }}</textarea>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-comment'></i></span>
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