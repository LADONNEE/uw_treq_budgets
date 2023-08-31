@extends('layout/htmlpage')
@section('title', 'Budgets')
@section('content')

    <ul class="nav nav-tabs" role="tablist">
        @foreach($bienniums as $year)
            <?php $active = ($year == $biennium) ? 'active' : ''; ?>

            <li class="nav-item" role="presentation">
                <a class="nav-link {!! $active !!}" href="{{ action('BudgetsController@biennium', $year) }}" aria-controls="{{ $year }}">{{ $year }}</a>
            </li>

        @endforeach
    </ul>

    <h1 class="mt-3">Budgets &mdash; {{ $biennium }} Biennium</h1>

    @include('budgets/_table')

@stop
