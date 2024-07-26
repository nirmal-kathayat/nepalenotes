<?php

namespace App\Http\Controllers;

use App\Http\Requests\FacultyRequest;
use App\Repository\FacultyRepository;
use App\Repository\GradeRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FacultyController extends Controller
{
    private $facultyRepo, $repo;
    public function __construct(FacultyRepository $facultyRepo, GradeRepository $repo)
    {
        $this->facultyRepo = $facultyRepo;
        $this->repo = $repo;
    }

    public function index()
    {
        try {
            if (request()->ajax()) {
                $faculties = $this->facultyRepo->get();
                return DataTables::of($faculties)
                    ->addIndexColumn()
                    ->rawColumns([])
                    ->make(true);
            }
            $grades = $this->repo->get();
            return view('faculty.index')->with(['grades' => $grades]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while storing data', 'type' => 'error', 'type' => 'error']);
        }
    }

    public function store(FacultyRequest $request)
    {
        try {
            $data = $this->facultyRepo->storeFaculty($request->validated());
            return response()->json(['message' => 'Faculty added successfully', 'type' => 'success', 'data' => $data]);
        } catch (\Exception $e) {

            return response()->json(['message' => 'An error occurred while storing data', 'type' => 'error', 'type' => 'error']);
        }
    }
    public function edit($id)
    {
        try {
            $edit = $this->facultyRepo->find($id);
            return response()->json($edit);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while storing data', 'type' => 'error', 'type' => 'error']);
        }
    }

    public function update(FacultyRequest $request, $id)
    {
        try {
            $this->facultyRepo->update($request->validated(), $id);
            return response()->json(['message' => 'Faculty added successfully', 'type' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while storing data', 'type' => 'error', 'type' => 'error']);
        }
    }

    public function delete($id)
    {
        try {
            $this->facultyRepo->delete($id);
            return response()->json(['message' => 'Faculty deleted successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while storing data', 'type' => 'error', 'type' => 'error']);
        }
    }
}
