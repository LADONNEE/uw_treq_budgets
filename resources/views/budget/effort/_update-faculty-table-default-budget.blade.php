@if ($facultyMember->default_budget_id)
    @if ($referrer !== 'effort')
        <span style="color: #777;">Default Budget:</span>
    @endif
    {{ $facultyMember->defaultBudget->budgetno }}
@else
    @if ($referrer !== 'effort')
        <span style="color: #777;">Default Budget:</span>
    @endif
    <span class="empty">missing</span>
@endif
