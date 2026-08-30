<x-admin-layout>

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.material_dotation')</h6>
    <div class="ms-auto">
        @if(isRightAccess([1, 5]))
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#dotation-add"><i class="bx bx-user-plus"></i> @lang('lang.new_dotation')</a>
        @endif
        <a class="btn btn-sm btn-danger" href="{{ route('prints.dotations.report') }}" target="_blank"><i class="bx bx-printer"></i> PDF @lang('lang.dotation', ['param'=>'s'])</a>
    </div>
</div>
<hr/>

<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body">
        <p class="text-muted mb-3">@lang('lang.material_dotation_hint')</p>

        <div class="row">
            <div class="col-12 col-lg-6 col-md-6">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing"><i class="bx bx-search-alt"></i></span>
                    <input type="text" class="form-control" id="searchKey" placeholder="@lang('lang.search') (@lang('lang.name'), @lang('lang.firstname'), @lang('lang.matricule'))">
                </div>
            </div>
        </div>

        <div class="row g-4 row-cols-1 row-cols-lg-2 row-cols-xl-4 search-result"></div>

        <div class="row g-4 row-cols-1 row-cols-lg-2 row-cols-xl-4" id="no-search">
            @forelse ($beneficiaries as $item)
                <x-dotation-beneficiary-card :employee="$item" />
            @empty
                <div class="col-12"><p class="text-center text-danger">@lang('lang.no_employee')</p></div>
            @endforelse

            <hr class="w-100">
            <x-pagination :paginator="$beneficiaries" />
        </div>
    </div>
</div>

<x-dotation-add :employees="$employees" :equipments="$equipments" />

@push('js-view')
<script>
    $('#searchKey').on('keyup', function () {
        let search = $(this).val();
        if (search != '') {
            $("#no-search").hide();
            $('.search-result').load("{{ route('dotations.search') }}", {'_token':"{{ csrf_token() }}", 'search':search});
        } else {
            $('.search-result').html("");
            $("#no-search").show();
        }
    })
</script>
@endpush
</x-admin-layout>
