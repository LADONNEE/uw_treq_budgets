@extends('layout/htmlpage')
@section('title', 'Edit Cost center Approver')
@section('content')

    <div class="center-panel mw-600 input-width">

        @include('costcenters._header')

        {!! $form->open(action('CostcentersController@update', [$costcenter->id])) !!}

        @include('costcenters._form-inputs')

        <div class="submitbox">
            <button type="submit" class="btn">Save</button>
        </div>

        {!! $form->close() !!}

    </div>
@stop
