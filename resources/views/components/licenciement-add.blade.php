<x-employee-bulk-modal id="licenciement-add" :action="route('licenciements.store')"
    :title="__('lang.new_licenciement')" submit-label="Licencier" submit-icon="bx-user-x" submit-class="btn-danger">
    <div class="position-relative input-icon">
        <textarea class="form-control" name="reason" placeholder="@lang('lang.reason')" style="resize: none" required></textarea>
        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
    </div>
</x-employee-bulk-modal>
