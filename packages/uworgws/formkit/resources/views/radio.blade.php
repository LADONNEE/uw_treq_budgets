<div id="{{ $id }}" class="{{ $class }}" {!! $htmlAttributes !!}>
    @foreach($options as $_ov => $_ol)
        <?php $_oid = optionId($id, $_ov); ?>
        @component('inputs.form-check', ['optionId' => $_oid, 'optionLabel' => $_ol])
            <input type="radio" name="{{ $name }}" id="{{ $_oid }}" value="{{ $_ov }}" {!! checked($value, $_ov) !!} />
        @endcomponent
    @endforeach
</div>
