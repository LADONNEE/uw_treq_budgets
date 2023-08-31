
@component('components/_modal')
    @slot('header')
        Watch Budget
    @endslot

    <h3>{{ eBudget($budget->BudgetNbr) }} {{$budget->BudgetName }}</h3>

    @include('watch/_user-watch-form')


@endcomponent
