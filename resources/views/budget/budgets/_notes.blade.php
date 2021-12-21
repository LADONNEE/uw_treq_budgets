
<div id="student_comments" data-refresh="{{ action('NotesController@index', $budget->id) }}">

    @include('budget/notes/_index')

</div>
