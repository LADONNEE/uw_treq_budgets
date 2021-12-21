@extends('layout/htmlpage')
@section('title', $budget->budgetno)
@section('content')

    <h1>{{ $budget->budgetno }} {{ $budget->name }}</h1>

    @include('budget/managers/_content')

@stop
