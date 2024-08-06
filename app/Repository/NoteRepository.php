<?php

namespace App\Repository;

use App\Models\Note;
use App\Models\NoteImage;
use Illuminate\Support\Facades\DB;

class NoteRepository
{
    private $query;
    public function __construct(Note $query)
    {
        $this->query = $query;
    }


    public function storeNotes(array $data)
    {
        DB::beginTransaction();

        try {
            $note = $this->query->create([
                'title' => $data['title'],
                'description' => $data['description'],
                'course_id' => $data['course_id']
            ]);

            if (isset($data['images']) && is_array($data['images'])) {
                $imagePaths = $this->handleImageUploads($data['images']);
                $this->storeNoteImages($note->id, $imagePaths);
            }

            DB::commit();
            return $note;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    private function handleImageUploads($images)
    {
        $imagePaths = [];
        foreach ($images as $image) {
            $filename = $image->getClientOriginalName();
            $path = $image->move(public_path('uploads/notes/'), $filename);
            $imagePaths[] = 'uploads/notes/' . $filename;
        }
        return $imagePaths;
    }

    private function storeNoteImages($noteId, $imagePaths)
    {
        foreach ($imagePaths as $path) {
            NoteImage::create([
                'note_id' => $noteId,
                'image_path' => $path
            ]);
        }
    }
    public function getAllNotes()
    {
        return $this->query->with('images')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function find($id)
    {
        return $this->query->findOrFail($id);
    }

    public function updateNotes(array $data, int $id)
    {

        DB::beginTransaction();

        try {
            $note = $this->query->findOrFail($id);

            $note->update([
                'title' => $data['title'],
                'description' => $data['description']
            ]);

            if (isset($data['images']) && is_array($data['images'])) {
                $note->images()->delete();
                $imagePaths = $this->handleImageUploads($data['images']);
                $this->storeNoteImages($note->id, $imagePaths);
            }

            DB::commit();
            return $note;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
