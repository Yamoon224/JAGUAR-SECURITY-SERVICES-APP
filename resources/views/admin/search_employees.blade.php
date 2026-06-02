@forelse($employees as $item)
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="card border-bottom border-dark radius-15">
        <div class="card-body p-3 text-center">
            <img src="{{ asset($item->photo ?? 'images/avatar.png') }}" width="110" height="110" class="rounded-circle shadow" alt="">
            <h6 class="mb-0 mt-2">{{ $item->firstname." ".$item->name }}</h6>
            <p class="mb-1">{{ $item->position." ".$item->matricule }}</p>
            <div class="list-inline mb-2"> 
                <a href="tel:{{ $item->phone }}" class="list-inline-item text-dark"><i class="bx bx-phone"></i> {{ $item->phone }}</a>
                @if(isRightAccess([1]))
                <a href="{{ route('employees.notaffected', $item->id) }}" onclick="if(!confirm('Confirmez-Vous cette suppression')) return false" class="list-inline-item text-danger"><i class="bx bx-trash"></i></a>
                @endif
            </div>
            <div class="d-grid"> 
                @if(isRightAccess([1, 2]))
                <a href="{{ route('employees.show', $item->id) }}" class="btn btn-outline-dark radius-15">@lang('lang.see_more')</a>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
<div class="col-12"><p class="text-center text-danger">@lang('lang.no_employee')</p></div>
@endforelse