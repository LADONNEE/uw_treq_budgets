@extends('layout/htmlpage')
@section('title', eBudget($budget->BudgetNbr))
@section('content')

    <h1>{{ eBudget($budget->BudgetNbr) }} {{$budget->BudgetName }}</h1>

    @include('budget/watch/_user-watch-form')

@stop
