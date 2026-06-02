<div class="modal fade" id="mail-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-mail-send"></i> @lang('lang.new_mail')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('mails.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">                        
                        <div class="col-12">   
                            <div class="position-relative mb-3">
                                <label for="mail_datetime">@lang('lang.mail_datetime')</label>
                                <input type="datetime-local" class="form-control" name="mail_datetime" id="mail_datetime" required />
                            </div>  
                            <div class="position-relative mb-3">
                                <label for="name">@lang('lang.type')</label>
                                <select class="form-control" name="name" id="name" required>
                                    <option>DEPART</option>
                                    <option>ARRIVEE</option>
                                </select>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="srce" placeholder="@lang('lang.srce') *" required />
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
                            </div> 
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="destinator" placeholder="@lang('lang.dest') *" required />
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="subject" placeholder="@lang('lang.subject') *" required />
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
                            </div>
                            <div class="position-relative mb-3">
                                <label for="observation">@lang('lang.observation')</label>
                                <textarea class="form-control" name="observation" style="resize: none" id="observation"></textarea>
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