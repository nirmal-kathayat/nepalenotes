<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotesRequest;
use App\Models\Note;
use App\Repository\NoteRepository;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    private $noteRepo;
    public function __construct(NoteRepository $noteRepo)
    {
        $this->noteRepo = $noteRepo;
    }

    public function store(NotesRequest $request)
    {
        try {
            $data = $request->all();
            $note = $this->noteRepo->storeNotes($data);
            return response()->json(['success' => 'Note added successfully!', 'note' => $note]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'type' => 'error'], 500);
        }
    }
    public function getNotes(Request $request)
    {
        try {
            $courseId = $request->get('course_id');
            $notes = Note::where('course_id', $courseId)->with('images')->get();
            return response()->json(['notes' => $notes]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'type' => 'error'], 500);
        }
    }
    public function edit($id)
    {
        try {
            $editNote = $this->noteRepo->find($id);
            return response()->json($editNote);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'type' => 'error'], 500);
        }
    }

    public function update(NotesRequest $request, $id)
    {
        try {
            $editNote = $this->noteRepo->updateNotes($request->validated(), $id);
            return response()->json(['success' => 'Note updated successfully!', 'editNote' => $editNote]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'type' => 'error'], 500);
        }
    }
}
