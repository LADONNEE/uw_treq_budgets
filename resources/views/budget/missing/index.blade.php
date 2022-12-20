@extends('layout/htmlpage')
@section('title', 'Missing Records')
@section('content')

    <h1 class="mt-3">Missing Records</h1>

    <p>
        These budgets are missing one or more required fields: Spend Authorizer, Budget Manager,
        Reconciler, Purpose, or Food Policy.
    </p>

    @if(count($budgets) == 0)

        <div class="emptytable">{{ 'No budgets are missing these fields.' }}</div>

    @else

        <div class="download_link"><a href="{{ downloadHref() }}">Download CSV spreadsheet</a></div>

        <table class="sortable js-edit-row">
            <thead>
            <tr>
                <th>Number</th>
                <th>Name</th>
                <th>Spend Authorizer</th>
                <th>Budget Manager</th>
                <th>Reconciler</th>
                <th>Purpose</th>
                <th>Food Policy</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($budgets as $budget)

                <tr id="budget-{{ $budget->id }}" data-href="{{ action('MissingController@edit', $budget->id) }}">
                    <td><a href="{!! action('BudgetsController@show', $budget->id) !!}">{{ $budget->budgetno }}</a></td>
                    <td>{{ $budget->name }}</td>
                    <td>{{ eFirstLast($budget->business) }}</td>
                    <td class="js-edit-row--trigger pointer">{!! eOrEmpty(eFirstLast($budget->fiscal_person_id)) !!}</td>
                    <td class="js-edit-row--trigger pointer">{!! eOrEmpty(eFirstLast($budget->reconciler_person_id)) !!}</td>
                    <td class="js-edit-row--trigger pointer">{!! eOrEmpty($budget->purpose_brief) !!}</td>
                    <td class="js-edit-row--trigger pointer">{!! eOrEmpty($budget->getFoodPolicy()) !!}</td>
                </tr>

            @endforeach
            </tbody>
        </table>

    @endif

@stop
