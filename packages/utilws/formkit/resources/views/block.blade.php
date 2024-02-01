<div class="{{ ($error) ? 'form-group has-error' : 'form-group' }}">
    @if($label)
        <label for="{{ $id }}" class="form-group__label">{{ $label }}@if($required)
            <span title="Required" class="required">*</span>
        @endif</label>
    @endif
    @include($view)
    @if($error)
        <div class="form-group__error">{{ $error }}</div>
    @else
        <div class="form-group__error" style="display:none;"></div>
    @endif
    @if($help)
        <div id="{{ $helpId }}" class="form-group__help">{{ $help }}</div>
    @endif
</div>
