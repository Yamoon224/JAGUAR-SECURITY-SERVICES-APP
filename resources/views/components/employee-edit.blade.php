<div class="modal fade" id="employee{{ $employee->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-edit-alt"></i> @lang('lang.new_employee')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('employees.update', $employee->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 text-center text-uppercase"><h6>@lang('lang.employee', ['param'=>''])</h6></div>
                        <div class="col-md-6">
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" name="firstname" placeholder="@lang('lang.firstname') *" value="{{ $employee->firstname }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" name="name" placeholder="@lang('lang.name') *" value="{{ $employee->name }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <select class="form-select" name="gender" required>
                                <option>@lang('lang.gender') *</option>
                                <option {{ $employee->gender == 'Homme' ? 'selected' : '' }}>Homme</option>
                                <option {{ $employee->gender == 'Femme' ? 'selected' : '' }}>Femme</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative input-icon">
                                <input type="tel" class="form-control" name="phone" placeholder="@lang('lang.phone_id') *" value="{{ $employee->phone }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-phone'></i></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative input-icon">
                                <input type="email" class="form-control" name="email" placeholder="@lang('lang.email')" value="{{ $employee->email }}">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-envelope'></i></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" name="position" placeholder="@lang('lang.position') *" value="{{ $employee->position }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <select class="form-select" name="familystatus" required>
                                <option>@lang('lang.familystatus') *</option>
                                <option {{ $employee->familystatus == 'Célibataire' ? 'selected' : '' }}>Célibataire</option>
                                <option {{ $employee->familystatus == 'Divorcé(e)' ? 'selected' : '' }}>Divorcé(e)</option>
                                <option {{ $employee->familystatus == 'Marié(e)' ? 'selected' : '' }}>Marié(e)</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <select class="form-select" name="studygrade" required>
                                <option>@lang('lang.studygrade') *</option>
                                <option {{ $employee->studygrade == 'C.E.P' ? 'selected' : '' }}>C.E.P</option>
                                <option {{ $employee->studygrade == 'B.E.P.C' ? 'selected' : '' }}>B.E.P.C</option>
                                <option {{ $employee->studygrade == 'Baccalauréat Unique' ? 'selected' : '' }}>Baccalauréat Unique</option>
                                <option {{ $employee->studygrade == 'Licence' ? 'selected' : '' }}>Licence</option>
                                <option {{ $employee->studygrade == 'Master' ? 'selected' : '' }}>Master</option>
                                <option {{ $employee->studygrade == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <select class="form-select" name="contract" required>
                                <option>@lang('lang.contract_type') *</option>
                                <option {{ $employee->contract == 'CDD' ? 'selected' : '' }}>CDD</option>
                                <option {{ $employee->contract == 'CDI' ? 'selected' : '' }}>CDI</option>
                                <option {{ $employee->contract == 'Vacataire | Stagiaire' ? 'selected' : '' }}>Vacataire | Stagiaire</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative input-icon">
                                <input type="number" class="form-control" name="salary" placeholder="@lang('lang.salary') *" min="600000" value="{{ $employee->salary }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check form-switch form-check-success">
                                <input class="form-check-input" type="checkbox" role="switch" id="hastoPay" name="hastopay" {{ $employee->hastopay == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="hastoPay">@lang('lang.hastopay')</label>
                            </div>
                        </div>
                        
                        @if($employee->isleaved == 1)
                        <div class="col-md-6">
                            <div class="form-check form-switch form-check-warning">
                                <input class="form-check-input" type="checkbox" role="switch" id="isleaved" name="isleaved" checked>
                                <label class="form-check-label" for="isleaved">@lang('lang.leaf')</label>
                            </div>
                        </div>
                        @endif
                        
                        @if($employee->issuspended == 1)
                        <div class="col-md-6">
                            <div class="form-check form-switch form-check-danger">
                                <input class="form-check-input" type="checkbox" role="switch" id="issuspended" name="issuspended" checked>
                                <label class="form-check-label" for="issuspended">@lang('lang.suspension')</label>
                            </div>
                        </div>
                        @endif
                        
                        <div class="col-md-6">
                            <div class="position-relative input-icon">
                                <input type="number" class="form-control" name="prime" placeholder="@lang('lang.prime')" min="0" value="{{ $employee->prime }}">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
                            </div>
                        </div>
                        <div class="col-12 text-center text-uppercase"><h6>@lang('lang.emergency')</h6></div>
                        <div class="col-md-6">
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" name="emergency_name" placeholder="@lang('lang.firstname') & @lang('lang.name') *" value="{{ $employee->emergency_name }}" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative input-icon">
                                <input type="tel" class="form-control" name="emergency_phone" placeholder="@lang('lang.phone_id') *" value="{{ $employee->emergency_phone }}">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-phone'></i></span>
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