@if(count($costcenters) == 0)

    <div class="emptytable">{{ $empty ?? 'No cost centers matching this report.' }}</div>

@else

    <div class="download_link"><a href="{{ downloadHref() }}">Download CSV spreadsheet</a></div>

    <table class="sortable js-has-budget-managers">
        <thead>
        <tr>
            <th>Workday Code</th>
            <th>Name</th>
            <th>Cost center Approver</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($costcenters as $costcenter)

            <tr id="costcenter-{{ $costcenter->id }}">
                <td><a href="{!! action('CostcentersController@show', $costcenter->id) !!}">{{ $costcenter->workday_code }}</a></td>
                <td>{{ $costcenter->name }}</td>
                <td><a href="{{ action('ManagersController@edit', $costcenter->id) }}" class="js-budget-manager">{!! eOrEmpty(eFirstLast($costcenter->fiscal_person_id)) !!}</a></td>
            </tr>

        @endforeach
        </tbody>
    </table>

@endif
