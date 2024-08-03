<?php

namespace App\Repository;

use App\Models\Grade;

class GradeRepository
{
    private $query;

    public function __construct(Grade $query)
    {
        $this->query = $query;
    }

    public function get()
    {
        return $this->query->get();
    }
    public function getWithFacultyEligibility()
    {
        return $this->query->select('id', 'title', 'value')
            ->selectRaw('CASE WHEN value >= 10 THEN true ELSE false END as can_have_faculty')
            ->get();
    }

    public function store(array $data)
    {
        return $this->query->create([
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
            'title' => $data['title']
        ];
        
        return $this->query->where('id', $id)->update($query);
    }
    public function delete($id)
    {
        return $this->query->where('id', $id)->delete($id);
    }
}
