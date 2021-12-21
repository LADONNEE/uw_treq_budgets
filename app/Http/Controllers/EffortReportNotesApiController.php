<?php
namespace App\Http\Controllers;

use App\Models\EffortReport;
use App\Models\EffortReportNote;
use App\Trackers\LoggedNote;
use App\User;

class EffortReportNotesApiController extends Controller
{
    public function index(EffortReport $effortReport, $section = null)
    {
        $query = $effortReport->notes();

        if ($section && $section !== EffortReportNote::DEFAULT_SECTION) {
            $query->where('section', $section);
        }

        return response()->json($this->annotatedNoteList($query->get()));
    }

    public function store(EffortReport $effortReport, $section)
    {
        $noteText = request('note');
        if (!$noteText) {
            return response()->json([
                'http_status' => 400,
                'message' => 'Ignored. Empty note.',
            ], 400);
        }

        $note = new EffortReportNote([
            'report_id' => $effortReport->id,
            'section' => $section,
            'note' => $noteText,
        ]);

        $note->editedBy(user());
        $note->save();

        return response()->json($this->annotatedNote($note));
    }

    public function update(EffortReportNote $note)
    {
        if (request('_action') === 'delete') {
            return $this->destroy($note);
        }

        $note->edit(request('note'));

        return response()->json($this->annotatedNote($note));
    }

    public function destroy(EffortReportNote $note)
    {
        $note->delete();

        return response()->json([
            'message' => 'deleted',
            'id' => $note->id,
        ]);
    }

    private function annotatedNote(EffortReportNote $note)
    {
        if (!$note->relationLoaded('contact')) {
            $note->load('contact');
        }
        $out = $note->toArray();

        if ($note->wasEdited()) {
            $out['edited'] = $note->editedMessage();
        } else {
            $out['edited'] = false;
        }

        $out['can_edit'] = $note->userCanEdit(user());

        return $out;
    }

    private function annotatedNoteList($items)
    {
        $out = [];
        foreach ($items as $note) {
            $out[] = $this->annotatedNote($note);
        }

        return $out;
    }
}
