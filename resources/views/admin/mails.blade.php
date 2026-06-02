<x-admin-layout>
@push('css-view')
<link href="{{ asset('admin/plugins/Drag-And-Drop/dist/imageuploadify.min.css') }}" rel="stylesheet" />
@endpush

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#mail-add"><i class="bx bx-mail-send"></i> @lang('lang.new_mail')</a>
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
                        <div class="tab-title">@lang('lang.mail', ['param'=>'s'])</div>
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
                                    <th>@lang('lang.mail_datetime')</th>
                                    <th>@lang('lang.mail_id')</th>
                                    <th>@lang('lang.type')</th>
                                    <th>@lang('lang.srce')</th>
                                    <th>@lang('lang.dest')</th>
                                    <th>@lang('lang.subject')</th>
                                    <th>@lang('lang.observation')</th>
                                    <th>@lang('lang.action', ['param'=>'s'])</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mails as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('d/m/Y H:i:s', strtotime($item->mail_datetime)) }}</td>
                                    <td>{{ $item->mail_id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->srce }}</td>
                                    <td>{{ $item->destinator }}</td>
                                    <td>{{ $item->subject }}</td>
                                    <td>{{ $item->observation }}</td>
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#mail{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer les informations" style="display: inline-block">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <x-mail-edit :mail="$item" />
            
                                        <form action="{{ route('mails.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                            @csrf  @method('DELETE')
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

<x-mail-add />
</x-admin-layout>
