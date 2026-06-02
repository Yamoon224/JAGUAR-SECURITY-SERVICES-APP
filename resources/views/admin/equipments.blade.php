<x-admin-layout>

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#equipment-add"><i class="bx bx-user-plus"></i> @lang('lang.new_equipment')</a>
    </div>
</div>
<hr/>
<div class="card border-dark border-bottom border-3">
    <div class="card-body">
        <ul class="nav nav-tabs nav-default" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-customize font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.equipment', ['param'=>'s'])</div>
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
                                    <th>@lang('lang.price')</th>
                                    <th>@lang('lang.qty')</th>
                                    <th>@lang('lang.available')</th>
                                    <th>@lang('lang.action', ['param'=>'s'])</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($equipments as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->qty." ".$item->unit }}</td>
                                    <td>{{ ($item->qty - $item->dotations->sum('qty'))." ".$item->unit }}</td>
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#equipment{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer les informations" style="display: inline-block">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <x-equipment-edit :categories="$categories" :equipment="$item" />
            
                                        <form action="{{ route('equipments.destroy', $item->id) }}" method="POST" style="display: inline-block">
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

<x-equipment-add :categories="$categories" />
</x-admin-layout>
