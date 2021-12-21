<div>
    @if(count($effortReports) === 0)
        <div class="empty-table  pt-4 pb-4">
            <span class="empty">No Effort Reports have been created for this faculty member.</span>
        </div>
    @else
        <div class="panel pt-0 pb-0 js-current-reports">
            <div class="panel-full-width">
                <table class="table table-hover sortable effort-table">
                    <thead>
                    <tr>
                        <th>Faculty Name</th>
                        <th>Period</th>
                        <th>Dates</th>
                        <th>Stage</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activeEffortReports as $effortReport)
                        <tr>
                            <td>{{ $effortReport->faculty->firstname }} {{ $effortReport->faculty->lastname }}</td>
                            <td>{{ Str::title($effortReport->type) }}</td>
                            <td data-text="{{ eDate($effortReport->start_at) }}">
                                <a href="{{ route('effort-report-show', [$faculty, $effortReport]) }}" class="js-link-row">
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
            </div>
        </div>

        <div class="panel pt-0 pb-0 js-all-reports" style="display: none;">
            <div class="panel-full-width">
                <table class="table table-hover sortable effort-table">
                    <thead>
                    <tr>
                        <th>Faculty Name</th>
                        <th>Period</th>
                        <th>Dates</th>
                        <th>Stage</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($effortReports as $effortReport)
                        <tr>
                            <td>{{ $effortReport->faculty->firstname }} {{ $effortReport->faculty->lastname }}</td>
                            <td>{{ Str::title($effortReport->type) }}</td>
                            <td>
                                <a href="{{ route('effort-report-show', [$faculty, $effortReport]) }}" class="js-link-row">
                                    {{ eDate($effortReport->start_at) . ' - ' . eDate($effortReport->end_at) }}
                                </a>
                            </td>
                            <td>
                                <div>
                                    @if ($effortReport->stage === 'SENT BACK' || $effortReport->stage === 'SUPERSEDED')
                                        <span style="color: #777;">{{ Str::title($effortReport->stage) }}</span>
                                    @else
                                        {{ Str::title($effortReport->stage) }}
                                    @endif
                                </div>
                                <div class="text-sm text-muted">{{ eDate($effortReport->updated_at) }}</div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            if ($('#effort-reports__all-reports-checkbox').is(':checked')) {
                $('.js-all-reports').show();
                $('.js-current-reports').hide();
            }

            $('#effort-reports__all-reports-checkbox').change(function() {
                $('.js-current-reports').toggle();
                $('.js-all-reports').toggle();
            })
        });
    </script>
@stop
