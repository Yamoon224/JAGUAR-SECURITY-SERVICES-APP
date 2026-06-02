<div class="modal fade" id="meet-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> @lang('lang.new_meet')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('meets.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">                        
                        <div class="col-12">   
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="object" placeholder="@lang('lang.object') *" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-edit'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <textarea class="form-control" name="points" placeholder="@lang('lang.points') *" style="resize: none" required></textarea>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
                            </div>  
                            <div class="mb-3">
                                <label for="inputProductDescription" class="form-label">@lang('lang.file_path')</label>
                                <input class="uploadfile" type="file" name="file_path" accept=".pdf" multiple required>
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