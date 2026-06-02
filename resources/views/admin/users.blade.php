<x-admin-layout>

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.user', ['param'=>'s'])</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#user-add"><i class="bx bx-user-plus"></i> @lang('lang.new_user')</a>
    </div>
</div>
<hr/>
<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body">
        <ul class="nav nav-tabs nav-default" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-group font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.user', ['param'=>'s'])</div>
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
                                    <th>#</th>
                                    <th>@lang('lang.name')</th>
                                    <th>@lang('lang.phone_id')</th>
                                    <th>@lang('lang.email')</th>
                                    <th>@lang('lang.group', ['param'=>''])</th>
                                    <th>@lang('lang.check')</th>
                                    <th>@lang('lang.action', ['param'=>'s'])</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->group->name }}</td>
                                    <td>
                                        <div class="badge bg-{{ is_null($item->email_verified_at) ? 'danger' : 'success' }} rounded-pill w-100">
                                            {{ is_null($item->email_verified_at) ? __('Not Checked') : __('Checked') }}
                                        </div>                            
                                    </td>
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#user{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer les informations" style="display: inline-block">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <x-user-edit :user="$item" :groups="$groups" />
            
                                        <form action="{{ route('users.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Supprimer cet Utilisateur"><i class="bx bx-trash"></i></button>
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

<x-user-add :groups="$groups" />
</x-admin-layout>