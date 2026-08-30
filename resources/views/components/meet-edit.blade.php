<div class="modal fade" id="meet{{ $meet->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-calendar-check"></i> @lang('lang.meet')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('meets.update', $meet->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bx bx-message-edit"></i> @lang('lang.object') <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="object" value="{{ $meet->object }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bx bx-message-detail"></i> @lang('lang.points') <span class="text-danger">*</span></label>
                        <textarea class="form-control ckeditor-points" id="points-{{ $meet->id }}" name="points" rows="6">{{ $meet->points }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">@lang('lang.file_path') <span class="text-muted small">(PDF)</span></label>
                        <input class="form-control" type="file" name="file_path" accept="application/pdf,.pdf">
                        @if($meet->file_path)
                        <small class="d-block mt-1">
                            <i class="bx bxs-file-pdf text-danger"></i>
                            <a href="{{ asset('storage/'.$meet->file_path) }}" target="_blank">Compte rendu actuel</a> — laissez vide pour le conserver.
                        </small>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success"><i class="bx bx-check"></i> @lang('lang.submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
