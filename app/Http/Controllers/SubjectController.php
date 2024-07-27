<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Repository\FacultyRepository;
use App\Repository\GradeRepository;
use App\Repository\SubjectRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubjectController extends Controller
{
    private $repo, $facultyRepo, $subjectRepo;
    public function __construct(SubjectRepository $subjectRepo, GradeRepository $repo, FacultyRepository $facultyRepo)
    {
        $this->repo = $repo;
        $this->facultyRepo = $facultyRepo;
        $this->subjectRepo = $subjectRepo;
    }

    public function index()
    {
        try {
            if (request()->ajax()) {
                $subjects = $this->subjectRepo->get();
                return DataTables::of($subjects)
                    ->addIndexColumn()
                    ->rawColumns([])
                    ->make(true);
            }
            $faculties = $this->facultyRepo->get();
            $grades = $this->repo->get();
            return view('subject.index')->with(['faculties' => $faculties, 'grades' => $grades]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error', 'type' => 'error']);
        }
    }

    public function store(SubjectRequest $request)
    {
        try {
            $data = $this->subjectRepo->storeSubject($request->validated());
            return response()->json(['message' => 'Subject added successfully!', 'type' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error', 'type' => 'error']);
        }
    }

    public function edit($id)
    {
        try {
            $editSubject = $this->subjectRepo->find($id);
            return response()->json($editSubject);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error', 'type' => 'error']);
        }
    }

    public function update(SubjectRequest $request, $id)
    {
        try {
            $this->subjectRepo->update($request->validated(),$id);
            return response()->json(['message'=>'subject updated successfully!','type'=>'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error', 'type' => 'error']);
        }
    }

    public function delete($id)
    {
        try{
            $this->subjectRepo->delete($id);
            return response()->json(['message'=>'Subject deleted successfully!','type'=>'success']);
        }catch(\Exception $e){
            return response()->json(['message' => 'Something went wrong!', 'type' => 'error', 'type' => 'error']);
        }
    }
}
