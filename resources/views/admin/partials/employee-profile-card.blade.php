<div class="card border-dark border-bottom employee-profile-export-card">
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
                <h6 class="mb-0"><i class="bx bx-phone"></i></h6>
                <span class="text-secondary">{{ $employee->phone }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0"><i class="bx bx-envelope"></i></h6>
                <span class="text-secondary">{{ $employee->email }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0"><i class="bx bx-money"></i></h6>
                <span class="text-secondary">{{ $employee->salary }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0"><i class="bx bx-money"></i></h6>
                <span class="text-secondary">{{ $employee->prime }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="mb-0"><i class="bx bx-user"></i></h6>
                <span class="text-secondary">{{ $employee->emergency_name." | ".$employee->emergency_phone }}</span>
            </li>
        </ul>

        @if(($showPrintButton ?? false) === true)
        <div class="d-grid mt-3">
            <a href="{{ route('employees.profile-card.print', $employee->id) }}" target="_blank" class="btn btn-dark">
                <i class="bx bx-printer"></i> Exporter ce card en PDF
            </a>
        </div>
        @endif
    </div>
</div>