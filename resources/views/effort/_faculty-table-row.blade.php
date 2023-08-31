<tr data-href="{{ action('ContactController@edit', $facultyMember->id) }}">
    <td>
        <a href="{{ route('allocations-by-faculty', $facultyMember) }}">
            {{ Str::title($facultyMember->lastname) . ', ' . Str::title($facultyMember->firstname) }}
            ({{ $facultyMember->uwnetid }})
        </a>
    </td>
    <td class="js-edit-row--trigger pointer">
        <span id="default-budget-{{ $facultyMember->id }}">
            @if ($facultyMember->default_budget_id)
                {{ $facultyMember->defaultBudget->budgetno }}
            @else
                <span class="empty">missing</span>
            @endif
        </span>
    </td>
    <td class="js-edit-row--trigger pointer">
        <span id="finance-manager-{{ $facultyMember->id }}">
            @if ($facultyMember->fiscal_person_id)
                {{ $facultyMember->getFiscalPersonName() }}
            @else
                <span class="empty">missing</span>
            @endif
        </span>
    </td>

    @if($facultyMember->latestEffortReport)
        <td data-text="{{ eDate($facultyMember->latestEffortReport->start_at) }}">
            <div><a href="{{ route('effort-report-show', [$facultyMember, $facultyMember->latestEffortReport->id]) }}">{{ eDate($facultyMember->latestEffortReport->start_at) }}
                    - {{ eDate($facultyMember->latestEffortReport->end_at) }}</a></div>
            <div class="text-sm text-muted">Stage: {{ Str::title($facultyMember->latestEffortReport->stage) }}</div>
        </td>
    @else
        <td><span class="empty">none</span></td>
    @endif
</tr>
