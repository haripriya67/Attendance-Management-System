<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('students.index');
    }

    public function fetchStudents()
    {
        $students = Student::all();
        return response()->json(['students' => $students]);
    }

    public function store(Request $request)
    {
        $student = Student::create($request->all());
        return response()->json(['student' => $student]);
    }

    public function edit($id)
    {
        $student = Student::find($id);
        return response()->json(['student' => $student]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        $student->update($request->all());
        return response()->json(['student' => $student]);
    }

    public function destroy($id)
    {
        Student::destroy($id);
        return response()->json(['success' => true]);
    }
}
