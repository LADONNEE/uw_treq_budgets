@extends('layout.htmlpage')
@section('title', $tree->name . ' Hierarchy')
@section('content')
    @include('worktags._menu')

    <h1 class="mb-4">{{ $tree->name }} Hierarchy</h1>

    @foreach($tree->children as $child)
        @include('hierarchy._branch', ['branch' => $child])
    @endforeach
    @if(count($tree->worktags) > 0)
        <h3>Orphan Worktags</h3>

        <p>The hierarchy object for these {{ $tree->name }} worktags are missing.</p>
        @foreach($tree->worktags as $worktag)

            <div class="worktag-branch__worktag">
                <a href="{{ route('worktag', $worktag->id) }}">
                    @icon('tag') {{ $worktag->name }} ({{ $worktag->budget_count }})
                </a>
            </div>

        @endforeach
    @endif
@stop
