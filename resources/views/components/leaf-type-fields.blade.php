@props(['leaf' => null])

@php
    $uid = 'leaf-type-'.($leaf->id ?? 'new');
    $currentType = old('type', $leaf->type ?? 'annuel');
    $currentDestination = old('destination', $leaf->destination ?? '');
    $requiresDestination = in_array($currentType, \App\Models\Leaf::TYPES_REQUIRING_DESTINATION, true);
@endphp

<div class="leaf-type-fields" data-destination-types='@json(\App\Models\Leaf::TYPES_REQUIRING_DESTINATION)'>
    <label class="form-label mb-1">@lang('lang.leaf_type') <span class="text-danger">*</span></label>
    <select class="form-select mb-3 leaf-type-select" name="type" required>
        @foreach (\App\Models\Leaf::TYPES as $value => $label)
            <option value="{{ $value }}" {{ $currentType == $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>

    <div class="leaf-destination-wrapper mb-3" @unless($requiresDestination) hidden @endunless>
        <label class="form-label mb-1">@lang('lang.destination') <span class="text-danger">*</span></label>
        <div class="position-relative input-icon">
            <input type="text" class="form-control leaf-destination-input" name="destination"
                   placeholder="@lang('lang.destination_hint')"
                   value="{{ $currentDestination }}" {{ $requiresDestination ? 'required' : '' }}>
            <span class="position-absolute top-50 translate-middle-y"><i class='bx bx-map-pin'></i></span>
        </div>
        <small class="text-muted">@lang('lang.destination_help')</small>
    </div>
</div>

@once
@push('js-view')
<script>
    document.addEventListener('change', function (event) {
        var select = event.target.closest('.leaf-type-select');
        if (!select) return;

        var group = select.closest('.leaf-type-fields');
        var requiredTypes = JSON.parse(group.getAttribute('data-destination-types') || '[]');
        var wrapper = group.querySelector('.leaf-destination-wrapper');
        var input = group.querySelector('.leaf-destination-input');
        var needed = requiredTypes.indexOf(select.value) !== -1;

        wrapper.hidden = !needed;
        input.required = needed;
        if (!needed) input.value = '';
    });
</script>
@endpush
@endonce
