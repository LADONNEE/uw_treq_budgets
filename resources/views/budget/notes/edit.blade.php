@extends('layout/htmlpage')
@section('title', 'Note - ' . $budget->BudgetNbr)
@section('content')

    <h1>{{ $budget->budgetno }} {{ $budget->name }}</h1>

    <h2>Edit Note</h2>

    <div class="mw-600 input_width">
    {!! $form->open(action('NotesController@update', $note->id)) !!}

        <div style="max-width: 400px;">
            @input('note', [
            'rows' => 6,
            'style' => 'width: 100%'
            ])
        </div>

        <div id="all_submit_buttons">
            <button type="submit" name="action" value="save" class="btn">Save</button>
            <button id="trigger_confirm_delete" class="btn">Delete...</button>
            <a href="{{ action('BudgetsController@show', $budget->id) }}" class="btn">Cancel</a>
        </div>

        <div id="confirm_delete" style="display:none;">
            <div class="alert alert-warning" role="alert">Confirm completely delete this note?</div>
            <button type="submit" name="action" value="destroy" class="btn">Delete</button>
            <a href="{{ action('BudgetsController@show', $budget->id) }}" class="btn">Cancel</a>
        </div>

    {!! $form->close() !!}
    </div>

@stop
@section('scripts')

    <script type="text/javascript">
        $( document ).ready(function() {
            $('#trigger_confirm_delete').on('click', function(event) {
                event.preventDefault();
                $('#all_submit_buttons').hide();
                $('#confirm_delete').show();
            });
        });
    </script>

@stop
