@extends('layout/htmlpage')
@section('title', 'Cancel Report')
@section('content')

    @include('effort/_header')

    <section class="bg-light pt-3 pr-5 pl-5 pb-3 mb-4">
        <h2>Report Period Allocations</h2>

        <div class="allocations">
            @include('effort.allocations._allocations-table')
        </div>
    </section>

    <section class="py-3 px-5 w-50">
        <h2>Cancel Effort Report</h2>

        {!! $form->open(action('CancelController@update', $effortReport->id)) !!}

        <div class="row my-3">
            <div class="col-12">
                @inputBlock('note', [
                'label' => 'Why is this report being canceled?',
                'rows' => 3
                ])
                <br>
                <div class="alert alert-danger" style="max-width: 500px;">Confirm you want to cancel this effort report.</div>
                <br>
                <div class="input_row">
                    <button type="submit" name="_action" value="delete" class="btn btn-danger">Confirm Canceled</button>
                    <a href="{{ route('effort-report-show', [$faculty, $effortReport->id]) }}" type="submit" class="btn btn-secondary">Go back</a>
                </div>
            </div>
        </div>
    </section>

    {!! $form->close() !!}
@stop
