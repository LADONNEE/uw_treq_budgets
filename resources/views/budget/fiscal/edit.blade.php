@extends('layout/htmlpage')
@section('title', eFirstLast($person) . ' - Contact')
@section('content')

    <h1>Fiscal Contact Information</h1>

    <h2>{{ eFirstLast($person) }}</h2>

    <p>Update contact information people should use for budget questions.</p>

    {!! $form->open(action('FiscalController@update', $person->person_id)) !!}

    @inputBlock('email')
    @inputBlock('phone')
    @inputBlock('office')

    <div>
        <input type="submit" value="Save" class="btn" />
    </div>

    {!! $form->close() !!}
@stop
