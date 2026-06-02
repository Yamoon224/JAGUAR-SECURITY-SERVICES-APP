<x-admin-layout>
@push('css-view')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
@endpush
<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.recruitment', ['param'=>'s'])</h6>
    <div class="ms-auto">
        @if($recruitments->sortByDesc('end_date')->first()->end_date < date('Y-m-d'))
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#recruitment-add"><i class="bx bx-user-plus"></i> @lang('lang.new_recruitment')</a>
        @endif
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
                        <th>@lang('lang.title')</th>
                        <th>@lang('lang.start_date')</th>
                        <th>@lang('lang.end_date')</th>
                        <th>@lang('lang.description')</th>
                        <th>@lang('lang.path')</th>
                        <th>@lang('lang.action', ['param'=>'s'])</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recruitments as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->start_date }}</td>
                        <td>{{ $item->end_date }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            @if($item->path)
                            <a href="{{ asset('storage/'.$item->path) }}" class="btn btn-sm btn-dark"><i class="bx bx-folder-plus"></i></a>
                            @endif
                        </td>
                        <td>
                            <a data-bs-toggle="modal" data-bs-target="#recruitment{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer les informations" style="display: inline-block">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <x-recruitment-edit :recruitment="$item" />

                            <form action="{{ route('recruitments.destroy', $item->id) }}" method="POST" style="display: inline-block">
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

<x-recruitment-add></x-recruitment-add>

@push('js-view')
<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable( {lengthChange: false, buttons: [ 'copy', 'excel', 'pdf', 'print']} );
        table.buttons().container().appendTo( '#example2_wrapper .col-md-6:eq(0)' );
    } );
</script>
@endpush
</x-admin-layout>
