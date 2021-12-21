<div class="effort__header pl-5">
    <h3 class="mb-1"><a class="effort__header-link" href="{{ route('effort-home') }}">Faculty Effort</a></h3>
    <h1><a class="effort__header-link" href="{{ route('allocations-by-faculty', $faculty) }}">
            {{ $faculty->firstname }} {{ $faculty->lastname }}
    </a></h1>
    @if (isset($effortReport) && $effortReport->id)
        <div class="d-flex">
            <p class="m-0">
                <span style="color: #777;">Faculty Finance Manager:</span>
                @if ($faculty->fiscal_person_id)
                    {{ $faculty->getFiscalPersonName() }}
                @else
                    missing
                @endif
            </p>

            <p class="m-0 ml-3">
                <span style="color: #777;">Default Budget:</span>
                @if ($faculty->default_budget_id)
                    {{ $faculty->defaultBudget->budgetno }}
                @else
                    missing
                @endif
            </p>

            <span class="ml-3" id="80_20-{{ $faculty->id }}">
                @if ($faculty->is_80_20)
                    <span style="color: #777;">Is 80/20: </span>Yes
                @endif
            </span>
        </div>

        <div class="d-flex">
            <p class="mt-1">
                <span style="color: #777;">Report Period:</span>
                {{ Str::title($effortReport->type) }} {{ eDate($effortReport->start_at) }} - {{ eDate($effortReport->end_at) }}
            </p>
            <p class="mt-1 ml-3">
                <span style="color: #777;">Status:</span>
                @if ($effortReport->stage === App\Models\EffortReport::STAGE_APPROVAL)
                    Pending {{ Str::title($effortReport->stage) }}
                @elseif ($effortReport->stage !== App\Models\EffortReport::STAGE_APPROVED
                    && $effortReport->stage !== App\Models\EffortReport::STAGE_SENT_BACK
                    && $effortReport->stage !== App\Models\EffortReport::STAGE_SUPERSEDED
                    && $effortReport->stage !== App\Models\EffortReport::STAGE_CANCELED)
                    Pending {{ Str::title($effortReport->stage) }} Approval
                @else
                    {{ Str::title($effortReport->stage) }}
                @endif
            </p>
        </div>
    @else
        <table class="js-edit-row effort__header-edit-contact m-0">
            <tr id="contact-{{ $faculty->id }}" data-href="{{ action('ContactController@edit', $faculty->id) }}">
                <td class="js-edit-row--trigger pointer pl-0">
                    <span id="finance-manager-{{ $faculty->id }}">
                        <span style="color: #777;">Faculty Finance Manager:</span>
                        @if ($faculty->fiscal_person_id)
                            {{ $faculty->getFiscalPersonName() }}
                        @else
                            missing
                        @endif
                    </span>
                </td>

                <td class="js-edit-row--trigger pointer">
                    <span id="default-budget-{{ $faculty->id }}">
                        <span style="color: #777;">Default Budget:</span>
                        @if ($faculty->default_budget_id)
                            {{ $faculty->defaultBudget->budgetno }}
                        @else
                            missing
                        @endif
                    </span>
                </td>

                <td class="js-edit-row--trigger pointer">
                    <span id="80_20-{{ $faculty->id }}">
                        @if ($faculty->is_80_20)
                            <span style="color: #777;">Is 80/20: </span>Yes
                        @endif
                    </span>
                </td>
            </tr>
        </table>
        @if (isset($reportPeriod))
            @php($reportRange = App\Models\EffortReport::getReportDateRange($reportPeriod->type, $reportPeriod->year))
            <p class="mt-1">
                <span style="color: #777;">Report Period:</span>
                {{ Str::title($reportPeriod->type) }} {{ eDate($reportRange['start_at']) }} - {{ eDate($reportRange['end_at']) }}
            </p>
        @endif
    @endif
</div>
