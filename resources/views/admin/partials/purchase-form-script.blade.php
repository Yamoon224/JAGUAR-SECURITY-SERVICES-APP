<script>
    (function () {
        var fmt = function (n) {
            return (Math.round(n * 100) / 100).toString().replace('.', ',');
        };

        var refresh = function (scope) {
            var qtyInput = scope.querySelector('.purchase-qty');
            var hint = scope.querySelector('.purchase-stock-hint');
            if (!qtyInput || !hint) return;

            // Nouvel achat : l'équipement est toujours créé, la quantité
            // saisie constitue donc son stock initial. Pas de select ici.
            var select = scope.querySelector('.purchase-equipment');
            if (!select) {
                var initial = parseFloat(qtyInput.value);
                hint.innerHTML = (!isNaN(initial) && initial > 0)
                    ? 'Stock initial : <strong class="text-success">' + fmt(initial) + '</strong>'
                    : '';
                return;
            }

            // Correction d'un achat existant : on rappelle le stock en place.
            var opt = select.options[select.selectedIndex];
            var available = opt ? parseFloat(opt.getAttribute('data-available')) : NaN;
            var unit = opt ? (opt.getAttribute('data-unit') || '') : '';

            hint.innerHTML = isNaN(available)
                ? ''
                : 'Disponible actuel : <strong>' + fmt(available) + ' ' + unit + '</strong>';
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
