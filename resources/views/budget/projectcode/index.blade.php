@extends('layout/htmlpage')
@section('title', 'Project Codes')
@section('content')

    <h1 class="mt-3">Project Codes</h1>

    <p>
        These are the Project Codes.
    </p>

    @if(count($projectcodes) == 0)

        <div class="emptytable">{{ 'No project codes to display.' }}</div>

    @else

        <div class="download_link"><a href="{{ downloadHref() }}">Download CSV spreadsheet</a></div>

        <table class="sortable js-edit-row">
            <thead>
            <tr>
                <th>Project Description</th>
                <th>Project Code</th>
                <th>Allocation Type / Frequency</th>
                <th>Purpose</th>
                <th>Pre-approval required</th>
                <th>Action Item</th>
                <th>Workday Code</th>
                <th>Workday Description</th>
                <th>Spend Authorizer</th>
                <th>Fiscal Person</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($projectcodes as $projectcode)

                <tr id="projectcode-{{ $projectcode->id }}" data-href="{{ action('ProjectCodeController@edit', $projectcode->id) }}">
                    <td class="js-edit-row--trigger pointer">{{ $projectcode->description }}</td>
                    <td>{{ $projectcode->code }}</td>
                    <td>{{ $projectcode->allocation_type_frequency }}</td>
                    <td>{{ $projectcode->purpose }}</td>
                    <td>{{ $projectcode->pre_approval_required }}</td>
                    <td>{{ $projectcode->action_item }}</td>
                    <td>{{ $projectcode->workday_code }}</td>
                    <td>{{ $projectcode->workday_description }}</td>
                    <td>{{ eFirstLast($projectcode->authorizer_person_id) }}</td>
                    <td>{{ eFirstLast($projectcode->fiscal_person_id) }}</td>

                    
                </tr>

            @endforeach
            </tbody>
        </table>

    @endif

@stop
