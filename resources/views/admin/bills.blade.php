<x-admin-layout>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-xl-2">
                        <a class="btn btn-dark mb-3 mb-lg-0"><i class='bx bx-blanket'></i>@lang('lang.bill', ['param'=>'s'])</a>
                    </div>
                    <div class="col-lg-9 col-xl-10">
                        <form class="float-lg-end">
                            <div class="row row-cols-lg-2 row-cols-md-2 row-cols-sm-2 row-cols-xl-auto g-2">
                                <div class="col">
                                    <div class="input-group">
                                        <label class="input-group-text bg-dark text-white" for="yearField"><i class="bx bx-calendar-check"></i></label>
                                        <select class="form-select" id="yearField">
                                            @foreach ([date('Y') - 2, date('Y') - 1, date('Y')] as $item)
                                            <option value="{{ $item }}" {{ $item == date('Y') ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
    <div class="card-body" id="bill-content"></div>
</div>

@push('js-view')
<script>
    $('#bill-content').load("{{ route('bills.monthly') }}", {'month_id':"{{ date('m') }}", 'monthname':"{{ __('lang.'.getMonthName(date('m'))) }}", 'year':"{{ date('Y') }}", '_token':"{{ csrf_token() }}"});
    $('#monthField').on('change', function () {
        let month_id = $(this).children('option:selected').val(),
            year = $('#yearField').children('option:selected').val(),
            monthname = $(this).children('option:selected').text();
        $('#bill-content').load("{{ route('bills.monthly') }}", {'month_id':month_id, 'monthname':monthname, 'year':year, '_token':"{{ csrf_token() }}"});
    })
    $('#yearField').on('change', function () {
        let month_id = $('#monthField').children('option:selected').val(),
            year = $(this).children('option:selected').val(),
            monthname = $('#monthField').children('option:selected').text();
        $('#bill-content').load("{{ route('bills.monthly') }}", {'month_id':month_id, 'monthname':monthname, 'year':year, '_token':"{{ csrf_token() }}"});
    })
</script>
@endpush
</x-admin-layout>
