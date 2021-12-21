@extends('layout/htmlpage')
@section('title', 'Show Effort Report')
@section('content')

    <div>
        @include('budget.effort._header')

        <?php
            $reportIsCanceled = $effortReport->stage === App\Models\EffortReport::STAGE_CANCELED;
            $reportWasSentBack = $effortReport->stage === App\Models\EffortReport::STAGE_SENT_BACK;
            $supersededBy = $effortReport->getSupersededByReport();
        ?>
        @if($reportIsCanceled)
            @foreach($effortReport->notesInSection('canceled') as $note)
                <div class="alert alert-danger pl-5" role="alert">
                    This report has been canceled by <strong>{{ eFirstLast($note->created_by) }}</strong>
                    <div>Reason: <strong>{{ $note->note }}</strong></div>
                </div>
            @endforeach
        @elseif($reportWasSentBack)
            <div class="alert alert-danger pl-5" role="alert">
                This report has been sent back by <strong>{{ $effortReport->sentBackApproval()->completer->firstname }}
                    {{ $effortReport->sentBackApproval()->completer->lastname }}</strong>
                <div>Reason: <strong>{{ $effortReport->sentBackApproval()->message }}</strong></div>
            </div>
        @endif

        <section class="bg-light pt-3 pr-5 pl-5 pb-3 mb-4">
            <h2>Report Period Allocations</h2>

            <div class="d-flex flex-row-reverse">
                <div class="pl-3">
                    <input type="radio" id="workday" name="report-view" value="workday" @if(hasRole('workday'))checked="checked"@endif>
                    <label for="workday">Workday</label>
                </div>

                <div class="pl-3">
                    <input type="radio" id="subperiod" name="report-view" value="subperiod">
                    <label for="subperiod">Subperiods</label>
                </div>

                <div class="pl-3">
                    <input type="radio" id="allocations" name="report-view" value="allocations" @if(!hasRole('workday'))checked="checked"@endif>
                    <label for="allocations">Allocations</label>
                </div>
                <div class="">Views: </div>
            </div>

            <div class="workday" @if(!hasRole('workday'))style="display: none;"@endif>
                @include('budget.effort.allocations._allocations-table-workday', [
                    'showSplit' => true
                ])
                @include('budget.effort.allocations._additional-pay-table')
            </div>

            <div class="subperiod" style="display: none;">
                @include('budget.effort.allocations._allocations-table-workday')
                @include('budget.effort.allocations._additional-pay-table')
            </div>

            <div class="allocations" @if(hasRole('workday'))style="display: none;"@endif>
                @include('budget.effort.allocations._allocations-table')
            </div>
        </section>

        @if(!$reportIsCanceled)
            @if($effortReport->creator_contact_id === \App\Models\Contact::personToContact(user()->person_id))
                <div class="ml-5 mb-5"><a href="{{ route('cancel-report', $effortReport->id) }}" class="text-danger">× Cancel Effort Report</a></div>
            @endif

            <div class="pl-5 pr-5">
                <h2>Approvals</h2>
                <div class="row justify-content-between mt-3">
                    <task-list url="{{ route('approvals', $effortReport->id) }}"></task-list>
                    <notes-section id="{{ $effortReport->id }}" section="snapshot"></notes-section>
                </div>
            </div>
        @endif

        @if($effortReport->stage !== 'SUPERSEDED' && $effortReport->stage !== 'CANCELED' && $effortReport->stage !== 'SENT BACK' && count($effortReport->getRelatedSupersededReports()) > 0)
            <hr class="my-4">
            <div class="pl-5 pr-5">
                <h2>This report supersedes:</h2>
                <ul>
                    @foreach($effortReport->getRelatedSupersededReports() as $report)
                        <li>
                            <a href="{{ route('effort-report-show', [$faculty, $report]) }}" target="_blank">
                                {{ eDate($report->created_at) }} report created by {{ $report->creator->firstname }} {{ $report->creator->lastname }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @elseif(count($supersededBy) > 0)
            <hr class="my-4">
            <div class="pl-5 pr-5">
                <h2>This report is superseded by:</h2>
                <ul>
                    <li>
                        <a href="{{ route('effort-report-show', [$faculty, $supersededBy[0]]) }}" target="_blank">
                            {{ eDate($supersededBy[0]->created_at) }} report created by {{ $supersededBy[0]->creator->firstname }} {{ $supersededBy[0]->creator->lastname }}
                        </a>
                    </li>
                </ul>
            </div>
        @endif
    </div>
@stop
@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            $('input[name=report-view]').change(function() {
                $('.allocations').toggle($(this).val() === 'allocations');
                $('.subperiod').toggle($(this).val() === 'subperiod');
                $('.workday').toggle($(this).val() === 'workday');
            });
        });
    </script>
@stop
