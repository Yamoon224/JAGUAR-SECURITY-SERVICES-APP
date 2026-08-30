<x-admin-layout>

@php
    $affectation = $employee->currentAffectation();
    $site = $affectation?->location ?: ($affectation?->customer?->name ?: __('lang.no_affectation'));
@endphp

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.dotation_history')</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-secondary" href="{{ route('dotations.index') }}"><i class="bx bx-arrow-back"></i> @lang('lang.material_dotation')</a>
        @if(isRightAccess([1, 5]))
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#dotation-add"><i class="bx bx-user-plus"></i> @lang('lang.new_dotation')</a>
        @endif
    </div>
</div>
<hr/>

<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card border-bottom border-dark radius-15">
            <div class="card-body p-3 text-center">
                <img src="{{ asset($employee->photo ?? 'images/avatar.png') }}" width="110" height="110" class="rounded-circle shadow" alt="">
                <h6 class="mb-0 mt-2">{{ $employee->name." ".$employee->firstname }}</h6>
                <p class="mb-2 text-muted">{{ $employee->position }}</p>
                <ul class="list-group list-group-flush small text-start">
                    <li class="list-group-item d-flex justify-content-between"><span class="text-muted">@lang('lang.name')</span><strong>{{ $employee->name }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="text-muted">@lang('lang.firstname')</span><strong>{{ $employee->firstname }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="text-muted">@lang('lang.phone_id')</span><strong>{{ $employee->phone }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="text-muted">@lang('lang.matricule')</span><strong>{{ $employee->matricule }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="text-muted">@lang('lang.location')</span><strong class="text-end">{{ $site }}</strong></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card border-dark border-bottom border-3 border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <td>#</td>
                                <th>@lang('lang.date')</th>
                                <th>@lang('lang.equipment', ['param'=>''])</th>
                                <th>@lang('lang.qty')</th>
                                <th>@lang('lang.action', ['param'=>'s'])</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dotations as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                <td>{{ $item->equipment?->name }}</td>
                                <td>{{ $item->qty." ".$item->equipment?->unit }}</td>
                                <td>
                                    @if(isRightAccess([1, 5]))
                                    <form action="{{ route('dotations.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="@lang('lang.delete')" onclick="if(!confirm('Confirmez-Vous cette suppression')) return false"><i class="bx bx-trash"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">@lang('lang.no_dotation')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<x-dotation-employee-add :employee="$employee" :equipments="$equipments" />
</x-admin-layout>
