<x-admin-layout>
@push('css-view')
<link href="{{ asset('admin/plugins/Drag-And-Drop/dist/imageuploadify.min.css') }}" rel="stylesheet" />
@endpush

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.customer', ['param'=>'s'])</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#customer-add"><i class="bx bx-buildings"></i> @lang('lang.new_customer')</a>
        <a class="btn btn-sm btn-dark" href="{{ route('partners.report') }}"><i class="bx bx-printer"></i> @lang('lang.customer', ['param'=>'s'])</a>
    </div>
</div>
<hr/>
<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body">
        <ul class="nav nav-tabs nav-default" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#card-customers" role="tab" aria-selected="true">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-card font-18 me-1'></i>
                        </div>
                        <div class="tab-title">@lang('lang.card', ['param'=>'s'])</div>
                    </div>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-table font-18 me-1'></i>
                        </div>
                        <div class="tab-title">@lang('lang.table', ['param'=>'s'])</div>
                    </div>
                </a>
            </li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="card-customers" role="tabpanel">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 product-grid">
                    @foreach ($customers as $item)
                    <div class="col-12 col-lg-3">
                        <div class="card border-dark border-bottom border-3 radius-15">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="font-30 text-danger"><i class='bx bxs-folder'></i>
                                    </div>
                                    <div class="user-groups ms-auto">
                                        <img src="{{ asset('images/entreprise.png') }}" width="35" height="35" class="rounded-circle" alt="" />
                                    </div>
                                    <a href="{{ route('customers.show', $item->id) }}" class="user-plus" title="@lang('lang.see_more')"><i class="bx bx-plus"></i></a>
                                </div>
                                <h6 class="mb-0 text-dark">{{ $item->name }}</h6>
                                <small><i class="bx bx-group"></i> {{ $item->affectations_count }} @lang('lang.employee', ['param'=>'s']) | <i class="bx bx-phone"></i> {{ $item->phone }}</small>
                                <div class="mt-2">
                                    <a href="{{ route('prints.bill.annual', [$item->id, date('Y')]) }}" class="btn btn-xs btn-warning" title="Facture annuelle {{ date('Y') }}">
                                        <i class="bx bx-calendar-check me-1"></i> Facture Annuelle {{ date('Y') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade" id="table-customer" role="tabpanel">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <th>@lang('lang.name')</th>
                                    <th>@lang('lang.phone_id')</th>
                                    <th>@lang('lang.email')</th>
                                    <th>@lang('lang.address')</th>
                                    <th>@lang('lang.responsible')</th>
                                    <th>@lang('lang.amount')</th>
                                    <th>@lang('lang.duration') @lang('lang.contract', ['param'=>''])</th>
                                    <th>@lang('lang.affectation', array('param'=>'s'))</th>
                                    <th>@lang('lang.action', ['param'=>'s'])</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->address }}</td>
                                    <td>{{ $item->responsible }}</td>
                                    <td>{{ moneyFormat($item->amount ?? 0) }}</td>
                                    <td>{{ $item->start_date." - ".$item->end_date }}</td>
                                    <td>{{ $item->affectations_count }} @lang('lang.employee', ['param'=>'s'])</td>
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#customer{{ $item->id }}" class="btn btn-xs btn-primary" title="Editer les informations" style="display: inline-block">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <x-customer-edit :customer="$item" />
                                        <a href="{{ route('prints.bill.annual', [$item->id, date('Y')]) }}" class="btn btn-xs btn-warning" title="Facture annuelle {{ date('Y') }}" style="display: inline-block">
                                            <i class="bx bx-calendar-check"></i>
                                        </a>
                                        <form action="{{ route('customers.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-xs btn-danger" title="Supprimer cet Employé">
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

<x-customer-add />

@push('js-view')
<script src="{{ asset('admin/plugins/Drag-And-Drop/dist/imageuploadify.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.uploadify').imageuploadify();
    } );
</script>
@endpush
</x-admin-layout>
