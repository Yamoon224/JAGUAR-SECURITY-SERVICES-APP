<div class="modal fade" id="meet-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-calendar-plus"></i> @lang('lang.new_meet')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('meets.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bx bx-message-edit"></i> @lang('lang.object') <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="object" placeholder="@lang('lang.object')" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bx bx-message-detail"></i> @lang('lang.points') <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="points" rows="4" placeholder="@lang('lang.points')" style="resize: none" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">@lang('lang.file_path') <span class="text-muted small">(PDF)</span></label>
                        <input class="form-control" type="file" name="file_path" accept="application/pdf,.pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success"><i class="bx bx-check"></i> @lang('lang.submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
