@extends('layout/htmlpage')
@section('title', 'Add Faculty')
@section('content')
    <div>
        <h1 class="mt-0 mb-4">Add Faculty</h1>

        <p class="mb-4">Enter faculty name and contact information. As you fill this form suggested people may appear
            on the right. Whenever possible select a matching suggested person.</p>

        <person-suggester
            suggest-url="{{ action('ContactController@index') }}"
            submit-url="{{ action('FacultyController@store') }}"
            token="{{ csrf_token() }}"
        ></person-suggester>
    </div>
@stop
