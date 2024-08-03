<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Repository\CourseRepository;
use App\Repository\GradeRepository;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private $courseRepo, $repo;
    public function __construct(CourseRepository $courseRepo, GradeRepository $repo)
    {
        $this->courseRepo = $courseRepo;
        $this->repo = $repo;
    }

    public function index()
    {
        try {
            if (request()->ajax()) {
                $courses = $this->courseRepo->dataTable();
                return DataTables::of($courses)
                    ->addIndexColumn()
                    ->rawColumns([])
                    ->make(true);
            }
            $grades = $this->repo->get();
            return view('course.index')->with(['grades' => $grades]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error']);
        }
    }

    public function store(CourseRequest $request)
    {
        try {
            $data = $this->courseRepo->store($request->validated());
            return response()->json(['message' => 'Course added successfully!', 'type' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error']);
        }
    }

    public function view($id)
    {
        try {
            $course = $this->courseRepo->find($id);
            $detail = $this->courseRepo->findWithRelations($id);
            $course->image_url = $course->image ? asset('uploads/courses/' . $course->image) : null;
            return view('course.view')->with(['course' => $course, 'detail' => $detail]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error']);
        }
    }
    public function edit($id)
    {
        try {
            $course = $this->courseRepo->find($id);
            $course->image_url = $course->image ? asset('uploads/courses/' . $course->image) : null;
            return response()->json($course);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error']);
        }
    }


    public function update(CourseRequest $request, $id)
    {
        try {
            $this->courseRepo->update($request->validated(), $id);
            return response()->json(['message' => 'Course updated successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error']);
        }
    }

    public function delete($id)
    {
        try {
            $this->courseRepo->delete($id);
            return response()->json(['message' => 'Course deleted successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error']);
        }
    }

    public function getSubjects(Request $request)
    {
        try {
            $gradeId = $request->input('grade_id');
            $subjects = $this->courseRepo->getSubjectsByGrade($gradeId);
            return response()->json($subjects);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error']);
        }
    }
}
