@extends('layout/htmlpage')
@section('title', 'Notes - ' . $budget->BudgetNbr)
@section('content')

    <h1>{{ eBudget($budget->BudgetNbr) }} {{$budget->BudgetName }}</h1>

    <h2>Budget Notes</h2>

    <div>
        @include('_notes')
    </div>

@stop
