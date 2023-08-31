
@if(hasRole('budget:fiscal'))

    <div class="add_comment">
        <a href="{!! action('NotesController@create', $budget->id) !!}" class="link_get_form">Add a note</a>
        <input class="mock_input" type="text" placeholder="Add a note" style="display:none;" />
    </div>

    <div class="form_box add_comment" style="display:none;"></div>

@endif
@foreach($budget->notes as $note)

    <div class="comment">
        <p class="author">{{ eFirstLast($note->person) }} <span>{{ $note->created_at->diffForHumans() }}</span></p>
        <p>
            {!! eBreaks($note->note) !!}

            @if($note->userCanEdit(user()))
                <a href="{!! action('NotesController@edit', $note->id) !!}">edit</a>
            @endif
        </p>
    </div>

@endforeach
