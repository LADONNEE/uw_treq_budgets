@extends('layout/htmlpage')
@section('title', 'Effort')
@section('content')

    <h3 class="mb-1"><a class="effort__header-link" href="{{ route('effort-home') }}">Faculty Effort</a></h3>
    <h1 class="mt-3 mb-3">Approved Effort Report Allocations & Additional Pay</h1>

    <div class="download_link">
        <a href="{{ downloadHref($period, $year) }}">Download CSV spreadsheet</a>
    </div>

    <label for="periods">Select a report period:</label>
    <select name="periods" id="periods-select">
        @foreach ($dates as $date)
            <option data-redirect="approvedAllocations?year={{ urlencode($date['year']) }}&period={{ urlencode($date['period']) }}">
                {{ $date['year'] }} {{ $date['period'] }}
            </option>
        @endforeach
    </select>

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
                                <th>80/20</th>
                                <th style="width: 10%;">Budget</th>
                                <th style="width: 5%;">PCA Code</th>
                                <th style="width: 10%;">Type</th>
                                <th>Category</th>
                                <th style="width: 10%;">% Effort</th>
                                <th style="width: 10%;">Dollar Amount</th>
                                <th style="width: 10%;">Start Date</th>
                                <th style="width: 10%;">End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($report->orders as $allocation)
                            <tr>
                                <td>
                                    <a href="{{ route('effort-report-show', [$allocation->effortReport->faculty_contact_id, $allocation->effortReport]) }}"
                                       class="js-link-row">
                                        {{ eLastFirst($allocation->effortReport->faculty->person_id) }}
                                    </a>
                                </td>
                                <td>{{ $allocation->effortReport->faculty->is_80_20 === 1 ? 'yes' : '' }}</td>
                                <td>{{ $allocation->budget ? $allocation->budget->budgetno : 'n/a' }}</td>
                                <td>{{ $allocation->pca_code }}</td>
                                <td>{{ Str::title($allocation->type) }}</td>
                                <td>{{ $allocation->allocation_category ? Str::title($allocation->allocation_category) : $allocation->additional_pay_category }}</td>
                                <td>{{ $allocation->allocation_percent ? $allocation->allocation_percent . '%' : ' ' }}</td>
                                <td>{{ $allocation->additional_pay_fixed_monthly ? '$' . $allocation->additional_pay_fixed_monthly : ' ' }}</td>
                                <td>{{ eDate($allocation->start_at) }}</td>
                                <td>{{ eDate($allocation->end_at) }}</td>
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
