<x-employee-bulk-modal id="leaf-add" :action="route('leaves.store')"
    :title="__('lang.new_leaf')" submit-label="Mettre en congé" submit-icon="bx-user-check" submit-class="btn-success">
    <label for="">@lang('lang.begin') <span class="text-danger">*</span></label>
    <div class="position-relative input-icon mb-3">
        <input type="date" class="form-control" name="begin" required>
        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-calendar-edit'></i></span>
    </div>
    <label for="">@lang('lang.end') <span class="text-danger">*</span></label>
    <div class="position-relative input-icon mb-3">
        <input type="date" class="form-control" name="end" required>
        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-calendar-edit'></i></span>
    </div>
    <div class="position-relative input-icon mb-3">
        <textarea class="form-control" name="reason" placeholder="@lang('lang.reason')" style="resize: none" required></textarea>
        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-message-detail'></i></span>
    </div>
    <x-leaf-type-fields />
</x-employee-bulk-modal>
