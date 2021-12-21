@extends('layout/htmlpage')
@section('title', 'Budget Log')
@section('content')

    @include('budget/part-budget-menu')

    <h1>{{ eBudget($budget->BudgetNbr) }} {{$budget->BudgetName }}</h1>

    <h2>Fiscal Contact Log</h2>

    <ul>
        @foreach ($assignLog as $item)

            <li>
                <span class="log_date">{{ eDate($item->created_at, 'm/d/Y g:i A') }}</span>
                <span class="log_user">{{ $item->uwnetid }}</span>
                <span class="log_event">{{ $item->getDescription() }}</span>
            </li>

        @endforeach
    </ul>


@stop
