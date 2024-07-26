<?php

namespace App\Repository;

use App\Models\Faculty;

class FacultyRepository
{
  private $query;
  public function __construct(Faculty $query)
  {
    $this->query = $query;
  }
  public function get()
  {
    return $this->query
      ->leftJoin('grades', 'grades.id', '=', 'faculties.grade_id')
      ->select('faculties.id', 'faculties.title', 'grades.title as grade_title', 'faculties.created_at')
      ->get();
  }

  public function storeFaculty(array $data)
  {
    return $this->query->create([
      'grade_id' => $data['grade_id'],
      'title' => $data['title']
    ]);
  }
  public function find($id)
  {
    return $this->query->findOrFail($id);
  }
  public function update(array $data, int $id)
  {
    $query = [
      'grade_id' => $data['grade_id'],
      'title' => $data['title']
    ];
    return $this->query->where('id', $id)->update($query);
  }
  public function delete($id)
  {
    return $this->query->where('id', $id)->delete($id);
  }
}
