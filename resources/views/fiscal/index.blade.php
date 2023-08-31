@extends('layout/htmlpage')
@section('title', 'Fiscal Team')
@section('content')

    <h1>Fiscal Team</h1>

    @if (hasRole('budget:admin'))

        @include('fiscal/_add-form')

    @endif

    <table class="sortable">
        <thead>
        <tr>
            <th>Fiscal contacts</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Office</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($fiscals as $fiscal)

            <tr>
                <td><a href="{!! action('Budget\FiscalController@show', $fiscal->person_id) !!}">{{ eLastFirst($fiscal) }}</a></td>
                <td>{{ $fiscal->email }}</td>
                <td>{{ $fiscal->phone }}</td>
                <td>{{ $fiscal->office }}</td>
            </tr>

        @endforeach
        </tbody>
    </table>

@stop
