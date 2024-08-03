<?php

namespace App\Repository;

use App\Models\Subject;

class SubjectRepository
{
    private $query;
    public function __construct(Subject $query)
    {
        $this->query = $query;
    }

    public function get()
    {
        return $this->query
            ->leftJoin('grades', 'grades.id', '=', 'subjects.grade_id')
            ->leftJoin('faculties', 'faculties.id', '=', 'subjects.faculty_id')
            ->select(
                'subjects.id',
                'subjects.title',
                'grades.title as grade_title',
                'faculties.title as faculty_title',
                'subjects.created_at'
            )
            ->get();
    }
    public function storeSubject(array $data)
    {
        $query = [
            'grade_id' => $data['grade_id'],
            'faculty_id' => $data['faculty_id'],
            'title' => $data['title']
        ];
        return $this->query->create($query);
    }


    public function find($id)
    {
        return $this->query->findOrFail($id);
    }

    public function update(array $data, int $id)
    {
        return $this->query->where('id', $id)->update([
            'grade_id' => $data['grade_id'],
            'faculty_id' => $data['faculty_id'],
            'title' => $data['title']
        ]);
    }

    public function delete($id)
    {
        return $this->query->where('id', $id)->delete($id);
    }
}
