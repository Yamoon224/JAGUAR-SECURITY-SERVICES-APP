<x-admin-layout>

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.leaf', ['param'=>'s'])</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#leaf-add"><i class="bx bx-user-plus"></i> @lang('lang.new_leaf')</a>
    </div>
</div>
<hr/>
<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body">
        <ul class="nav nav-tabs nav-default" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-user-minus font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.leaf', ['param'=>'s'])</div>
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
                                    <th>@lang('lang.date')</th>
                                    <th>@lang('lang.employee', ['param'=>''])</th>
                                    <th>@lang('lang.begin')</th>
                                    <th>@lang('lang.end')</th>
                                    <th>@lang('lang.reason')</th>
                                    <th>@lang('lang.action', ['param'=>'s'])</th>
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
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#leaf{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer les informations" style="display: inline-block">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <x-leaf-edit :employees="$employees" :leaf="$item" />
            
                                        <form action="{{ route('leaves.destroy', $item->id) }}" method="POST" style="display: inline-block">
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

<x-leaf-add :employees="$employees" />
</x-admin-layout>
