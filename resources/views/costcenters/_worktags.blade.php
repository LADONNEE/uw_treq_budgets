@if(count($budget->worktags) === 0)

    <div class="emptytable">
        No worktags are mapped from this Budget Number.
    </div>

@else

    <ul>
        @foreach($budget->worktags as $worktag)
            <li>{{ $worktag->name }}</li>
        @endforeach
    </ul>

@endif