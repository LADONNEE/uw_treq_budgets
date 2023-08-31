@extends('layout/htmlpage')
@section('title', 'Users - Add Person')
@section('content')

    <h1>Users - Add Person</h1>

    <p>
        If you could not find the person searching by Name or UW NetID you can add the person. Person must
        have a valid UW NetID to be added a Budget database user.
    </p>

    {!! $form->open(action('UsersController@store')) !!}


    <div class="col-md-3">
        @input('id')
        @input('person_id')
        @inputBlock('uwnetid')
        @inputBlock('firstname')
        @inputBlock('lastname')
    </div>


    <div class="submitbox">
        <input type="submit" value="Add User" class="btn" />
    </div>

    {!! $form->close() !!}

@stop
