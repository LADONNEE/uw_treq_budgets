@extends('layout/htmlpage')
@section('title', 'Note - ' . $budget->BudgetNbr)
@section('content')

    <h1>{{ eBudget($budget->BudgetNbr) }} {{$budget->BudgetName }}</h1>

    <h2>New Budget Note</h2>

    {!! $form->open(action('NotesController@create', $budget->id)) !!}

    <div>
        @input('note', [
        'rows' => 6,
        'style' => 'width: 100%'
        ])
    </div>

    <div id="all_submit_buttons">
        <input type="submit" value="Save" class="btn" />
        <a href="{{ action('IndexController@show', $budget->id) }}" class="btn">Cancel</a>
    </div>

    {!! $form->close() !!}

@stop
