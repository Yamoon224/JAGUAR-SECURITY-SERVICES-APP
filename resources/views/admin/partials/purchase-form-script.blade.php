<script>
    // NB : les selects "équipement" sont transformés en Select2, qui notifie le
    // changement via jQuery.trigger('change'). Un addEventListener natif sur
    // document ne reçoit pas ces évènements : tout passe donc par jQuery.
    jQuery(function ($) {
        var NEW = '__new__';

        var fmt = function (n) {
            return (Math.round(n * 100) / 100).toString().replace('.', ',');
        };

        var refresh = function (scope) {
            var $scope = $(scope);
            var $select = $scope.find('.purchase-equipment');
            var $qty = $scope.find('.purchase-qty');
            var $hint = $scope.find('.purchase-stock-hint');
            if (!$select.length || !$qty.length) return;

            var isNew = $select.val() === NEW;

            // Champs "nouvel équipement" : affichés et exigés uniquement en mode
            // création, pour que le navigateur ne bloque pas un champ masqué.
            var $block = $scope.find('.purchase-new-equipment');
            $block.toggleClass('d-none', !isNew);
            $scope.find('.purchase-new-name').prop('required', isNew).prop('disabled', !isNew);
            $scope.find('.purchase-new-unit').prop('disabled', !isNew);

            if (!$hint.length) return;

            var added = parseFloat($qty.val());

            if (isNew) {
                $hint.html(added > 0 ? 'Stock initial : <strong class="text-success">' + fmt(added) + '</strong>' : '');
                return;
            }

            var $opt = $select.find('option:selected');
            var available = parseFloat($opt.attr('data-available'));
            var unit = $opt.attr('data-unit') || '';

            if (isNaN(available)) { $hint.text(''); return; }

            if ($hint.attr('data-mode') === 'new' && added > 0) {
                $hint.html('Disponible : <strong>' + fmt(available) + ' ' + unit +
                    '</strong> &rarr; après approvisionnement : <strong class="text-success">' +
                    fmt(available + added) + ' ' + unit + '</strong>');
            } else {
                $hint.html('Disponible actuel : <strong>' + fmt(available) + ' ' + unit + '</strong>');
            }
        };

        var scopeOf = function (el) {
            var $modal = $(el).closest('.modal');
            return $modal.length ? $modal : $(document);
        };

        $(document)
            .on('change select2:select', '.purchase-equipment', function () { refresh(scopeOf(this)); })
            .on('input change', '.purchase-qty', function () { refresh(scopeOf(this)); })
            .on('shown.bs.modal', '.modal', function () { refresh(this); });

        // Ouverture initiale : le modal d'ajout est réaffiché après une erreur
        // de validation, l'état doit correspondre à la saisie restaurée.
        $('.purchase-equipment').each(function () { refresh(scopeOf(this)); });
    });
</script>
