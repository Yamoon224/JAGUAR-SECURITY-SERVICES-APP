<x-admin-layout>

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.dotation', ['param'=>'s'])</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#dotation-add"><i class="bx bx-user-plus"></i> @lang('lang.new_dotation')</a>
    </div>
</div>
<hr/>
<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body">
        <ul class="nav nav-tabs nav-default" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-mail-send font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.dotation', ['param'=>'s'])</div>
                    </div>
                </a>
            </li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="table-customer" role="tabpanel">
                <div class="col">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <th>@lang('lang.employee', ['param'=>''])</th>
                                    <th>@lang('lang.equipment', ['param'=>''])</th>
                                    <th>@lang('lang.qty')</th>
                                    <th>@lang('lang.action', ['param'=>'s'])</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dotations as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->employee->firstname." ".$item->employee->name." | ".$item->employee->position." | ".$item->employee->phone }}</td>
                                    <td>{{ $item->equipment->name }}</td>
                                    <td>{{ $item->qty." ".$item->equipment->unit }}</td>
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#dotation{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer les informations" style="display: inline-block">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <x-dotation-edit :employees="$employees" :equipments="$equipments" :dotation="$item" />
            
                                        <form action="{{ route('dotations.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Supprimer cette catégorie"><i class="bx bx-trash"></i></button>
                                        </form>
                                    </td>
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

<x-dotation-add :employees="$employees" :equipments="$equipments" />
</x-admin-layout>
