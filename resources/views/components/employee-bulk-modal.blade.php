@props(['id', 'action', 'title', 'submitLabel', 'submitIcon' => 'bx-user-check', 'submitClass' => 'btn-success'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><i class="bx bx-user-plus"></i> {{ $title }}</h5>
                <a class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <form method="POST" action="{{ $action }}" class="employee-bulk-form">
                @csrf
                <div class="modal-body">
                    <div class="position-relative input-icon mb-2">
                        <input type="text" class="form-control employee-bulk-search" placeholder="Rechercher par matricule, email, téléphone, nom ou prénom..." autocomplete="off">
                        <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-search'></i></span>
                    </div>

                    <div class="border rounded mb-2" style="max-height: 220px; overflow-y: auto">
                        <table class="table table-sm table-hover mb-0 align-middle">
                            <thead class="table-light" style="position: sticky; top: 0">
                                <tr>
                                    <th style="width: 30px"><input type="checkbox" class="form-check-input employee-bulk-all" title="Tout cocher"></th>
                                    <th>@lang('lang.matricule')</th>
                                    <th>@lang('lang.name') / @lang('lang.firstname')</th>
                                    <th>Téléphone</th>
                                    <th>@lang('lang.email')</th>
                                </tr>
                            </thead>
                            <tbody class="employee-bulk-results">
                                <tr><td colspan="5" class="text-center text-muted">Saisissez votre recherche</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-3">
                        <strong>Sélectionné(s) : <span class="employee-bulk-count">0</span></strong>
                        <div class="employee-bulk-selected d-flex flex-wrap gap-1 mt-1"></div>
                    </div>
                    <div class="employee-bulk-inputs"></div>

                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm {{ $submitClass }} employee-bulk-submit" disabled><i class="bx {{ $submitIcon }}"></i> {{ $submitLabel }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@once
@push('js-view')
<script>
$(function () {
    var searchUrl = "{{ route('employees.lookup') }}";

    $('.employee-bulk-form').each(function () {
        var $form = $(this);
        var $input = $form.find('.employee-bulk-search');
        var $results = $form.find('.employee-bulk-results');
        var $all = $form.find('.employee-bulk-all');
        var selected = {};
        var found = [];
        var timer = null;

        function esc(value) { return $('<div>').text(value || '').html(); }

        function refreshSelection() {
            var ids = Object.keys(selected);
            var $chips = $form.find('.employee-bulk-selected').empty();
            var $hidden = $form.find('.employee-bulk-inputs').empty();
            ids.forEach(function (id) {
                $chips.append('<span class="badge bg-dark">' + esc(selected[id]) +
                    ' <a href="#" class="text-white ms-1 employee-bulk-remove" data-id="' + id + '">&times;</a></span>');
                $hidden.append('<input type="hidden" name="employee_ids[]" value="' + id + '">');
            });
            $form.find('.employee-bulk-count').text(ids.length);
            $form.find('.employee-bulk-submit').prop('disabled', ids.length === 0);
            $all.prop('checked', found.length > 0 && found.every(function (e) { return selected[e.id]; }));
        }

        function render() {
            if (!found.length) {
                $results.html('<tr><td colspan="5" class="text-center text-muted">Aucun employé trouvé</td></tr>');
                return;
            }
            $results.empty();
            found.forEach(function (e) {
                $results.append('<tr><td><input type="checkbox" class="form-check-input employee-bulk-check" value="' + e.id + '"' +
                    (selected[e.id] ? ' checked' : '') + '></td><td>' + esc(e.matricule) + '</td><td>' +
                    esc(e.name + ' ' + e.firstname) + '</td><td>' + esc(e.phone) + '</td><td>' + esc(e.email) + '</td></tr>');
            });
        }

        function search() {
            var term = $.trim($input.val());
            if (term.length < 2) {
                found = [];
                $results.html('<tr><td colspan="5" class="text-center text-muted">Saisissez au moins 2 caractères</td></tr>');
                refreshSelection();
                return;
            }
            $.get(searchUrl, {search: term}, function (data) {
                found = data;
                render();
                refreshSelection();
            });
        }

        $input.on('input', function () {
            clearTimeout(timer);
            timer = setTimeout(search, 300);
        });
        $input.on('keydown', function (ev) { if (ev.key === 'Enter') ev.preventDefault(); });

        $form.on('change', '.employee-bulk-check', function () {
            var id = $(this).val();
            var e = found.find(function (x) { return String(x.id) === id; });
            if (this.checked) { selected[id] = e.name + ' ' + e.firstname; } else { delete selected[id]; }
            refreshSelection();
        });

        $all.on('change', function () {
            var checked = this.checked;
            found.forEach(function (e) {
                if (checked) { selected[e.id] = e.name + ' ' + e.firstname; } else { delete selected[e.id]; }
            });
            render();
            refreshSelection();
        });

        $form.on('click', '.employee-bulk-remove', function (ev) {
            ev.preventDefault();
            delete selected[$(this).data('id')];
            render();
            refreshSelection();
        });

        $form.closest('.modal').on('hidden.bs.modal', function () {
            selected = {};
            found = [];
            $form[0].reset();
            $results.html('<tr><td colspan="5" class="text-center text-muted">Saisissez votre recherche</td></tr>');
            refreshSelection();
        });
    });
});
</script>
@endpush
@endonce
