<div class="modal fade" id="applicant-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> @lang('lang.applicant', ['param'=>''])</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('applicants.save') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="firstname" placeholder="@lang('lang.firstname') *" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="name" placeholder="@lang('lang.name') *" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="tel" class="form-control" name="phone" placeholder="@lang('lang.phone_id') *" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-phone'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="address" placeholder="@lang('lang.addresses') *" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-map'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="affiliate" placeholder="@lang('lang.affiliate') *" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-map'></i></span>
                            </div>
                            <label for="">@lang('lang.photo')</label>
                            <div class="position-relative input-icon mb-3">
                                <input type="file" class="form-control" name="photo">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-image'></i></span>
                            </div>
                            <div class="mb-3">
                                <label for="inputProductDescription" class="form-label">@lang('lang.application_file')</label>
                                <input id="image-uploadify" type="file" name="path" accept=".pdf" multiple>
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