<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamResult;

class ExamResultController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
            'correct_answers' => 'required|integer',
            'wrong_answers' => 'required|integer',
        ]);

        $examResult = new ExamResult();
        $examResult->student_id = $request->student_id;
        $examResult->exam_id = $request->exam_id;
        $examResult->correct_answers = $request->correct_answers;
        $examResult->wrong_answers = $request->wrong_answers;
        $examResult->save();

        return response()->json(['message' => 'Exam result saved successfully'], 201);
    }
}
