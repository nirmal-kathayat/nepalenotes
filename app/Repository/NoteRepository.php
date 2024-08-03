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
                'description' => $data['description']
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
            $path = $image->move(public_path('uploads/notes'), $filename);
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
}
