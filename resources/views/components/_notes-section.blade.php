<div class="js-note-section" data-refresh="{{ $refreshUrl }}">
    @if($addNoteUrl)

    <div class="note-add">
        <div class="note-trigger">
            <a href="{{ $addNoteUrl }}" class="js-note-add">Add a note</a>
            <input style="display:none;" class="js-note-add-alt" type="text" placeholder="Add a note" aria-label="Add a note" />
        </div>
        <div class="note-form-box input-width" style="display:none;"></div>
    </div>

@endif
    <div class="js-note-items">
        @include('components._notes-items')
    </div>
</div>
