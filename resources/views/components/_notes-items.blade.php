@foreach($notes as $note)

    <div class="note-item">
        <div class="note-heading">
            <span class="note-author">{{ eFirstLast($note->createdBy) }}</span>
            <span class="note-posted">{{ $note->created_at->diffForHumans() }}</span>
            @if(isset($editNoteAction) && is_callable($editNoteAction) && $note->userCanEdit(user()))
                <span class="note-edit-action">
                    <a href="{{ call_user_func($editNoteAction, $note->id) }}">edit</a>
                </span>
            @endif
        </div>
        <div class="note-body">{{ $note->note ?? $note->comment }}</div>
    </div>

@endforeach