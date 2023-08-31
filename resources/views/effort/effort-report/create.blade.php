@extends('layout/htmlpage')
@section('title', 'Create Effort Report')
@section('content')

    <form method="POST" action="{{ route('effort-report-store', $faculty) }}">
        @csrf
        <input type="hidden" value="{{ $reportPeriod->type }}" name="type">
        <input type="hidden" value="{{ $reportPeriod->year }}" name="year">

        @include('effort._header')

        @if(count($allocations) === 0)
            <p class="empty">There are no allocations for this period.</p>
        @else
            @if(count($effortReport->getReportsThatWillBeSuperseded()) > 0)
                <div class="alert alert-warning mt-4" role="alert">
                    This report will supersede the following report(s):
                    <ul>
                        @foreach($effortReport->getReportsThatWillBeSuperseded() as $report)
                            <li>
                                <a href="{{ route('effort-report-show', [$faculty, $report]) }}" target="_blank">
                                    {{ eDate($report->created_at) }} report created by {{ $report->creator->firstname }} {{ $report->creator->lastname }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="bg-light pt-3 pr-5 pl-5 pb-3 mb-4">
                <h2>Allocations</h2>
                @include('effort.allocations._allocations-table')
            </section>

            <section class="bg-light pt-3 pr-5 pl-5 pb-3 mb-4">
                <h2>Approvers</h2>
                <ul>
                    @foreach($approvals as $approval)
                        <li>{{ $approval->name }}</li>
                    @endforeach
                </ul>

                @if($invalidPeriods)
                    <?php $periods = implode(' and ', $invalidPeriods) ?>
                    <div class="alert alert-danger mt-4" role="alert">
                        Allocation percentages add up to more than 100% for the time period(s) starting with
                        {{ $periods }}. You must return to the previous page to make corrections in order to
                        submit this snapshot.
                    </div>
                @endif

                <button class="btn btn-primary mt-3" type="submit" @if($invalidPeriods) disabled @endif>Submit for Approval</button>
            </section>
        @endif
    </form>
@stop

