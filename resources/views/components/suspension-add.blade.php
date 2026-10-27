<x-employee-bulk-modal id="suspension-add" :action="route('suspensions.store')"
    :title="__('lang.new_suspension')" submit-label="Suspendre" submit-icon="bx-user-minus" submit-class="btn-warning">
    <div class="position-relative input-icon mb-3">
        <textarea class="form-control" name="reason" placeholder="@lang('lang.reason')" style="resize: none" required></textarea>
        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
    </div>
    <div class="position-relative input-icon mb-3">
        <input type="number" class="form-control" name="duration" placeholder="@lang('lang.duration') *" min="1" required>
        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
    </div>
    <div class="position-relative input-icon">
        <input type="text" class="form-control" name="unit" placeholder="@lang('lang.unit')" required>
        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
    </div>
</x-employee-bulk-modal>
