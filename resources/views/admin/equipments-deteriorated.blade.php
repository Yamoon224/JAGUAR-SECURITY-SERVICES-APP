<x-admin-layout>

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" href="{{ route('equipments.index') }}"><i class="bx bx-customize"></i> @lang('lang.equipment', ['param'=>'s']) @lang('lang.available')</a>
    </div>
</div>
<hr/>
<div class="card border-dark border-bottom border-3">
    <div class="card-body">
        <ul class="nav nav-tabs nav-default" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-error font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.deteriorated_equipment')</div>
                    </div>
                </a>
            </li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="table-customer" role="tabpanel">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <th>@lang('lang.category', ['param'=>''])</th>
                                    <th>@lang('lang.name')</th>
                                    <th>@lang('lang.qty')</th>
                                    <th>@lang('lang.deteriorated_qty')</th>
                                    <th>@lang('lang.action', ['param'=>'s'])</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($equipments as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->category?->name ?? '—' }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->qty." ".$item->unit }}</td>
                                    <td>{{ $item->deteriorated_qty." ".$item->unit }}</td>
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#equipment{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer les informations" style="display: inline-block">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <x-equipment-edit :categories="$categories" :equipment="$item" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aucun équipement détérioré</td>
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
</x-admin-layout>
