@extends('layout/htmlpage')
@section('title', 'Effort')
@section('content')

    <h3 class="mb-1"><a class="effort__header-link" href="{{ route('effort-home') }}">Faculty Effort</a></h3>
    <h1 class="mt-3 mb-3">Summer Hiatus</h1>
    <p>All faculty with unpaid summer hiatus time. Faculty without a check under "Approved Effort Report" had no
        effort report created for the selected period.</p>

    <div class="download_link">
        <a href="{{ downloadHref($period, $year) }}">Download CSV spreadsheet</a>
    </div>

    <label for="periods">Select a report period:</label>
    <select name="periods" id="periods-select">
        @foreach ($dates as $date)
            <option data-redirect="summerHiatus?year={{ urlencode($date['year']) }}&period={{ urlencode($date['period']) }}">
                {{ $date['year'] }} {{ $date['period'] }}
            </option>
        @endforeach
    </select>

    <p>{{$report->count}} results</p>
    <div class="mb-3">
        @if($report->count === 0)
            <div class="empty-table empty">
                There are no allocations for the selected period.
            </div>
        @else
            <div class="panel pt-0 pb-0">
                <div class="panel-full-width">
                    <table class="table-tight sortable">
                        <thead>
                            <tr>
                                <th>Faculty</th>
                                <th>Dates</th>
                                <th style="text-align: center;">Unpaid Full Summer</th>
                                <th style="text-align: center;">Approved Effort Report</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($report->orders as $row)
                            <tr>
                                <td>{{ $row->effortReport ? $row->effortReport->faculty->lastname . ', ' . $row->effortReport->faculty->firstname : $row->lastname . ', ' . $row->firstname }}</td>
                                @if($row->effortReport)
                                    <td>
                                        <a href="{{ route('effort-report-show', [$row->effortReport->faculty_contact_id, $row->effortReport]) }}"
                                           class="js-link-row">
                                        {{ eDate($row->start_at) . ' - ' . eDate($row->end_at) }}
                                        </a>
                                    </td>
                                    <td style="text-align: center;">
                                        @if($row->start_at == ($reportDateRange['start_at'])->toDateString() && $row->end_at == ($reportDateRange['end_at'])->toDateString())
                                            @icon('check')
                                        @endif
                                    </td>
                                    <td style="text-align: center;">@icon('check')</td>
                                @else
                                    <td>{{ eDate($reportDateRange['start_at']) . ' - ' . eDate($reportDateRange['end_at']) }}</td>
                                    <td style="text-align: center;">@icon('check')</td>
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            const year = {!! json_encode($year) !!};
            const period = {!! json_encode($period) !!};

            $('#periods-select').val(year + ' ' + period);

            $('#periods-select').change(function () {
                window.location.href = $(this).find(':selected').data('redirect');
            });
        });
    </script>
@stop
