<div class="row row-cols-1 row-cols-md-3 row-cols-xl-6">
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="text-center">
                    <div class="widgets-icons rounded-circle mx-auto bg-light-success text-success mb-3"><i class='bx bx-briefcase'></i></div>
                    <h4 class="my-1">{{ $employees->where('contract', 'CDI')->count() }}</h4>
                    <p class="mb-0 text-secondary">@lang('lang.employee', ['param'=>'s']) CDI</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="text-center">
                    <div class="widgets-icons rounded-circle mx-auto bg-light-info text-info mb-3"><i class='bx bx-briefcase'></i></div>
                    <h4 class="my-1">{{ $employees->where('contract', 'CDD')->count() }}</h4>
                    <p class="mb-0 text-secondary">@lang('lang.employee', ['param'=>'s']) CDD</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="text-center">
                    <div class="widgets-icons rounded-circle mx-auto bg-light-dark text-dark mb-3"><i class='bx bx-briefcase-alt'></i></div>
                    <h4 class="my-1">{{ $employees->where('contract', 'Vacataire | Stagiaire')->count() }}</h4>
                    <p class="mb-0 text-secondary">Vacataire | Stagiaire</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="text-center">
                    <div class="widgets-icons rounded-circle mx-auto bg-light-warning text-warning mb-3"><i class='bx bx-user-x'></i></div>
                    <h4 class="my-1">{{ $employees->where('familystatus', 'Célibataire')->count() }}</h4>
                    <p class="mb-0 text-secondary">Célibataire</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="text-center">
                    <div class="widgets-icons rounded-circle mx-auto bg-light-success text-success mb-3"><i class='bx bx-user-check'></i></div>
                    <h4 class="my-1">{{ $employees->where('familystatus', 'Marié(e)')->count() }}</h4>
                    <p class="mb-0 text-secondary">Marié(e)</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="text-center">
                    <div class="widgets-icons rounded-circle mx-auto bg-light-danger text-danger mb-3"><i class='bx bx-female'></i></div>
                    <h4 class="my-1">{{ $employees->where('gender', 'Femme')->count() }}</h4>
                    <p class="mb-0 text-secondary">Femmes</p>
                </div>
            </div>
        </div>
    </div>
</div>