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

            // Bloc "nouvel équipement"
            var newBlock = scope.querySelector('.purchase-new-equipment');
            var newName = scope.querySelector('.purchase-new-name');
            if (newBlock) {
                var isNew = select.value === '__new__';
                newBlock.classList.toggle('d-none', !isNew);
                if (newName) newName.required = isNew;
            }

            var opt = select.options[select.selectedIndex];
            var available = opt ? parseFloat(opt.getAttribute('data-available')) : NaN;
            var unit = opt ? (opt.getAttribute('data-unit') || '') : '';

            if (select.value === '__new__') {
                var q = parseFloat(qtyInput.value);
                hint.innerHTML = (!isNaN(q) && q > 0)
                    ? 'Stock initial : <strong class="text-success">' + fmt(q) + '</strong>'
                    : '';
                return;
            }

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
