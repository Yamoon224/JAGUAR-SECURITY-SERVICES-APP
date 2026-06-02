<x-admin-layout>
<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.hire', ['param'=>'s'])</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" href="{{ route('hires.affectations') }}"><i class="bx bx-printer"></i> @lang('lang.hire', ['param'=>'s'])</a>
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
                        <th>@lang('lang.date')</th>
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
                        <td>{{ date('d/m/Y H:i:s', strtotime($item->updated_at)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@push('js-view')
<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable( {lengthChange: false, buttons: [ 'copy', 'excel', 'pdf', 'print']} );
        table.buttons().container().appendTo( '#example2_wrapper .col-md-6:eq(0)' );
    } );
</script>
@endpush
</x-admin-layout>
