@extends('layout.htmlpage')
@section('title', 'Faculty Effort Allocations')
@section('content')

    <div>
        @include('.budget.effort._header')

        <section class="bg-light pt-3 pr-5 pl-5 pb-3 mb-4">
            <h2 class="mb-1">Long-Term Allocations</h2>
            <p class="mt-0 mb-3">
                Finance managers record Long-Term Allocations of faculty pay to non-default budgets. Allocations will
                drop off this list one month after the end of the time period range.
            </p>
            <allocation-list
                submit-url="{{ route('allocations-by-faculty-store', $faculty) }}"
                can-edit="{{ hasRole('budget:fiscal') }}"
                faculty-id="{{ $faculty->id }}"
                token="{{ csrf_token() }}"
            ></allocation-list>
        </section>

        <div id="effortReportModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create Effort Report</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="mt-2" method="get" action="{!! route('effort-report-create', $faculty) !!}#results">
                        <div class="modal-body">
                            <label for="effort-report-period">Create Effort Report for</label>
                            <select id="effort-report-period" name="report_period">
                                @if ($now < ($now->year . App\Models\EffortReport::DATE_SUMMER_START))
                                    <option value="{{ $now->year - 1 }}-A">Academic Year {{ $now->year - 1 }}/{{ $now->year }}</option>
                                @endif

                                @if ($now < ($now->year . App\Models\EffortReport::DATE_ACADEMIC_YEAR_START))
                                    <option value="{{ $now->year }}-S">Summer {{ $now->year }}</option>
                                @endif
                                <option value="{{ $now->year }}-A">Academic Year {{ $now->year }}/{{ $now->year + 1 }}</option>
                                <option value="{{ $now->year + 1 }}-S">Summer {{ $now->year + 1 }}</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary btn-sm mb-1 inline-flex-container__btn" type="submit">
                                Create Report Snapshot
                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <section class="bg-light pt-3 pr-5 pl-5 pb-3 mb-4">
            <h2 class="mb-1">Effort Reports</h2>
            <p class="mt-0 mb-3">
                Effort Reports are snapshots of Long-Term Allocations for a specific time period. Once an Effort
                Report snapshot is created it can be shared for review and approval. Effort Reports are not
                directly editable; to correct an Effort Report change the Long-Term Allocations then start a new
                Effort Report snapshot.
            </p>
            @if(hasRole('budget:fiscal'))
                <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#effortReportModal" type="button" id="new-snapshot-button">
                    + New Report Snapshot
                </button>
            @endif

            @if(count($effortReports) !== 0)
                <div class="d-flex flex-row-reverse">
                    <label for="effort-reports__all-reports-checkbox">Show all reports</label>
                    <input class="mr-1" id="effort-reports__all-reports-checkbox" type="checkbox">
                </div>
            @endif

            @include('effort.effort-report._effort_reports_active_all_tables')
        </section>
    </div>
@stop
