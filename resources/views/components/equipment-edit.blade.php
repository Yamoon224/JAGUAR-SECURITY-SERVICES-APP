<div class="modal fade" id="equipment{{ $equipment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> @lang('lang.equipment', ['param'=>''])</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('equipments.update', $equipment->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">                        
                        <div class="col-12">                                    
                            <select class="form-select mb-3" name="category_id" required>
                                <option selected>@lang('lang.category', ['param'=>'s']) *</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" {{ $equipment->category_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="name" placeholder="@lang('lang.name')" value="{{ $equipment->name }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-envelope'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="text" class="form-control" name="price" placeholder="@lang('lang.price') *" value="{{ $equipment->price }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                            </div>
                            <div class="position-relative input-icon mb-3">
                                <input type="number" class="form-control" name="qty" placeholder="@lang('lang.qty') *" value="{{ $equipment->qty }}" min="1" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
                            </div>
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" name="unit" placeholder="@lang('lang.unit')" value="{{ $equipment->unit }}">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
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