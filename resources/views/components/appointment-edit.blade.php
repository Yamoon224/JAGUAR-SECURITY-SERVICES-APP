<div class="modal fade" id="appointment{{ $appointment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-calendar"></i> @lang('lang.edit_appointment')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('appointments.update', $appointment->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">                
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="visitor" placeholder="@lang('lang.visitor')" value="{{ $appointment->visitor }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="tel" class="form-control" name="phone" placeholder="@lang('lang.phone_id') *" value="{{ $appointment->phone }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-phone'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="company" placeholder="@lang('lang.company')" value="{{ $appointment->company }}">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-buildings'></i></span>
                            </div>
                            <div class="position-relative">
                                <label>@lang('lang.expected_at') <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="expected_at" placeholder="@lang('lang.expected_at') *" value="{{ $appointment->expected_at }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative">
                                <label>@lang('lang.start_time') <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="start_time" placeholder="@lang('lang.start_time')" value="{{ $appointment->start_time }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative">
                                <label>@lang('lang.end_time') <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="end_time" placeholder="@lang('lang.end_time')" value="{{ $appointment->end_time }}" required>
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