<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotesRequest;
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
            return response()->json(['success' => 'Note added successfully!', 'data' => $note]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'type' => 'error'], 500);
        }
    }
}
