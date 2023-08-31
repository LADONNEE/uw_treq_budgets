
<div class="watching">
    @if($budget->isWatchedBy(user()->person_id))

        <p><a href="{{ action('Budget\WatchController@index', $budget->id) }}" class="btn btn-primary js-get-modal" data-modal-size="sm">Watching</a></p>

    @else

        <p><a href="{{ action('Budget\WatchController@index', $budget->id) }}" class="btn btn-primary js-get-modal" data-modal-size="sm">Watch</a></p>

    @endif
</div>