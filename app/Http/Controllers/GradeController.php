<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeRequest;
use App\Repository\GradeRepository;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    private $repo;
    public function __construct(GradeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        try {
            if (request()->ajax()) {
                $grades = $this->repo->get();
                return DataTables::of($grades)
                    ->addIndexColumn()
                    ->rawColumns([])
                    ->make(true);
            }
            return view('grades.index');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error', 'type' => 'success']);
        }
    }

    public function store(GradeRequest $request)
    {
        try {
            $data = $this->repo->store($request->validated());
            return response()->json(['message' => 'Grade added successfully', 'type' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error', 'type' => 'success']);
        }
    }

    public function edit($id)
    {
        try {
            $editGrade = $this->repo->find($id);
            return response()->json($editGrade);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error', 'type' => 'success']);
        }
    }

    public function update(GradeRequest $request, $id)
    {
        try {
            $this->repo->update($request->validated(), $id);
            return response()->json(['message' => 'Grade updated successfully', 'type' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error', 'type' => 'success']);
        }
    }

    public function delete($id)
    {
        try {
            $this->repo->delete($id);
            return response()->json(['message' => 'Grade deleted successfully', 'type' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error', 'type' => 'success']);
        }
    }
}
