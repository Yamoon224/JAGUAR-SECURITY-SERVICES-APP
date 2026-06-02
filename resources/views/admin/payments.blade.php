<x-admin-layout>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-xl-4 col-sm-4 col-xs-12">
                        <a class="mb-3 mb-lg-0"><i class='bx bx-list'></i>@lang('lang.employee', ['param'=>'s'])</a>
                    </div>
                    <div class="col-lg-8 col-xl-8 col-sm-8 col-xs-12">
                        <form class="float-lg-end">
                            <div class="row row-cols-lg-2 row-cols-xl-auto g-2">
                                <div class="col">
                                    <div class="input-group">
                                        <label class="input-group-text bg-dark text-white" for="methodField"><i class="bx bx-money"></i></label>
                                        <select class="form-select" id="methodField">
                                            @foreach (['' => 'COMPTABILITE', 'ACCESS BANK GUINEE' => 'ACCESS BANK GUINEE', 'BANQUE ISLAMIQUE DE GUINEE' => 'BIG', 'DIAMA BANK' => 'DIAMA BANK', '*' => 'TOUS LES SALARIES'] as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
<div class="card border-dark border-bottom border-3">
    <div class="card-body" id="payment-content"></div>
</div>

<script>
    $('#payment-content').load("{{ route('payments.monthly') }}", {'bank': '', 'bankname': 'COMPTABILITE', 'year':"{{ date('Y') }}", 'month_id':"{{ date('m') }}", 'monthname':"{{ __('lang.'.getMonthName(date('m'))) }}", '_token':"{{ csrf_token() }}"});
    $('#monthField').on('change', function () {
        let selected = $(this).children('option:selected'),
            month_id = selected.val(),
            monthname = selected.text(),
            
            year = $('#yearField').children('option:selected').val(),
            
            methodSelected = $('#methodField').children('option:selected'),
            method = methodSelected.val(),
            methodname = methodSelected.text();
        $('#payment-content').load("{{ route('payments.monthly') }}", {'bank': method, 'bankname': methodname, 'year': year, 'month_id':month_id, 'monthname':monthname, '_token':"{{ csrf_token() }}"});
    })
    $('#methodField').on('change', function () {
        let selected = $(this).children('option:selected'),
            method = selected.val(),
            methodname = selected.text(),
            
            year = $('#yearField').children('option:selected').val(),
            
            monthSelected = $('#monthField').children('option:selected'),
            month_id = monthSelected.val(),
            monthname = monthSelected.text();
        $('#payment-content').load("{{ route('payments.monthly') }}", {'bank': method, 'bankname': methodname, 'year': year, 'month_id':month_id, 'monthname':monthname, '_token':"{{ csrf_token() }}"});
    })
    $('#yearField').on('change', function () {
        let selected = $(this).children('option:selected'),
            year = selected.val(),
            
            method = $('#methodField').children('option:selected').val(),
            methodname = $('#methodField').children('option:selected').text(),
            
            monthSelected = $('#monthField').children('option:selected'),
            month_id = monthSelected.val(),
            monthname = monthSelected.text();
        $('#payment-content').load("{{ route('payments.monthly') }}", {'bank': method, 'bankname': methodname, 'year': year, 'month_id':month_id, 'monthname':monthname, '_token':"{{ csrf_token() }}"});
    })
</script>
</x-admin-layout>
