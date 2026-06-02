<x-admin-layout>

<div class="col">
    <div class="d-none d-sm-flex align-items-center">
        <h6 class="mb-0 text-uppercase">@lang('lang.employee_management')</h6>
        <div class="ms-auto">
            @if(isRightAccess([1, 3]))
            <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#employee-add"><i class="bx bx-user-plus"></i> @lang('lang.new_employee')</a>
            @endif
        </div>
    </div>
    <hr/>
    <div class="card border-dark border-bottom border-3">
        <div class="card-body">
            <ul class="nav nav-tabs nav-default" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-group font-18 me-1'></i>
                            </div>
                            <div class="tab-title">@lang('lang.employee', ['param'=>'s'])</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#affectation" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-user-check font-18 me-1'></i>
                            </div>
                            <div class="tab-title">@lang('lang.affectation', ['param'=>'s'])</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#dotation" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-user-plus font-18 me-1'></i>
                            </div>
                            <div class="tab-title">@lang('lang.dotation', ['param'=>'s'])</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#leave" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-user-minus font-18 me-1'></i>
                            </div>
                            <div class="tab-title">@lang('lang.leaf', ['param'=>'s'])</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#suspension" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-user-minus font-18 me-1'></i>
                            </div>
                            <div class="tab-title">@lang('lang.suspension', ['param'=>'s'])</div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="successhome" role="tabpanel">
                    <div class="row">
                        <div class="col-12 col-lg-6 col-md-6">
                            <div class="input-group input-group mb-3"> <span class="input-group-text" id="inputGroup-sizing"><i class="bx bx-search-alt"></i></span>
                				<input type="text" class="form-control" id="searchKey" aria-label="Sizing example input" aria-describedby="inputGroup-sizing" placeholder="@lang('lang.search')">
                			</div>
                        </div>
                    </div>
                    <div class="row search-result"></div>
                    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4" id="no-search">
                        @foreach ($employees->items() as $item)
                        <div class="col">
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
                        @endforeach

                        <hr class="w-100">
                        <x-pagination :paginator="$employees" :count="$count" />
                    </div>
                </div>

                <div class="tab-pane fade" id="affectation" role="tabpanel">
                    @if(isRightAccess([1]))
                    <a class="btn btn-sm btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#affectation-add"><i class="bx bx-user-plus"></i> @lang('lang.new_affectation')</a>
                    <hr/>
                    @endif
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <th>@lang('lang.date')</th>
                                        <th>@lang('lang.employee', ['param'=>''])</th>
                                        <th>@lang('lang.customer', ['param'=>''])</th>
                                        <th>@lang('lang.price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($affectations as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->employee->firstname." ".$item->employee->name." | ".$item->employee->position." | ".$item->employee->phone }}</td>
                                        <td>{{ $item->customer->name." | ".$item->customer->responsible." | ".$item->customer->phone }}</td>
                                        <td>{{ moneyFormat($item->price) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>     
                </div> 

                <div class="tab-pane fade" id="dotation" role="tabpanel">
                    @if(isRightAccess([1, 5]))
                    <a class="btn btn-sm btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#dotation-add"><i class="bx bx-user-plus"></i> @lang('lang.new_dotation')</a>
                    <hr/>
                    @endif
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <th>@lang('lang.employee', ['param'=>''])</th>
                                        <th>@lang('lang.equipment', ['param'=>''])</th>
                                        <th>@lang('lang.qty')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dotations as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->employee->firstname." ".$item->employee->name." | ".$item->employee->position." | ".$item->employee->phone }}</td>
                                        <td>{{ $item->equipment->name }}</td>
                                        <td>{{ $item->qty." ".$item->equipment->unit }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="leave" role="tabpanel">
                    @if(isRightAccess([1, 3]))
                    <a class="btn btn-sm btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#leaf-add"><i class="bx bx-user-plus"></i> @lang('lang.new_leaf')</a>
                    <hr/>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <th>@lang('lang.date')</th>
                                    <th>@lang('lang.employee', ['param'=>''])</th>
                                    <th>@lang('lang.begin')</th>
                                    <th>@lang('lang.end')</th>
                                    <th>@lang('lang.reason')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaves as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->employee->firstname." ".$item->employee->name." | ".$item->employee->position." | ".$item->employee->phone }}</td>
                                    <td>{{ $item->begin }}</td>
                                    <td>{{ $item->end }}</td>
                                    <td>{{ $item->reason }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="suspension" role="tabpanel">
                    @if(isRightAccess([1, 3]))
                    <a class="btn btn-sm btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#suspension-add"><i class="bx bx-user-plus"></i> @lang('lang.new_suspension')</a>
                    <hr/>
                    @endif
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <th>@lang('lang.date')</th>
                                        <th>@lang('lang.employee', ['param'=>''])</th>
                                        <th>@lang('lang.duration')</th>
                                        <th>@lang('lang.reason')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suspensions as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->employee->firstname." ".$item->employee->name." | ".$item->employee->position." | ".$item->employee->phone }}</td>
                                        <td>{{ $item->duration." ".$item->unit }}</td>
                                        <td>{{ $item->reason }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div>

@if(isRightAccess([1, 3]))
<x-employee-add :applicants="$applicants" />
<x-suspension-add :employees="$employees" />
<x-leaf-add :employees="$employees" />
@endif

<x-dotation-add :employees="$employees" :equipments="$equipments" />

@if(isRightAccess([1]))
<x-affectation-add :employees="$employees" :customers="$customers" />
@endif

@push('js-view')
<script>
    $('#searchKey').on('keyup', function () {
        let search = $(this).val();
        if(search != '') {
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
