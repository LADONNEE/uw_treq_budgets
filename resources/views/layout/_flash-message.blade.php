<?php
$__fm = new \App\Utilities\FlashMessage();
$__fm->retrieve();
switch($__fm->style) {
    case 'success':
        $__fmCssClass = ' alert-success';
        break;
    case 'danger':
    case 'error':
        $__fmCssClass = ' alert-danger';
        break;
    case 'warning':
        $__fmCssClass = ' alert-warning';
        break;
    default:
        $__fmCssClass = ' alert-info';
        break;
}
?>
@if($__fm->message)
    <div class="flash-message__container js-dismissable">
        <div class="flash-message flash">
            <div class="alert{{ $__fmCssClass }}">
                <button class="close js-dismissable--close">&times;</button>
                <div>
                    {{ $__fm->message }}
                    @if($__fm->order_id)
                        <a href="{{ route('order', $__fm->order_id) }}">{{ $__fm->linkText ?? 'View Order' }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
