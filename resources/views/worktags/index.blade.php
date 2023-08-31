@extends('layout.htmlpage')
@section('title', 'Worktags')
@section('content')
    @include('worktags._menu')

    <h1 class="mb-4">Worktags</h1>

    <p>
        UWFT Workday worktags that are related to the iSchool. For Cost Center worktags and
        Program worktags we import worktags based on the relevant worktag hierarchy, starting with
        the top "iSchool" hierarchy. We also import any worktag configured as a driver
        that references a iSchool Cost Center. This gets us Gift, Grant, and also Program worktags.
        (Work in progress.)
    </p>

    @if(count($worktags) === 0)
        <div class="emptytable">No worktags.</div>
    @else

        <table>
            <thead>
            <tr>
                <th>Type</th>
                <th>Worktag</th>
                <th>Budget Count</th>
                <th>Driver for Cost Center</th>
            </tr>
            </thead>

            <tbody>
            @foreach($worktags as $worktag)
                <tr>
                    <td>{{ $worktag->tag_type }}</td>
                    <td>
                        <a href="{{ route('worktag', $worktag->id) }}">
                            @icon('tag') {{ $worktag->name }}
                        </a>
                    </td>
                    <td>{{ (int) $worktag->budget_count }}</td>
                    <td>{{ ($worktag->hasCostCenter()) ? $worktag->costCenter->name : '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @endif

@stop
