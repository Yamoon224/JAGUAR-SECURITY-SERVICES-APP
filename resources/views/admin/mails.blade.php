<x-admin-layout>

<div class="d-sm-flex align-items-center flex-wrap gap-2 mb-1">
    <div>
        <h5 class="mb-0 text-uppercase">@lang('lang.mail', ['param'=>'s'])</h5>
        <small class="text-muted">{{ $mails->count() }} @lang('lang.mail', ['param'=>'s'])</small>
    </div>
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#mail-add"><i class="bx bx-mail-send"></i> @lang('lang.new_mail')</a>
    </div>
</div>
<hr/>

<div class="row g-2 mb-3">
    <div class="col-6 col-lg-3">
        <div class="card radius-15 mb-0 border-dark border-bottom border-3">
            <div class="card-body py-2 text-center">
                <h4 class="mb-0">{{ $mails->count() }}</h4>
                <small class="text-muted text-uppercase">Total</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card radius-15 mb-0 border-success border-bottom border-3">
            <div class="card-body py-2 text-center">
                <h4 class="mb-0 text-success">{{ $mails->where('name', 'ARRIVEE')->count() }}</h4>
                <small class="text-muted text-uppercase">Arrivée</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card radius-15 mb-0 border-primary border-bottom border-3">
            <div class="card-body py-2 text-center">
                <h4 class="mb-0 text-primary">{{ $mails->where('name', 'DEPART')->count() }}</h4>
                <small class="text-muted text-uppercase">Départ</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card radius-15 mb-0 border-dark border-bottom border-3">
            <div class="card-body py-2 text-center">
                <h4 class="mb-0">{{ optional($mails->first())->mail_datetime ? \Carbon\Carbon::parse($mails->first()->mail_datetime)->format('d/m/Y') : '—' }}</h4>
                <small class="text-muted text-uppercase">Dernier courrier</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-dark border-bottom border-3 border-0">
    <div class="card-body">
        <div class="input-group input-group-sm mb-3" style="max-width: 320px">
            <span class="input-group-text"><i class="bx bx-search-alt"></i></span>
            <input type="text" id="mailSearch" class="form-control" placeholder="@lang('lang.search')...">
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-hover w-100">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>@lang('lang.mail_id')</th>
                        <th>@lang('lang.mail_datetime')</th>
                        <th>@lang('lang.type')</th>
                        <th>@lang('lang.srce')</th>
                        <th>@lang('lang.dest')</th>
                        <th>@lang('lang.subject')</th>
                        <th class="text-end">@lang('lang.action', ['param'=>'s'])</th>
                    </tr>
                </thead>
                <tbody id="mailRows">
                    @forelse ($mails as $item)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td><span class="fw-semibold">{{ $item->mail_id }}</span></td>
                        <td>{{ $item->mail_datetime ? \Carbon\Carbon::parse($item->mail_datetime)->format('d/m/Y H:i') : '—' }}</td>
                        <td>
                            @if($item->name === 'ARRIVEE')
                                <span class="badge bg-success"><i class="bx bx-down-arrow-alt"></i> Arrivée</span>
                            @else
                                <span class="badge bg-primary"><i class="bx bx-up-arrow-alt"></i> Départ</span>
                            @endif
                        </td>
                        <td>{{ $item->srce }}</td>
                        <td>{{ $item->destinator }}</td>
                        <td>
                            <div>{{ $item->subject }}</div>
                            @if($item->observation)
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($item->observation, 60) }}</small>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a data-bs-toggle="modal" data-bs-target="#mail{{ $item->id }}" class="btn btn-sm btn-outline-primary" title="@lang('lang.edit')"><i class="bx bx-edit-alt"></i></a>
                            <x-mail-edit :mail="$item" />
                            <form action="{{ route('mails.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="@lang('lang.delete')" onclick="return confirm('Confirmez-Vous cette suppression')"><i class="bx bx-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bx bx-mail-send" style="font-size:2rem"></i>
                            <div>Aucun courrier enregistré</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<x-mail-add />

@push('js-view')
<script>
    $('#mailSearch').on('keyup', function () {
        var q = $(this).val().toLowerCase();
        $('#mailRows tr').each(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(q) > -1);
        });
    });
</script>
@endpush
</x-admin-layout>
