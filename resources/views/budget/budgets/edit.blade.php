@extends('layout/htmlpage')
@section('title', 'Edit Budget')
@section('content')

    <div class="center-panel mw-600 input-width">

        @include('budget.budgets._header')

        {!! $form->open(action('BudgetsController@update', [$budget->id])) !!}

        @include('budget.budgets._form-inputs')

        <div class="submitbox">
            <button type="submit" class="btn">Save</button>
        </div>

        {!! $form->close() !!}

    </div>
@stop
