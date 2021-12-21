
<form action="{{ action('Budget\WatchController@update', $budget->id) }}" method="post">
    {!! csrf_field() !!}

@if($budget->isWatchedBy(user()->person_id))

    <p>You are currently watching budget {{ eBudget($budget->BudgetNbr) }} {{$budget->BudgetName }}. Watched budgets
        are listed on the home screen.</p>
    <input type="hidden" name="action" value="stop">
    <p><button class="btn btn-primary">Stop Watching</button></p>

@else

    <p>You are not watching budget {{ eBudget($budget->BudgetNbr) }} {{$budget->BudgetName }}. Watched budgets
        are listed on the home screen.</p>

    <input type="hidden" name="action" value="start">
    <p><button class="btn btn-primary">Watch</button></p>

@endif

</form>
