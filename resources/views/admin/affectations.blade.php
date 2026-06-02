<x-admin-layout>

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.affectation', ['param'=>'s'])</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-primary" href="{{ route('prints.affectations') }}"><i class="bx bx-printer"></i> @lang('lang.print')</a>
    </div>
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#affectation-add"><i class="bx bx-user-plus"></i> @lang('lang.new_affectation')</a>
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
                        <th>@lang('lang.date')</th>
                        <th>@lang('lang.employee', ['param'=>''])</th>
                        <th>@lang('lang.customer', ['param'=>''])</th>
                        <th>@lang('lang.price')</th>
                        <th>@lang('lang.tva')</th>
                        <th>@lang('lang.action', ['param'=>'s'])</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($affectations as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->begin." - ".$item->end }}</td>
                        <td>{{ $item->employee->firstname." ".$item->employee->name." | ".$item->employee->position." | ".$item->employee->phone." | ".$item->employee->matricule }}</td>
                        <td>{{ $item->customer->name." | ".$item->customer->responsible." | ".$item->customer->phone }}</td>
                        <td>{{ moneyFormat($item->price) }}</td>
                        <td>{{ $item->tva }}%</td>
                        <td>
                            <a data-bs-toggle="modal" data-bs-target="#affectation{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer les informations" style="display: inline-block">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <x-affectation-edit  :customers="$customers" :employees="$employees" :affectation="$item" />

                            <form action="{{ route('affectations.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Supprimer cette catégorie"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>#</td>
                        <th>@lang('lang.date')</th>
                        <th>@lang('lang.employee', ['param'=>''])</th>
                        <th>@lang('lang.customer', ['param'=>''])</th>
                        <th>@lang('lang.price')</th>
                        <th>@lang('lang.tva')</th>
                        <th>@lang('lang.action', ['param'=>'s'])</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<x-affectation-add :employees="$employees" :customers="$customers" />

@push('js-view')
<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable( {lengthChange: false, buttons: [ 'copy', 'excel', 'pdf', 'print']} );
        table.buttons().container().appendTo( '#example2_wrapper .col-md-6:eq(0)' );
    } );
</script>
@endpush
</x-admin-layout>
