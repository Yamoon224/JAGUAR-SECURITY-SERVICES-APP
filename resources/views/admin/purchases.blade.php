<x-admin-layout>

<div class="page-breadcrumb d-none d-sm-flex align-items-center">
    <h6 class="breadcrumb-title pe-3 text-uppercase">@lang('lang.material_supply')</h6>
    <div class="ms-auto">
        <a class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#purchase-add"><i class="bx bx-cart-add"></i> @lang('lang.new_purchase')</a>
    </div>
</div>
<hr/>
<div class="card border-dark border-bottom border-3">
    <div class="card-body">
        <ul class="nav nav-tabs nav-default" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#table-purchases" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-cart font-18 me-1'></i></div>
                        <div class="tab-title">@lang('lang.purchase', ['param'=>'s'])</div>
                    </div>
                </a>
            </li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="table-purchases" role="tabpanel">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <th>@lang('lang.date')</th>
                                    <th>@lang('lang.equipment', ['param'=>''])</th>
                                    <th>@lang('lang.qty')</th>
                                    <th>@lang('lang.price')</th>
                                    <th>Total</th>
                                    <th>@lang('lang.action', ['param'=>'s'])</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchases as $item)
                                <tr>
                                    <td>{{ $purchases->firstItem() + $loop->index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->purchased_at)->format('d/m/Y') }}</td>
                                    <td>{{ $item->equipment?->name }}</td>
                                    <td>{{ $item->qty." ".$item->equipment?->unit }}</td>
                                    <td>{{ moneyFormat($item->price) }}</td>
                                    <td>{{ moneyFormat($item->price * $item->qty) }}</td>
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#purchase{{ $item->id }}" class="btn btn-sm btn-primary" title="Editer cet achat" style="display: inline-block">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <x-purchase-edit :equipments="$equipments" :purchase="$item" />

                                        <form action="{{ route('purchases.destroy', $item->id) }}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Supprimer cet achat" onclick="if(!confirm('Confirmez-Vous cette suppression')) return false"><i class="bx bx-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Aucun achat enregistré</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <x-pagination :paginator="$purchases" />
                </div>
            </div>
        </div>
    </div>
</div>

<x-purchase-add :equipments="$equipments" />

@push('js-view')
<script>
    (function () {
        var fmt = function (n) {
            return (Math.round(n * 100) / 100).toString().replace('.', ',');
        };

        var refresh = function (scope) {
            var select = scope.querySelector('.purchase-equipment');
            var qtyInput = scope.querySelector('.purchase-qty');
            var hint = scope.querySelector('.purchase-stock-hint');
            if (!select || !qtyInput || !hint) return;

            var opt = select.options[select.selectedIndex];
            var available = opt ? parseFloat(opt.getAttribute('data-available')) : NaN;
            var unit = opt ? (opt.getAttribute('data-unit') || '') : '';

            if (isNaN(available)) { hint.textContent = ''; return; }

            var added = parseFloat(qtyInput.value);
            if (hint.getAttribute('data-mode') === 'new' && !isNaN(added) && added > 0) {
                hint.innerHTML = 'Disponible : <strong>' + fmt(available) + ' ' + unit +
                    '</strong> &rarr; après approvisionnement : <strong class="text-success">' +
                    fmt(available + added) + ' ' + unit + '</strong>';
            } else {
                hint.innerHTML = 'Disponible actuel : <strong>' + fmt(available) + ' ' + unit + '</strong>';
            }
        };

        var onChange = function (e) {
            if (e.target.matches('.purchase-equipment, .purchase-qty')) {
                refresh(e.target.closest('.modal') || document);
            }
        };
        document.addEventListener('input', onChange);
        document.addEventListener('change', onChange); // Select2 déclenche "change"
        document.addEventListener('shown.bs.modal', function (e) { refresh(e.target); });
    })();
</script>
@endpush
</x-admin-layout>
