<?php

namespace App\Repository;

use App\Models\Course;
use App\Models\Subject;

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
            ->leftJoin('subjects', 'subjects.id', '=', 'courses.subject_id')
            ->select('courses.id', 'courses.title', 'courses.description', 'grades.title as grade_title', 'subjects.title as subject_title');
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
            'subject_id' => $data['subject_id'],
            'title' => $data['title'],
            'image' => $data['image'],
            'description' => $data['description'],
        ]);
    }
    public function find($id)
    {
        return $this->query->findOrFail($id);
    }


    // public function update(array $data, int $id)
    // {
    //     $course = $this->find($id);

    //     if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
    //         $destination = 'uploads/courses/';
    //         $imageName = time() . '_' . $data['image']->getClientOriginalName();
    //         $data['image']->move(public_path($destination), $imageName);

    //         // Delete old image
    //         if ($course->image && file_exists(public_path($destination . $course->image))) {
    //             unlink(public_path($destination . $course->image));
    //         }

    //         $data['image'] = $imageName;
    //     } else {
    //         // Keep the existing image if no new image is uploaded
    //         unset($data['image']);
    //     }

    //     $course->update([
    //         'grade_id' => $data['grade_id'],
    //         'subject_id' => $data['subject_id'],
    //         'title' => $data['title'],
    //         'description' => $data['description'],
    //         'image' => $data['image'] ?? $course->image,
    //     ]);

    //     return $course;
    // }
    public function update(array $data, int $id)
    {
        $course = $this->find($id);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $destination = 'uploads/courses/';
            $imageName = time() . '_' . $data['image']->getClientOriginalName();
            $data['image']->move(public_path($destination), $imageName);

            // Delete old image
            if ($course->image && file_exists(public_path($destination . $course->image))) {
                unlink(public_path($destination . $course->image));
            }

            $data['image'] = $imageName;
        } else {
            // Keep the existing image if no new image is uploaded
            unset($data['image']);
        }

        $course->update([
            'grade_id' => $data['grade_id'],
            'subject_id' => $data['subject_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $data['image'] ?? $course->image,
        ]);

        return $course;
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

    public function getSubjectsByGrade($gradeId)
    {
        return Subject::where('grade_id', $gradeId)->get(['id', 'title']);
    }

    public function findWithRelations($id)
    {
        return $this->query
            ->with(['grade:id,title', 'subject:id,title'])
            ->findOrFail($id);
    }
}
