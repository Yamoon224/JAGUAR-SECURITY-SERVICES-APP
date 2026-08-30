@props(['employee'])

@php
    $affectation = method_exists($employee, 'currentAffectation') ? $employee->currentAffectation() : null;
    $site = $affectation?->location ?: ($affectation?->customer?->name ?: __('lang.no_affectation'));
@endphp

<div class="col d-flex">
    <div class="card border-bottom border-dark radius-15 h-100 w-100">
        <div class="card-body p-3 d-flex flex-column">
            <div class="text-center">
                <img src="{{ asset($employee->photo ?? 'images/avatar.png') }}" width="90" height="90" class="rounded-circle shadow" alt="">
                <h6 class="mb-0 mt-2">{{ $employee->name." ".$employee->firstname }}</h6>
                <span class="badge bg-dark">{{ $employee->dotations_count ?? $employee->dotations->count() }} @lang('lang.dotation', ['param'=>'s'])</span>
            </div>
            <ul class="list-group list-group-flush small mt-3 mb-3">
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">@lang('lang.name')</span><strong>{{ $employee->name }}</strong></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">@lang('lang.firstname')</span><strong>{{ $employee->firstname }}</strong></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">@lang('lang.phone_id')</span><strong>{{ $employee->phone }}</strong></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">@lang('lang.matricule')</span><strong>{{ $employee->matricule }}</strong></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">@lang('lang.location')</span><strong class="text-end">{{ $site }}</strong></li>
            </ul>
            <div class="d-grid mt-auto">
                <a href="{{ route('dotations.history', $employee->id) }}" class="btn btn-outline-dark radius-15"><i class="bx bx-history"></i> @lang('lang.dotation_history')</a>
            </div>
        </div>
    </div>
</div>
