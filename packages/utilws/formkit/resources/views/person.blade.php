<input type="hidden" name="{{ $name }}" id="{{ $id }}">
<input type="text" name="{{ $name }}_typeahead" class="form-control person-typeahead {{ $class }}" data-for="{{ $id }}" value="{{ $value }}" {!! $htmlAttributes !!} />
