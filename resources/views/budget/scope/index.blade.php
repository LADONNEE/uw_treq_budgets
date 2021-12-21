@extends('layout/htmlpage')
@section('title', 'Budget Scope')
@section('content')

    <h1>Budget Scope</h1>

    <p>
        UW budget data is updated daily from the UW Enterprise Data Warehouse. The scope of budgets
        included in the system is displayed here.
    </p>

    <h2>Configuration</h2>

    <p>Budgets where the College Org Code (aka Dean's Org Code) is one of:</p>

    <ul class="pl-3">
        @foreach($scope['college-codes'] as $code => $name)

            <li class="monospace">{{ $code }} {{ $name }}</li>

        @endforeach
    </ul>

    <p>Plus budgets where the unit Org Code is one of:</p>

    <ul class="pl-3">
        @foreach($scope['org-codes'] as $code => $name)

            <li class="monospace">{{ $code }} {{ $name }}</li>

        @endforeach
    </ul>

    <p>Plus these specific budgets:</p>

    <ul class="pl-3">
        @foreach($scope['budgets'] as $code => $name)

            <li class="monospace">{{ $code }} {{ $name }}</li>

        @endforeach
    </ul>

    <hr>

    <p>
        Budget scope is defined in the application configuration. Email IT &lt;envithelp@uw.edu&gt;
        for changes.
    </p>

@stop
