<x-admin-layout>
<div class="col">
    <div class="d-none d-sm-flex align-items-center">
        <div class="ms-auto">
            <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#appointment-add"><i class="bx bx-calendar"></i> @lang('lang.new_appointment')</a>
            <a class="btn btn-sm btn-dark" href="{{ route('appointments.report') }}"><i class="bx bx-printer"></i> @lang('lang.appointment', ['param'=>'s'])</a>
        </div>
    </div>
    <hr/>
    <div class="card border-dark border-bottom border-3 border-0">
        <div class="card-body">
            <ul class="nav nav-tabs nav-default" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#table-customer" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-calendar font-18 me-1'></i></div>
                            <div class="tab-title">@lang('lang.appointment', ['param'=>'s'])</div>
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
                                        <th>@lang('lang.expected_at')</th>
                                        <th>@lang('lang.start_time')</th>
                                        <th>@lang('lang.end_time')</th>
                                        <th>@lang('lang.visitor')</th>
                                        <th>@lang('lang.phone_id')</th>
                                        <th>@lang('lang.company')</th>
                                        <th>@lang('lang.action', ['param'=>'s'])</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appointments as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d/m/Y', strtotime($item->expected_at)) }}</td>
                                        <td>{{ date('H:i', strtotime($item->start_time)) }}</td>
                                        <td>{{ date('H:i', strtotime($item->end_time)) }}</td>
                                        <td>{{ $item->visitor }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->company }}</td>
                                        <td>
                                            <a data-bs-toggle="modal" data-bs-target="#appointment{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer les informations" style="display: inline-block">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <x-appointment-edit :appointment="$item" />
                
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

<x-appointment-add></x-appointment-add>
</x-admin-layout>
