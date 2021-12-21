@component('inputs.form-check', ['optionId' => $id, 'optionLabel' => $booleanText ?? $label])
    <input type="hidden" name="{{ $name }}" id="{{ $id }}_empty" value="0" />
    <input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="1" {!! checked($value, 1) !!} {!! $htmlAttributes !!} />
@endcomponent
