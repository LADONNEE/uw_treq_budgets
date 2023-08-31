@extends('layout/htmlpage')
@section('title', 'Effort')
@section('content')
    <div class="container">
        <div class="inline-flex-container pl-5 pr-5">
            <h1 class="mt-3 mb-3"><a class="effort__header-link" href="{{ route('effort-home') }}">Faculty Effort</a></h1>
            @include('effort._jump-nav')
        </div>

        <section id="nav-tasks" class="bg-light pt-4 pr-5 pl-5 pb-3 mt-4 mb-4 report-section">
            <h2>{{hasRole('workday') ? 'Needs Action' : 'Needs Approval'}}</h2>
            <p class="m-0 report-section__description">Effort Reports awaiting your approval{{ hasRole('workday')
                ? ' and reports due for revisit. The revisit flag can be cleared from the Effort Report page' : '' }}.</p>
            <div class="panel pt-0 pb-0">
                <div class="panel-full-width">
                    @if($reports->tasks->orders->count() === 0)
                        <div class="empty bg-light pt-2">No Effort Reports are currently awaiting your approval.</div>
                    @else
                        <table class="table table-hover sortable effort-table">
                            <thead>
                                <tr>
                                    <th style="width: 28%;">Faculty Name</th>
                                    <th style="width: 18%;">Period</th>
                                    <th style="width: 27%;">Dates</th>
                                    <th style="width: 27%;">Stage</th>
                                    @if(hasRole('workday'))
                                        <th>Revisit</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports->tasks->orders as $effortReport)
                                    <tr>
                                        <td>{{ $effortReport->faculty->firstname }} {{ $effortReport->faculty->lastname }}</td>
                                        <td>{{ Str::title($effortReport->type) }}</td>
                                        <td data-text="{{ eDate($effortReport->start_at) }}">
                                            <a href="{{ route('effort-report-show', [$effortReport->faculty_contact_id, $effortReport]) }}"
                                               class="js-link-row">
                                                {{ eDate($effortReport->start_at) . ' - ' . eDate($effortReport->end_at) }}
                                            </a>
                                        </td>
                                        <td>
                                            <div>{{ Str::title($effortReport->stage) }}</div>
                                            <div class="text-sm text-muted">{{ eDate($effortReport->updated_at) }}</div>
                                        </td>
                                        @if(hasRole('workday'))
                                            <td style="text-align: center;">
                                                @if($effortReport->needsRevisit())
                                                    @icon('repeat')
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </section>

        @if(hasRole('workday'))
            <section id="nav-revisits" class="bg-light pt-4 pr-5 pl-5 pb-3 mt-4 mb-4 report-section">
                <h2>Revisits</h2>
                <p class="m-0 report-section__description">All Effort Reports flagged for revisit.</p>
                <div class="panel pt-0 pb-0">
                    <div class="panel-full-width">
                        @if($reports->revisits->orders->count() === 0)
                            <div class="empty bg-light pt-2">No matching Effort Reports.</div>
                        @else
                            <table class="table table-hover sortable effort-table">
                                <thead>
                                <tr>
                                    <th style="width: 28%;">Faculty Name</th>
                                    <th style="width: 18%;">Period</th>
                                    <th style="width: 27%;">Dates</th>
                                    <th style="width: 27%;">Stage</th>
                                    <th>Revisit Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reports->revisits->orders as $effortReport)
                                    <tr>
                                        <td>{{ $effortReport->faculty->firstname }} {{ $effortReport->faculty->lastname }}</td>
                                        <td>{{ Str::title($effortReport->type) }}</td>
                                        <td data-text="{{ eDate($effortReport->start_at) }}">
                                            <a href="{{ route('effort-report-show', [$effortReport->faculty_contact_id, $effortReport]) }}"
                                               class="js-link-row">
                                                {{ eDate($effortReport->start_at) . ' - ' . eDate($effortReport->end_at) }}
                                            </a>
                                        </td>
                                        <td>
                                            <div>{{ Str::title($effortReport->stage) }}</div>
                                            <div class="text-sm text-muted">{{ eDate($effortReport->updated_at) }}</div>
                                        </td>
                                        <td>{{ eDate($effortReport->revisit_at) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        @if(hasRole('budget:fiscal'))
            <section id="nav-my-reports" class="bg-light pt-4 pr-5 pl-5 pb-3 mb-4 report-section">
                <h2>My Reports</h2>
                <p class="m-0 report-section__description">Effort Reports you created that are pending approval, plus completed reports resolved in the
                    last 90 days.</p>
                <div class="panel pt-0 pb-0">
                    <div class="panel-full-width">
                        @if($reports->myEffortReports->orders->count() === 0)
                            <div class="empty bg-light pt-2">You have not created any Effort Reports.</div>
                        @else
                            <table class="table table-hover sortable effort-table">
                                <thead>
                                    <tr>
                                        <th style="width: 28%;">Faculty Name</th>
                                        <th style="width: 18%;">Period</th>
                                        <th style="width: 27%;">Dates</th>
                                        <th style="width: 27%;">Stage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports->myEffortReports->orders as $effortReport)
                                        <tr class="link-row" style="{{ $effortReport->stage === 'SENT BACK' || $effortReport->stage === 'SUPERSEDED' ? "color: #777;" : '' }}">
                                            <td>{{ $effortReport->faculty->firstname }} {{ $effortReport->faculty->lastname }}</td>
                                            <td>{{ Str::title($effortReport->type) }}</td>
                                            <td data-text="{{ eDate($effortReport->start_at) }}">
                                                <a href="{{ route('effort-report-show', [$effortReport->faculty_contact_id, $effortReport]) }}"
                                                   class="js-link-row">
                                                    {{ eDate($effortReport->start_at) . ' - ' . eDate($effortReport->end_at) }}
                                                </a>
                                            </td>
                                            <td>
                                                <div>{{ Str::title($effortReport->stage) }}</div>
                                                <div class="text-sm text-muted">{{ eDate($effortReport->updated_at) }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </section>

            <section id="nav-my-faculty" class="bg-light pt-4 pr-5 pl-5 pb-3 mb-4 report-section">
                <h2>My Faculty</h2>
                <p class="m-0 report-section__description">Faculty you manage.</p>
                <div class="panel pt-0 pb-0">
                    <div class="panel-full-width">
                        @if($reports->myFaculty->orders->count() === 0)
                            <div class="empty bg-light pt-2">You have not been assigned as finance manager for any faculty.</div>
                        @else
                            <table class="table table-hover sortable effort-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Name</th>
                                        <th style="width: 18%;">Default Budget</th>
                                        <th style="width: 20%;">Finance Manager</th>
                                        <th style="width: 27%;">Last Effort Report</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports->myFaculty->orders as $facultyMember)
                                        @include('effort._faculty-table-row')
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </section>

            <section id="nav-faculty" class="bg-light pt-4 pr-5 pl-5 pb-3 mb-4 report-section">
                <h2>Faculty</h2>
                <p class="m-0 report-section__description">Active faculty with Effort Reports created in the last 90 days.</p>
                <div class="d-flex flex-row-reverse">
                    <label for="show-defaults">Show all faculty</label>
                    <input class="mt-1 mr-1" type="checkbox" name="show-defaults">
                </div>

                <div class="panel pt-0 pb-0 js-current-faculty">
                    <div class="panel-full-width">

                        @if($facultyWithEffort->count() === 0)
                            <div class="empty bg-light pt-2">There are no active faculty.</div>
                        @else
                            <table class="table table-hover sortable effort-table js-edit-row">
                                <thead>
                                <tr>
                                    <th style="width: 30%;">Name</th>
                                    <th style="width: 18%;">Default Budget</th>
                                    <th style="width: 20%;">Finance Manager</th>
                                    <th style="width: 27%;">Last Effort Report</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($facultyWithEffort as $facultyMember)
                                    @include('effort._faculty-table-row')
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                <div class="panel pt-0 pb-0 js-all-faculty" style="display: none;">
                    <div class="panel-full-width">
                        <table class="table table-hover sortable effort-table js-edit-row">
                            <thead>
                            <tr>
                                <th style="width: 30%;">Name</th>
                                <th style="width: 18%;">Default Budget</th>
                                <th style="width: 20%;">Finance Manager</th>
                                <th style="width: 27%;">Last Effort Report</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($allActiveFaculty as $facultyMember)
                                @include('effort._faculty-table-row')
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        @endif
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            $('input:checkbox').prop('checked', false);

            $('input').change(function() {
                $('.js-current-faculty').toggle();
                $('.js-all-faculty').toggle();
            })
        });
    </script>
@stop
