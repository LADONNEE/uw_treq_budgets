<div class="worktag-branch">
    <div class="worktag-branch__name">
        @icon('code-branch') {{ $branch->workday_code }} {{ $branch->name }}
    </div>
    <div class="worktag-branch__members">
        @if(count($branch->worktags) > 0)
            @foreach($branch->worktags as $worktag)

                <div class="worktag-branch__worktag">
                    <a href="{{ route('worktag', $worktag->id) }}">
                        @icon('tag') {{ $worktag->name }} ({{ $worktag->budget_count }})
                    </a>
                </div>

            @endforeach
        @endif
        @foreach($branch->children as $child)
            @include('hierarchy._branch', ['branch' => $child])
        @endforeach
    </div>
</div>
