<select name="{{ $name }}" id="{{ $id }}" class="form-control {{ $class }}" {!! $htmlAttributes !!} >
    @foreach($options as $_ov => $_ol)
        <option value="{{ $_ov }}" {!! selected($value, $_ov) !!}>{{ $_ol }}</option>
    @endforeach
</select>
