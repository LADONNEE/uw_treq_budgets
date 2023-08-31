@extends('layout/htmlpage', ['searchOpen' => true])
@section('title', 'Budgets')
@section('content')

    <h1>Budgets</h1>

    <h2>My Budgets</h2>

    <p>This list includes budgets you are watching, where you are the Principal Investigator, or where you are the
        fiscal contact.</p>

    @include('budgets/_table')

@stop
