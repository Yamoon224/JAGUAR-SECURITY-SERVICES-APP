<script>
    (function () {
        var fmt = function (n) {
            return (Math.round(n * 100) / 100).toString().replace('.', ',');
        };

        var key = function (name) {
            return (name || '').trim().toLowerCase();
        };

        // Équipements déjà en fiche, pour prévenir du cumul avant l'envoi.
        var known = function (scope) {
            var payload = scope.querySelector('.purchase-known-equipments');
            if (!payload) return null;
            try {
                return JSON.parse(payload.textContent);
            } catch (e) {
                return null;
            }
        };

        var refresh = function (scope) {
            var qtyInput = scope.querySelector('.purchase-qty');
            var hint = scope.querySelector('.purchase-stock-hint');
            if (!qtyInput || !hint) return;

            var added = parseFloat(qtyInput.value);
            var select = scope.querySelector('.purchase-equipment');

            // Nouvel achat : la fiche est retrouvée par son nom. Si elle
            // existe déjà, la quantité vient s'y cumuler ; sinon la saisie
            // constitue le stock initial de la fiche qui sera créée.
            if (!select) {
                var nameInput = scope.querySelector('.purchase-name');
                var list = known(scope) || [];
                var name = nameInput ? key(nameInput.value) : '';
                var match = null;

                for (var i = 0; i < list.length; i++) {
                    if (key(list[i].name) === name) { match = list[i]; break; }
                }

                if (!name) { hint.innerHTML = ''; return; }

                if (!match) {
                    hint.innerHTML = 'Nouvelle fiche' + ((!isNaN(added) && added > 0)
                        ? ' — stock initial : <strong class="text-success">' + fmt(added) + '</strong>'
                        : '');
                    return;
                }

                var unit = match.unit || '';
                hint.innerHTML = 'Fiche existante — disponible : <strong>' + fmt(match.available) + ' ' + unit + '</strong>'
                    + ((!isNaN(added) && added > 0)
                        ? ' &rarr; après cumul : <strong class="text-success">' + fmt(match.available + added) + ' ' + unit + '</strong>'
                        : '');
                return;
            }

            // Correction d'un achat existant : on rappelle le stock en place.
            var opt = select.options[select.selectedIndex];
            var available = opt ? parseFloat(opt.getAttribute('data-available')) : NaN;
            var selectedUnit = opt ? (opt.getAttribute('data-unit') || '') : '';

            hint.innerHTML = isNaN(available)
                ? ''
                : 'Disponible actuel : <strong>' + fmt(available) + ' ' + selectedUnit + '</strong>';
        };

        var onChange = function (e) {
            if (e.target.matches('.purchase-equipment, .purchase-qty, .purchase-name')) {
                refresh(e.target.closest('.modal') || document);
            }
        };
        document.addEventListener('input', onChange);
        document.addEventListener('change', onChange); // Select2 déclenche "change"
        document.addEventListener('shown.bs.modal', function (e) { refresh(e.target); });
    })();
</script>
