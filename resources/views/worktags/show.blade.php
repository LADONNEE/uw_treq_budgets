@extends('layout.htmlpage')
@section('title', $worktag->name . ' - Worktags')
@section('content')
    @include('worktags._menu')

    <h1 class="mb-4">@icon('tag') {{ $worktag->name }}</h1>

    <p class="mb-4">
        Displaying configuration of a UWFT Worktag. This feature is a work-in-progress. This information may
        be incomplete or stale.
    </p>

    <section class="mb-4">
        <h2>Driver</h2>

        @if($worktag->hasCostCenter())
            <p>
                This worktag is configured as a driver, when it is selected it will apply this Cost Center worktag.
            </p>

            <p>
                <a href="{{ route('worktag', $worktag->costCenter->id) }}">
                    @icon('tag') {{ $worktag->costCenter->name }}
                </a>
            </p>
        @else
            <p class="empty">
                This worktag is not a driver for any Cost Center.
            </p>
        @endif
    </section>

    <section class="mb-4">
        <h2 class="mb-3">Hierarchy</h2>
        @if(!$hierarchyTree)
            <p>No hierarchy imported for this worktag.</p>
        @else
            @foreach($hierarchyTree as $hierarchy)
                <div class="mb-2">
                    @icon('code-branch') {{ $hierarchy->workday_code }} {{ $hierarchy->name }}
                </div>
            @endforeach

            <div class="mb-2">
                @icon('tag') {{ $worktag->name }}
            </div>
        @endif
    </section>

    <section>
        <h2>Budgets</h2>

        <p class="mb-0">
            Budget Numbers that will be migrated to this Worktag. This information is loaded periodically from
            the <a href="https://uwft-prodweb1.s.uw.edu/FDMTWEB/">FDM Translator Tool</a>.
        </p>

        @include('budgets._table', ['budgets' => $worktag->budgets])
    </section>
@stop
