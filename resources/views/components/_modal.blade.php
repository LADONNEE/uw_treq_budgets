@if(!empty($header))
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ $header }}</h4>
    </div>
@endif
    <div class="modal-body">
        {{ $slot }}
    </div>
@if(!empty($footer))
    <div class="modal-footer">{{ $header }}</div>
@endif