<x-admin-layout>

<div class="d-sm-flex align-items-center flex-wrap gap-2">
    <h6 class="breadcrumb-title pe-3 text-uppercase mb-0">@lang('lang.material_dotation')</h6>
    <div class="ms-auto d-flex align-items-center flex-wrap gap-2">
        @if(isRightAccess([1, 5]))
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#dotation-add"><i class="bx bx-user-plus"></i> @lang('lang.new_dotation')</a>
        @endif

        <form method="GET" action="{{ route('prints.dotations.report') }}" target="_blank" class="d-flex align-items-center flex-wrap gap-1">
            <select name="period" id="exportPeriod" class="form-select form-select-sm" style="width:auto">
                <option value="all">@lang('lang.all_periods')</option>
                <option value="jour">@lang('lang.day')</option>
                <option value="semaine">@lang('lang.week')</option>
                <option value="mois" selected>@lang('lang.month')</option>
                <option value="trimestre">@lang('lang.quarter')</option>
                <option value="semestre">@lang('lang.semester')</option>
                <option value="annee">@lang('lang.year', ['param'=>''])</option>
            </select>
            <input type="date" name="date" id="exportDate" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" style="width:auto">
            <button type="submit" name="format" value="pdf" class="btn btn-sm btn-danger" title="@lang('lang.export') PDF"><i class="bx bx-file"></i> PDF</button>
            <button type="submit" name="format" value="csv" class="btn btn-sm btn-success" title="@lang('lang.export') CSV"><i class="bx bx-table"></i> CSV</button>
        </form>
    </div>
</div>
<hr/>

<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body">
        <p class="text-muted mb-3">@lang('lang.material_dotation_hint')</p>

        <div class="row position-relative" style="z-index: 5">
            <div class="col-12 col-lg-6 col-md-6">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing"><i class="bx bx-search-alt"></i></span>
                    <input type="text" class="form-control" id="searchKey" placeholder="@lang('lang.search') (@lang('lang.name'), @lang('lang.firstname'), @lang('lang.matricule'))">
                </div>
            </div>
        </div>

        <div class="row g-4 mt-0 row-cols-1 row-cols-lg-2 row-cols-xl-4 search-result"></div>

        <div class="row g-4 mt-0 row-cols-1 row-cols-lg-2 row-cols-xl-4" id="no-search">
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
    (function () {
        var toggleDate = function () {
            $('#exportDate').toggle($('#exportPeriod').val() !== 'all');
        };
        $('#exportPeriod').on('change', toggleDate);
        toggleDate();
    })();

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
