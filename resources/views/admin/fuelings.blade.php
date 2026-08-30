<x-admin-layout>

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.fueling', ['param'=>'s'])</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#fueling-add"><i class="bx bx-gas-pump"></i> @lang('lang.new_fueling')</a>
    </div>
</div>
<hr/>
<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body">
        <ul class="nav nav-tabs nav-default" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#table-fuelings" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-gas-pump font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.fueling', ['param'=>'s'])</div>
                    </div>
                </a>
            </li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="table-fuelings" role="tabpanel">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <th>@lang('lang.fueling_date')</th>
                                    <th>@lang('lang.fuel_type')</th>
                                    <th>@lang('lang.fuel_volume')</th>
                                    <th>@lang('lang.beneficiary_matricule')</th>
                                    <th>@lang('lang.beneficiary_function')</th>
                                    <th>@lang('lang.station_name')</th>
                                    <th>@lang('lang.vehicle_type')</th>
                                    <th>@lang('lang.voucher_number')</th>
                                    <th>@lang('lang.action', ['param'=>'s'])</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fuelings as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->fueled_at)->format('d/m/Y H:i') }}</td>
                                    <td class="text-capitalize">{{ $item->fuel_type }}</td>
                                    <td>{{ $item->volume }} L</td>
                                    <td>{{ $item->beneficiary_matricule }}</td>
                                    <td>{{ $item->beneficiary_function }}</td>
                                    <td>{{ $item->station_name }}</td>
                                    <td class="text-capitalize">{{ $item->vehicle_type }}</td>
                                    <td>{{ $item->voucher_number }}</td>
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#fueling{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer cette carburation" style="display: inline-block">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <x-fueling-edit :fueling="$item" />

                                        <form action="{{ route('fuelings.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Supprimer cette carburation" onclick="if(!confirm('Confirmez-Vous cette suppression')) return false"><i class="bx bx-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Aucune carburation enregistrée</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-fueling-add />
</x-admin-layout>
