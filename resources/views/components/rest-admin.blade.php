<div class="modal fade" id="sanction{{ $employee->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> @lang('lang.sanction')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('sanctions.store') }}">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="position-relative input-icon">
                                <textarea class="form-control" name="reason" placeholder="@lang('lang.reason') *" style="resize: none" required></textarea>
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