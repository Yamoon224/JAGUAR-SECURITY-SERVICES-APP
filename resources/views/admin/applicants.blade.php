<x-admin-layout>
@push('css-view')
<link href="{{ asset('admin/plugins/Drag-And-Drop/dist/imageuploadify.min.css') }}" rel="stylesheet" />
@endpush

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.applicant', ['param'=>'s'])</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#applicant-add"><i class="bx bx-user-plus"></i> @lang('lang.new_applicant')</a>
        <a class="btn btn-sm btn-danger" href="{{ route('applicants.report') }}"><i class="bx bx-printer"></i> @lang('lang.applicant', ['param'=>'s'])</a>
    </div>
</div>
<hr/>
<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td>#</td>
                        <th>@lang('lang.firstname') & @lang('lang.name')</th>
                        <th>@lang('lang.phone_id')</th>
                        <th>@lang('lang.address')</th>
                        <th>@lang('lang.application_id')</th>
                        <th>Type</th>
                        <th>@lang('lang.status')</th>
                        <th>@lang('lang.application_file')</th>
                        <th>@lang('lang.action', ['param'=>'s'])</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applicants as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->firstname." ".$item->name }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->address }}</td>
                        <td>{{ $item->applicationid }}</td>
                        <td>{{ $item->recruitment_id != 0 ? date('d/m/Y H:i:s', strtotime($item->created_at)) : 'Ajouté' }}</td>
                        <td>{{ $item->status }}</td>
                        <td><a href="{{ asset('storage/'.$item->path) }}" class="btn btn-sm btn-dark"><i class="bx bx-folder-plus"></i></a></td>
                        <td>
                            @if($item->status == 'inprogress')
                            <a data-bs-toggle="modal" data-bs-target="#applicant{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer les informations" style="display: inline-block">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <x-applicant-edit :applicant="$item" />

                            <form action="{{ route('applicants.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Supprimer cet Employé">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<x-applicant-add />

@push('js-view')
<script src="{{ asset('admin/plugins/Drag-And-Drop/dist/imageuploadify.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable( {lengthChange: false, buttons: [ 'copy', 'excel', 'pdf', 'print']} );
        table.buttons().container().appendTo( '#example2_wrapper .col-md-6:eq(0)' );

		$('#image-uploadify').imageuploadify();
    } );
</script>
@endpush
</x-admin-layout>
