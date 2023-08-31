@component('components/_modal')

    <h1 class="text-lg mb-4">{{ $budget->budgetno }} {{ $budget->name }}</h1>

    @include('managers/_content')

@endcomponent
