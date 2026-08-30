<x-admin-layout>
@push('css-view')
<link href="{{ asset('admin/plugins/highcharts/css/highcharts.css') }}" rel="stylesheet" />
@endpush
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <a class="btn btn-dark mb-3 mb-lg-0"><i class='bx bx-desktop'></i>@lang('lang.dashboard')</a>
                    </div>
                    <div class="col-6">
                        <form class="float-lg-end">
                            <div class="row row-cols-lg-2 row-cols-xl-auto g-2">
                                <div class="col">
                                    <div class="input-group">
                                        <label class="input-group-text bg-dark text-white" for="monthField"><i class="bx bx-calendar-check"></i></label>
                                        <select class="form-select" id="monthField">
                                            @for ($monthIndex = 1; $monthIndex <= 12; $monthIndex++)
                                            <option value="{{ $monthIndex }}" {{ $monthIndex == date('m') ? 'selected' : '' }}>@lang('lang.'.getMonthName($monthIndex))</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body" id="dash"></div>
</div>

<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body">
        <div class="d-sm-flex align-items-center mb-3">
            <h6 class="mb-0 text-uppercase">@lang('lang.employee', ['param'=>'s'])</h6>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6 col-md-6">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bx bx-search-alt"></i></span>
                    <input type="text" class="form-control" id="searchKey" placeholder="@lang('lang.search') (@lang('lang.name'), @lang('lang.firstname'), @lang('lang.matricule'))">
                </div>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4 search-result"></div>
        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4" id="no-search">
            @foreach ($employees->items() as $item)
            <div class="col d-flex">
                <div class="card border-bottom border-dark radius-15 h-100 w-100">
                    <div class="card-body p-3 text-center d-flex flex-column align-items-center">
                        <img src="{{ asset($item->photo ?? 'images/avatar.png') }}" width="110" height="110" class="rounded-circle shadow" alt="">
                        <h6 class="mb-0 mt-2">{{ $item->firstname." ".$item->name }}</h6>
                        <p class="mb-1">{{ $item->position." ".$item->matricule }}</p>
                        <div class="list-inline mb-2">
                            <a href="tel:{{ $item->phone }}" class="list-inline-item text-dark"><i class="bx bx-phone"></i> {{ $item->phone }}</a>
                        </div>
                        @if(isRightAccess([1, 2]))
                        <div class="d-grid mt-auto w-100">
                            <a href="{{ route('employees.show', $item->id) }}" class="btn btn-outline-dark radius-15">@lang('lang.see_more')</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            <hr class="w-100">
            <x-pagination :paginator="$employees" :count="$count" />
        </div>
    </div>
</div>

@push('js-view')
<script>
    $('#dash').load("{{ route('dash') }}", {'month_id':"{{ date('m') }}", 'monthname':"{{ __('lang.'.getMonthName(date('m'))) }}", '_token':"{{ csrf_token() }}"});
    $('#monthField').on('change', function () {
        let month_id = $(this).children('option:selected').val(),
            monthname = $(this).children('option:selected').text();
        $('#dash').load("{{ route('dash') }}", {'month_id':month_id, 'monthname':monthname, '_token':"{{ csrf_token() }}"});
    })

    $('#searchKey').on('keyup', function () {
        let search = $(this).val();
        if (search != '') {
            $("#no-search").hide();
            $('.search-result').load("{{ route('employees.search') }}", {'_token':"{{ csrf_token() }}", 'search':search});
        } else {
            $('.search-result').html("");
            $("#no-search").show();
        }
    })
</script>
@endpush
</x-admin-layout>


