<x-admin-layout>
@push('css-view')
<link href="{{ asset('admin/plugins/Drag-And-Drop/dist/imageuploadify.min.css') }}" rel="stylesheet" />
@endpush

<div class="col">
    <div class="d-none d-sm-flex align-items-center">
        <div class="ms-auto">
            <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#meet-add"><i class="bx bx-user-plus"></i> @lang('lang.new_meet')</a>
        </div>
    </div>
    <hr/>
    <div class="card border-dark border-bottom border-3 border-0">
        <div class="card-body">
            <ul class="nav nav-tabs nav-default" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-calendar-check font-18 me-1'></i></div>
                            <div class="tab-title">@lang('lang.meet', ['param'=>'s'])</div>
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
                                        <th>@lang('lang.object')</th>
                                        <th>@lang('lang.points')</th>
                                        <th>@lang('lang.file_path')</th>
                                        <th>@lang('lang.action', ['param'=>'s'])</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($meets as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->object }}</td>
                                        <td>{{ $item->points }}</td>
                                        <td><a href="{{ asset('storage/'.$item->file_path) }}"><i class="bx bx-file"></i></a></td>
                                        <td>
                                            <a data-bs-toggle="modal" data-bs-target="#meet{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer les informations" style="display: inline-block">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <x-meet-edit :meet="$item" />
                
                                            <form action="{{ route('meets.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="Supprimer cet Employé">
                                                    <i class="bx bx-trash"></i>
                                                </button>
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
</div>

<x-meet-add />

@push('js-view')
<script src="{{ asset('admin/plugins/Drag-And-Drop/dist/imageuploadify.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.uploadfile').imageuploadify();
    });
</script>
@endpush
</x-admin-layout>
