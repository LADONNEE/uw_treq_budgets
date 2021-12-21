@component('components/_modal')

    <h1 class="text-lg mb-4">{{ $budget->budgetno }} {{ $budget->name }}</h1>

    @include('budget/managers/_content')

@endcomponent
