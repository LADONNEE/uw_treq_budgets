@extends('layout/htmlpage')
@section('title', 'Who Am I?')
@section('content')
    <div class="panel panel-ribbon mw-600">
        <h1 class="h3 text-muted my-3">Who Am I?</h1>

        <div class="card mt-4" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">{{ eFirstLast(user()->person_id) }} ({{ user()->uwnetid }})</h5>
                <p class="card-text">{{ implode(', ', user()->getRoles()) }}</p>
            </div>
        </div>

        <p class="my-4"><a href="{{ action('WhoamiController@logout') }}">Logout</a></p>

        @if(hasRole('budget:super'))

            <hr>

            <p>Allows a super user to view this system as another user for testing and support.</p>

            <form action="{{ action('WhoamiController@update') }}" method="post">
                @csrf()

                <div class="form-group" style="width:18rem;">
                    <label for="uwnetid">Spoof UW NetID</label>
                    <div class="input-group mb-3" style="width: 12rem;">
                        <input type="text" name="uwnetid" id="uwnetid" value="" class="form-control">
                        <div class="input-group-append">
                            <button class="btn btn-secondary">Set</button>
                        </div>
                    </div>
                </div>

            </form>
        @endif
    </div>
@stop
