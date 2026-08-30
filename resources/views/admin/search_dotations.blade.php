@forelse($beneficiaries as $item)
    <x-dotation-beneficiary-card :employee="$item" />
@empty
    <div class="col-12"><p class="text-center text-danger">@lang('lang.no_employee')</p></div>
@endforelse
