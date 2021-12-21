@extends('layout/htmlpage')
@section('title', 'Search Budgets')
@section('content')

    <h1>Budget Search</h1>

    <form method="get" action="{!! action('SearchController@index') !!}">
        <div class="search-form-large">
            <input type="text" name="q" id="search-query" value="{{ $report->getSearchTerm() }}" />
            <input type="submit" value="Search" />
        </div>
    </form>


    @include('budget/budgets/_table', [
        'budgets' => $report->budgets(),
        'empty' => 'No matches for this search.',
    ])

    <h2>Search Help</h2>

    <p>Budget search tool matches budget records on the following fields</p>

    <ul class="bullet">
        <li>Budget numbers (by full six digits or last 4)</li>
        <li>UW Org Codes</li>
        <li>Budget names</li>
        <li>Principal investigator (first, last, or UW NetID)</li>
        <li>Fiscal contact</li>
        <li>UW budget status (enter search term "status=1" or to search for multiple status codes "status=24")</li>
    </ul>

@stop
