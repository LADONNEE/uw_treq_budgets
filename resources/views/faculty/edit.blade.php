@extends('layout/htmlpage')
@section('title', 'Edit Faculty')
@section('content')
    <div>
        <h1 class="mt-0 mb-4">Edit Faculty</h1>

        <p class="mb-4">Enter faculty name and contact information. As you fill this form suggested people may appear
            on the right. Whenever possible select a matching suggested person.</p>

        <person-suggester
            suggest-url="{{ action('ContactController@index') }}"
            submit-url="{{ action('FacultyController@store') }}"
            token="{{ csrf_token() }}"
        ></person-suggester>
    </div>
@stop
@section('state')
    <script type="text/javascript">
        window.facultyFormState = {!! json_encode($form->all()) !!};
    </script>
@stop
