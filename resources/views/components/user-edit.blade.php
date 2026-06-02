<div class="modal fade" id="user{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-edit-alt"></i> @lang('lang.new_user')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 text-center text-uppercase"><h6>@lang('lang.user', ['param'=>''])</h6></div>
                        <div class="col-12">
                            <div class="border border-3 p-4 rounded">
                                <div class="position-relative input-icon mb-3">
                                    <input type="text" class="form-control" name="name" placeholder="@lang('lang.name') *"  value="{{ $user->name }}" required>
                                    <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                                </div>
                                <div class="position-relative input-icon mb-3">
                                    <input type="email" class="form-control" name="email" placeholder="@lang('lang.email') *" value="{{ $user->email }}" required>
                                    <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-envelope'></i></span>
                                </div>
                                <div class="position-relative input-icon mb-3">
                                    <input type="tel" class="form-control" name="phone" placeholder="@lang('lang.phone_id') *" value="{{ $user->phone }}" required>
                                    <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-phone'></i></span>
                                </div>
                                <div class="position-relative input-icon mb-3">
                                    <select class="form-select" name="group_id" required>
                                        <option selected>@lang('lang.group', ['param'=>'']) *</option>
                                        @foreach ($groups as $item)
                                            <option value="{{ $item->id }}" {{ $user->group_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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