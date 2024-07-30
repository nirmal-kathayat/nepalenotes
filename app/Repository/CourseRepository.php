<?php

namespace App\Repository;

use App\Models\Course;

class CourseRepository
{
    private $query;
    public function __construct(Course $query)
    {
        $this->query = $query;
    }

    public function dataTable()
    {
        return $this->query
            ->leftJoin('grades', 'grades.id', '=', 'courses.grade_id')
            ->select('courses.id', 'courses.title', 'courses.description', 'grades.title as grade_title');
    }
    public function store(array $data)
    {
        if (isset($data['image'])) {
            $destination = public_path('uploads/courses/');
            $imageName = time() . '_' . $data['image']->getClientOriginalName();
            $data['image']->move($destination, $imageName);
            $data['image'] = $imageName;
        }

        return $this->query->create([
            'grade_id' => $data['grade_id'],
            'title' => $data['title'],
            'image' => $data['image'],
            'description' => $data['description'],
        ]);
    }
    public function find($id)
    {
        return $this->query->findOrFail($id);
    }

    
    public function update(array $data, int $id)
    {
        $course = $this->find($id);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $destination = 'uploads/courses/';
            $imageName = time() . '_' . $data['image']->getClientOriginalName();
            $data['image']->move(public_path($destination), $imageName);

            // Delete old image
            if (!empty($course->image) && file_exists(public_path($destination . $course->image))) {
                unlink(public_path($destination . $course->image));
            }

            $data['image'] = $imageName;
        } else {
            
            unset($data['image']);
        }

        return $this->query->where('id', $id)->update([
            'grade_id' => $data['grade_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $data['image'] ?? $course->image, 
        ]);
    }

    public function delete($id)
    {
        $course = $this->find($id);
        if (!empty($course->image) && file_exists(public_path() . '/uploads/courses/' . $course->image)) {
            $file_path = 'uploads/courses/' . $course->image;
            unlink($file_path);
        }
        return $this->query->where('id', $id)->delete($id);
    }
}
