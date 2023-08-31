@extends('layout/htmlpage')
@section('title', eFirstLast($person))
@section('content')

    @if (hasRole('budget:admin'))

        @include('fiscal/_remove-form')

    @endif

    <h1>{{ eFirstLast($person) }} is Fiscal Contact</h1>

    <br>
    <ul class="nav nav-tabs" role="tablist">
        @foreach($bienniums as $year)
            <?php $active = ($year == $biennium) ? ' class="active"' : ''; ?>

            <li role="presentation"{!! $active !!}><a href="{{ action('Budget\FiscalController@show', [$person->person_id, $year]) }}" aria-controls="{{ $year }}">{{ $year }}</a></li>

        @endforeach
    </ul>

    <h2>{{ $biennium }} Biennium</h2>

    @include('_budgets-table', [
        'budgets' => $budgets,
        'empty'   => 'No budgets with ' . eFirstLast($person) . ' assigned.',
    ])


@stop
