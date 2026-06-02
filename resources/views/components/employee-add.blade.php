<div class="modal fade" id="employee-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> @lang('lang.new_employee')</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 text-uppercase"><h6>@lang('lang.employee', ['param'=>''])</h6></div>
                        <div class="col-12" id="applicant-delete"></div>
                        <div class="col-12">                                    
                            <select class="form-select mb-3" name="employee_id" required>
                                <option value='' selected>@lang('lang.applicant', ['param'=>'s']) *</option>
                                @foreach ($applicants as $item)
                                    <option value="{{ $item->id }}">{{ $item->firstname." ".$item->name." | ".$item->phone. " | " .$item->applicationid }}</option>
                                @endforeach
                            </select>
                            <div class="position-relative input-icon mb-3">
                                <input type="email" class="form-control" name="email" placeholder="@lang('lang.email')">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-envelope'></i></span>
                            </div>
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" name="position" placeholder="@lang('lang.position') *" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" name="gender" required>
                                <option value='' selected>@lang('lang.gender') *</option>
                                <option>Homme</option>
                                <option>Femme</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" name="familystatus" required>
                                <option value='' selected>@lang('lang.familystatus') *</option>
                                <option>Célibataire</option>
                                <option>Divorcé(e)</option>
                                <option>Marié(e)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" name="studygrade" required>
                                <option value='' selected>@lang('lang.studygrade') *</option>
                                <option>C.E.P</option>
                                <option>B.E.P.C</option>
                                <option>Baccalauréat Unique</option>
                                <option>Licence</option>
                                <option>Master</option>
                                <option>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" name="contract" required>
                                <option value='' selected>@lang('lang.contract_type') *</option>
                                <option>CDD</option>
                                <option>CDI</option>
                                <option>Vacataire | Stagiaire</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="position-relative input-icon mb-3">
                                <input type="number" class="form-control" name="salary" placeholder="@lang('lang.salary') *" min="600000" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
                            </div>
                            <div class="position-relative input-icon">
                                <input type="number" class="form-control" name="prime" placeholder="@lang('lang.prime')" min="0">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
                            </div>
                        </div>
                        
                        <div class="col-12 text-uppercase"><h6>@lang('lang.bank')</h6></div>
                        <div class="col-md-6">
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" name="rib" placeholder="@lang('lang.rib')">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-credit-card'></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" name="bank" placeholder="@lang('lang.bank_name')">
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-building'></i></span>
                            </div>
                        </div>
                        <div class="col-12 text-uppercase"><h6>@lang('lang.emergency')</h6></div>
                        <div class="col-md-6">
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" name="emergency_name" placeholder="@lang('lang.firstname') & @lang('lang.name') *" required>
                                <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative input-icon">
                                <input type="tel" class="form-control" name="emergency_phone" placeholder="@lang('lang.phone_id') *">
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
<script>
    $('[name="employee_id"]').on('change', function () {
        let option = $(this).children('option:selected').val();
        console.log(option);
        if(option != '') {
            $('#applicant-delete').html('<a href="https://admin.jss-gn.com/applicants/'+option+'/delete" class="btn btn-sm btn-danger">@lang("lang.delete")</a>')
        } else {
            $('#applicant-delete').html('');
        }
    })
</script>