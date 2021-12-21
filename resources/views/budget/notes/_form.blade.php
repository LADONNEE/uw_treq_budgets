
{!! $form->open(action('NotesController@store', $budget->id)) !!}

<div>
    @input('note', [
        'rows' => 6,
        'style' => 'width: 100%'
    ])
</div>

<div>
    <input type="submit" value="Save" class="btn" />
    <button class="cancel_button btn">Cancel</button>
</div>

{!! $form->close() !!}
