@if ($facultyMember->fiscal_person_id)
    @if ($referrer !== 'effort')
        <span style="color: #777;">Faculty Finance Manager:</span>
    @endif
        {{ $facultyMember->getFiscalPersonName() }}
@else
    @if ($referrer !== 'effort')
        <span style="color: #777;">Faculty Finance Manager:</span>
    @endif
    <span class="empty">missing</span>
@endif

