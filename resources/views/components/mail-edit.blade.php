<div class="modal fade" id="mail{{ $mail->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-mail-send"></i> @lang('lang.mail', ['param'=>''])</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('mails.update', $mail->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">                        
                        <div class="col-12">   
                            <div class="position-relative mb-3">
                                <label for="mail_datetime{{ $mail->id }}">@lang('lang.mail_datetime')</label>
                                <input type="datetime-local" class="form-control" value="{{ $mail->mail_datetime }}" name="mail_datetime" id="mail_datetime{{ $mail->id }}" required />
                            </div>  
                            <div class="position-relative mb-3">
                                <label for="name{{ $mail->id }}">@lang('lang.type')</label>
                                <select class="form-control" name="name" id="name{{ $mail->id }}" required>
                                    <option {{ $mail->name == 'DEPART' ? 'selected' : '' }}>DEPART</option>
                                    <option {{ $mail->name == 'ARRIVEE' ? 'selected' : '' }}>ARRIVEE</option>
                                </select>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="srce" placeholder="@lang('lang.srce') *" value="{{ $mail->srce }}" required />
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
                            </div> 
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="destinator" placeholder="@lang('lang.dest') *" value="{{ $mail->destinator }}" required />
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="subject" placeholder="@lang('lang.subject') *" value="{{ $mail->subject }}" required />
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
                            </div>
                            <div class="position-relative mb-3">
                                <label for="observation{{ $mail->id }}">@lang('lang.observation')</label>
                                <textarea class="form-control" name="observation" style="resize: none" id="observation{{ $mail->id }}">{{ $mail->observation }}</textarea>
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