@if(count($budgets) == 0)

    <div class="emptytable">{{ $empty ?? 'No budgets matching this report.' }}</div>

@else

    <div class="download_link"><a href="{{ downloadHref() }}">Download CSV spreadsheet</a></div>

    <table class="sortable js-has-budget-managers">
        <thead>
        <tr>
            <th>Number</th>
            <th>Biennium</th>
            <th>Name</th>
            <th>Status</th>
            <th>Administrative PI/Director</th>
            <th>Purpose</th>
            <th>Budget Manager</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($budgets as $budget)

            <tr id="budget-{{ $budget->id }}">
                <td><a href="{!! action('BudgetsController@show', $budget->id) !!}">{{ $budget->budgetno }}</a></td>
                <td>{{ $budget->biennium }}</td>
                <td>{{ $budget->name }}</td>
                <td>{!! $budget->getStatusShort() !!}</td>
                <td>{{ eFirstLast($budget->business) }}</td>
                <td>{{ $budget->purpose_brief }}</td>
                <td><a href="{{ action('ManagersController@edit', $budget->id) }}" class="js-budget-manager">{!! eOrEmpty(eFirstLast($budget->fiscal_person_id)) !!}</a></td>
            </tr>

        @endforeach
        </tbody>
    </table>

@endif
