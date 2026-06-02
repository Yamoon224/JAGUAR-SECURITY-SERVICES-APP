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

@push('js-view')
<script>
    $('#dash').load("{{ route('dash') }}", {'month_id':"{{ date('m') }}", 'monthname':"{{ __('lang.'.getMonthName(date('m'))) }}", '_token':"{{ csrf_token() }}"});
    $('#monthField').on('change', function () {
        let month_id = $(this).children('option:selected').val(),
            monthname = $(this).children('option:selected').text();
        $('#dash').load("{{ route('dash') }}", {'month_id':month_id, 'monthname':monthname, '_token':"{{ csrf_token() }}"});
    })
</script>
@endpush
</x-admin-layout>


