<x-admin-layout>
@push('css-view')
<link href="{{ asset('assets/admin/plugins/highcharts/css/highcharts.css') }}" rel="stylesheet" />
@endpush
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <a class="btn btn-dark mb-3 mb-lg-0"><i class='bx bx-desktop'></i>@lang('lang.dashboard')</a>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <a class="btn btn-dark mb-3 mb-lg-0" href="{{ route('prints.wifi') }}"><i class='bx bx-wifi'></i>@lang('lang.wifi_access')</a>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <form class="float-lg-end">
                            <div class="row row-cols-lg-2 row-cols-md-2 row-cols-sm-12 row-cols-12 g-2">
                                <div class="col-12">
                                    <div class="input-group">
                                        <label class="input-group-text bg-dark text-white" for="monthField"><i class="bx bx-calendar-check"></i></label>
                                        <select class="form-select" id="monthField">
                                            @for ($monthIndex = 1; $monthIndex <= 12; $monthIndex++)
                                            <option value="{{ $monthIndex }}" {{ $monthIndex == date('m') ? 'selected' : '' }}>@lang('lang.'.getMonthName($monthIndex))</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-group">
                                        <label class="input-group-text bg-dark text-white" for="yearField"><i class="bx bx-calendar-check"></i></label>
                                        <select class="form-select" id="yearField">
                                            @foreach ([date('Y') - 2, date('Y') - 1, date('Y')] as $item)
                                            <option value="{{ $item }}" {{ $item == date('Y') ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
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

@push('js-view')
<script>
    $('#dash').load("{{ route('dash') }}", {'month_id':"{{ date('m') }}", 'monthname':"{{ __('lang.'.getMonthName(date('m'))) }}", 'year':"{{ date('Y') }}", '_token':"{{ csrf_token() }}"});
    $('#monthField').on('change', function () {
        let month_id = $(this).children('option:selected').val(),
            year = $('#yearField').children('option:selected').val(),
            monthname = $(this).children('option:selected').text();
        $('#dash').load("{{ route('dash') }}", {'month_id':month_id, 'monthname':monthname, 'year':year, '_token':"{{ csrf_token() }}"});
    })
    $('#yearField').on('change', function () {
        let month_id = $('#monthField').children('option:selected').val(),
            year = $(this).children('option:selected').val(),
            monthname = $('#monthField').children('option:selected').text();
        $('#dash').load("{{ route('dash') }}", {'month_id':month_id, 'monthname':monthname, 'year':year, '_token':"{{ csrf_token() }}"});
    })
</script>
@endpush
</x-admin-layout>


