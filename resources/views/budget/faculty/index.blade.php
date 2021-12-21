@extends('layout/htmlpage')
@section('title', 'Manage Faculty')
@section('content')
    <h1 class="mb-3">Manage Faculty</h1>
    <a class="btn" href="{{ route('faculty-create') }}">+ Faculty</a>

    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-controls="active" aria-selected="true">Active Faculty</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="inactive-tab" data-toggle="tab" href="#inactive" role="tab" aria-controls="inactive" aria-selected="false">Inactive Faculty</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
            <div class="panel pt-3">
                <div class="panel-full-width">
                    <table class="table-tight sortable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>UW NetID</th>
                                <th>Default Budget</th>
                                <th>Finance Manager</th>
                                <th>End Date</th>
                                <th>80/20</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeFaculty as $faculty)
                                <tr>
                                    <td><a class="js-link-row" href="{!! action('FacultyController@edit', [$faculty->id]) !!}">{{ eLastFirst($faculty) }}</a></td>
                                    <td>{{ $faculty->uwnetid }}</td>
                                    <td>
                                        @if ($faculty->default_budget_id)
                                            {{ $faculty->defaultBudget->budgetno }}
                                        @else
                                            <span class="empty">missing</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($faculty->fiscal_person_id)
                                            {{ $faculty->getFiscalPersonName() }}
                                        @else
                                            <span class="empty">missing</span>
                                        @endif
                                    </td>
                                    <td>{{ eDate($faculty->end_at) }}</td>
                                    @if ($faculty->is_80_20)
                                        <td>@icon('check')</td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="inactive" role="tabpanel" aria-labelledby="inactive-tab">
            <div class="panel pt-3">
                <div class="panel-full-width">
                    <table class="table-tight sortable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>UW NetID</th>
                                <th>Default Budget</th>
                                <th>Finance Manager</th>
                                <th>End Date</th>
                                <th>80/20</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inactiveFaculty as $faculty)
                                <tr>
                                    <td><a class="js-link-row" href="{!! action('FacultyController@edit', [$faculty->id]) !!}">{{ eLastFirst($faculty) }}</a></td>
                                    <td>{{ $faculty->uwnetid }}</td>
                                    <td>
                                        @if ($faculty->default_budget_id)
                                            {{ $faculty->defaultBudget->budgetno }}
                                        @else
                                            <span class="empty">missing</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($faculty->fiscal_person_id)
                                            {{ $faculty->getFiscalPersonName() }}
                                        @else
                                            <span class="empty">missing</span>
                                        @endif
                                    </td>
                                    <td>{{ eDate($faculty->end_at) }}</td>
                                    @if ($faculty->is_80_20)
                                        <td>@icon('check')</td>
                                    @else
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
