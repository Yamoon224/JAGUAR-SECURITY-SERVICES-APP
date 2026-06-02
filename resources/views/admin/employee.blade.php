<x-admin-layout>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">@lang('lang.profil') @lang('lang.employee', ['param'=>''])</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}" title="La liste des employées"><i class="bx bx-table"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $employee->position }}</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<div class="container">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-4">
                @if(session()->has('errors'))
                <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
					<div class="d-flex align-items-center">
						<div class="font-35 text-white"><i class='bx bxs-message-square-x'></i>
						</div>
						<div class="ms-3">
							<h6 class="mb-0 text-white text-uppercase">@lang('lang.employee', ['param'=>""])</h6>
							<div class="text-white">{{ $errors->first('matricule') }}</div>
						</div>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
                @endif
                <div class="card border-dark border-bottom">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="{{ asset('images/employee.png') }}" alt="Photo" class="rounded-circle p-1 bg-dark" width="110">
                            <div class="mt-3">
                                <h4>{{ $employee->firstname." ".$employee->name }}</h4>
                                <p class="text-secondary mb-1">{{ $employee->gender." | ".$employee->position }}</p>
                                <p class="text-muted font-size-sm">{{ $employee->studygrade." | ".$employee->familystatus." | ".$employee->contract }}</p>
                            </div>
                        </div>
                        <hr class="my-3" />
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0"><i class="bx bx-phone"></i> @lang('lang.phone_id')</h6>
                                <span class="text-secondary">{{ $employee->phone }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0"><i class="bx bx-envelope"></i> @lang('lang.email')</h6>
                                <span class="text-secondary">{{ $employee->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0"><i class="bx bx-money"></i> @lang('lang.salary')</h6>
                                <span class="text-secondary">{{ $employee->salary }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0"><i class="bx bx-money"></i> @lang('lang.prime')</h6>
                                <span class="text-secondary">{{ $employee->prime }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0"><i class="bx bx-user"></i> @lang('lang.emergency')</h6>
                                <span class="text-secondary">{{ $employee->emergency_name." | ".$employee->emergency_phone }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card border-dark border-bottom">
                    <div class="card-body">
                        <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-2">
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
                                <div class="col-md-6">
                                    <select class="form-select" name="gender" required>
                                        <option value=''>@lang('lang.gender') *</option>
                                        <option {{ $employee->gender == 'Homme' ? 'selected' : '' }}>Homme</option>
                                        <option {{ $employee->gender == 'Femme' ? 'selected' : '' }}>Femme</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="tel" class="form-control" name="phone" placeholder="@lang('lang.phone_id') *" value="{{ $employee->phone }}" required>
                                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-phone'></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="email" class="form-control" name="email" placeholder="@lang('lang.email')" value="{{ $employee->email }}">
                                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-envelope'></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" name="position" placeholder="@lang('lang.position') *" value="{{ $employee->position }}" required>
                                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-user'></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" name="familystatus" required>
                                        <option value=''>@lang('lang.familystatus') *</option>
                                        <option {{ $employee->familystatus == 'Célibataire' ? 'selected' : '' }}>Célibataire</option>
                                        <option {{ $employee->familystatus == 'Divorcé(e)' ? 'selected' : '' }}>Divorcé(e)</option>
                                        <option {{ $employee->familystatus == 'Marié(e)' ? 'selected' : '' }}>Marié(e)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" name="studygrade" required>
                                        <option value=''>@lang('lang.studygrade') *</option>
                                        <option {{ $employee->studygrade == 'C.E.P' ? 'selected' : '' }}>C.E.P</option>
                                        <option {{ $employee->studygrade == 'B.E.P.C' ? 'selected' : '' }}>B.E.P.C</option>
                                        <option {{ $employee->studygrade == 'Baccalauréat Unique' ? 'selected' : '' }}>Baccalauréat Unique</option>
                                        <option {{ $employee->studygrade == 'Licence' ? 'selected' : '' }}>Licence</option>
                                        <option {{ $employee->studygrade == 'Master' ? 'selected' : '' }}>Master</option>
                                        <option {{ $employee->studygrade == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" name="contract" required>
                                        <option value=''>@lang('lang.contract_type') *</option>
                                        <option {{ $employee->contract == 'CDD' ? 'selected' : '' }}>CDD</option>
                                        <option {{ $employee->contract == 'CDI' ? 'selected' : '' }}>CDI</option>
                                        <option {{ $employee->contract == 'Vacataire | Stagiaire' ? 'selected' : '' }}>Vacataire | Stagiaire</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" name="matricule" placeholder="@lang('lang.matricule') *" value="{{ $employee->matricule }}" required>
                                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-id-card'></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="number" class="form-control" name="salary" placeholder="@lang('lang.salary') *" min="600000" value="{{ $employee->salary }}" required>
                                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="number" class="form-control" name="prime" placeholder="@lang('lang.prime')" min="0" value="{{ $employee->prime }}">
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
                                <div class="col-md-4">
                                    <div class="form-check form-switch form-check-warning">
                                        <input class="form-check-input" type="checkbox" role="switch" id="isleaved" name="isleaved" checked>
                                        <label class="form-check-label" for="isleaved">@lang('lang.leaf')</label>
                                    </div>
                                </div>
                                @endif
                                
                                @if($employee->issuspended == 1)
                                <div class="col-md-4">
                                    <div class="form-check form-switch form-check-danger">
                                        <input class="form-check-input" type="checkbox" role="switch" id="issuspended" name="issuspended" checked>
                                        <label class="form-check-label" for="issuspended">@lang('lang.suspension')</label>
                                    </div>
                                </div>
                                @endif
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
                                <div class="col-12 text-center text-uppercase"><h6>@lang('lang.bank')</h6></div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" name="rib" placeholder="@lang('lang.rib')" value="{{ $employee->rib }}">
                                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-credit-card'></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" name="bank" placeholder="@lang('lang.bank_name')" value="{{ $employee->bank }}">
                                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-building'></i></span>
                                    </div>
                                </div>
                                <div class="col-12 text-center text-uppercase"><h6>@lang('lang.tax')</h6></div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="number" class="form-control" name="cnss" placeholder="@lang('lang.cnss')" value="{{ $employee->cnss }}">
                                        <span class="position-absolute top-50 translate-middle-y">%</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="number" class="form-control" name="rts" placeholder="@lang('lang.rts')" value="{{ $employee->rts }}">
                                        <span class="position-absolute top-50 translate-middle-y">%</span>
                                    </div>
                                </div>
                                <div class="col-12 text-center text-uppercase"><h6>@lang('lang.acompte')</h6></div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="number" class="form-control" name="acompte" placeholder="@lang('lang.acompte')" value="{{ $employee->acompte }}">
                                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" name="sanction" placeholder="@lang('lang.sanction')" value="{{ $employee->sanction }}">
                                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-money'></i></span>
                                    </div>
                                </div>
                                @if(isRightAccess([1, 2, 3]))
                                <div class="col-12">
                                    <button class="btn btn-dark w-100">@lang('lang.submit')</button>
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-dark border-bottom border-3">
                    <div class="card-body">
                        <ul class="nav nav-pills mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="pill" href="#affectationTab" role="tab" aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user-check font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">@lang('lang.affectation', ['param'=>'s'])</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="pill" href="#leafTab" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user-minus font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">@lang('lang.leaf', ['param'=>'s'])</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="pill" href="#dotationTab" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user-plus font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">@lang('lang.dotation', ['param'=>'s'])</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="pill" href="#suspensionTab" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user-minus font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">@lang('lang.suspension', ['param'=>'s'])</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="affectationTab" role="tabpanel">
                                <h6 class="mb-0 text-uppercase">@lang('lang.affectation', ['param'=>'s'])</h6>
                                <hr/>
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-2">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">@lang('lang.date')</th>
                                                    <th scope="col">@lang('lang.price')</th>
                                                    <th scope="col">@lang('lang.customer', ['param'=>''])</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employee->affectations as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>{{ $item->price }}</td>
                                                    <td>{{ $item->customer->name }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="leafTab" role="tabpanel">
                                @if(isRightAccess([1, 3]))
                                <div class="page-breadcrumb d-none d-sm-flex align-items-center">
                                    <div class="ms-auto">
                                        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#leaf-add"><i class="bx bx-user-plus"></i> @lang('lang.new_leaf')</a>
                                    </div>
                                </div>
                                <hr/>
                                @endif
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-2">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">@lang('lang.begin')</th>
                                                    <th scope="col">@lang('lang.end')</th>
                                                    <th scope="col">@lang('lang.reason')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employee->leaves as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->begin }}</td>
                                                    <td>{{ $item->end }}</td>
                                                    <td>{{ $item->reason }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="dotationTab" role="tabpanel">
                                @if(isRightAccess([1, 5]))
                                <div class="page-breadcrumb d-none d-sm-flex align-items-center">
                                    <div class="ms-auto">
                                        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#dotation-add"><i class="bx bx-user-plus"></i> @lang('lang.new_dotation')</a>
                                    </div>
                                </div>
                                <hr/>
                                @endif
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-2">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">@lang('lang.date')</th>
                                                    <th scope="col">@lang('lang.equipment', ['param'=>''])</th>
                                                    <th scope="col">@lang('lang.qty')</th>
                                                    <th>@lang('lang.action', ['param'=>'s'])</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employee->dotations as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>{{ $item->equipment->name }}</td>
                                                    <td>{{ $item->qty." ".$item->equipment->unit }}</td>
                                                    <td>            
                                                        <form action="{{ route('dotations.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" title="Supprimer cette dotation"><i class="bx bx-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="suspensionTab" role="tabpanel">
                                <h6 class="mb-0 text-uppercase">@lang('lang.suspension', ['param'=>'s'])</h6>
                                <hr/>
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-2">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">@lang('lang.date')</th>
                                                    <th scope="col">@lang('lang.reason')</th>
                                                    <th scope="col">@lang('lang.duration')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employee->suspensions as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>{{ $item->reason }}</td>
                                                    <td>{{ $item->duration." ".$item->unit }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>

@if(isRightAccess([1, 5]))
<x-dotation-employee-add :employee="$employee" :equipments="$equipments" />
@endif

@if(isRightAccess([1, 3]))
<x-leaf-employee-add :employee="$employee" />
@endif

</x-admin-layout>

