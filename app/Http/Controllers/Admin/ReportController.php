<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use App\Exports\GradesExport;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //geta ll exams
        $exams = Exam::with('lesson', 'classroom')->get();

        return inertia('Admin/Reports/Index', [
            'exams'         => $exams,
            'grades'        => []
        ]);
    }

    /**
     * filter
     *
     * @param  mixed $request
     * @return void
     */
    public function filter(Request $request)
    {
        $request->validate([
            'exam_id'       => 'required',
        ]);

        //geta ll exams
        $exams = Exam::with('lesson', 'classroom')->get();

        //get exam
        $exam = Exam::with('lesson', 'classroom')
            ->where('id', $request->exam_id)
            ->first();

        if ($exam) {

            //get exam session
            $exam_session = ExamSession::where('exam_id', $exam->id)->first();

            //get grades / nilai
            $grades = Grade::with('student', 'exam.classroom', 'exam.lesson', 'exam_session')
                ->where('exam_id', $exam->id)
                ->where('exam_session_id', $exam_session->id)
                ->get();
        } else {
            $grades = [];
        }

        return inertia('Admin/Reports/Index', [
            'exams'         => $exams,
            'grades'         => $grades,
        ]);
    }

    /**
     * export
     *
     * @param  mixed $request
     * @return void
     */
    public function export(Request $request)
    {
        //get exam
        $exam = Exam::with('lesson', 'classroom')
            ->where('id', $request->exam_id)
            ->first();

        //get exam session
        $exam_session = ExamSession::where('exam_id', $exam->id)->first();

        //get grades / nilai
        $grades = Grade::with('student', 'exam.classroom', 'exam.lesson', 'exam_session')
            ->where('exam_id', $exam->id)
            ->where('exam_session_id', $exam_session->id)
            ->get()
            ->map(function ($grade) {
                $grade->questions_with_answers = Answer::with('question')
                    ->where('student_id', $grade->student_id)
                    ->where('exam_id', $grade->exam_id)
                    ->get()
                    ->map(function ($answer) {
                        $options = [
                            '1' => 'A. ' . $answer->question->option_1,
                            '2' => 'B. ' . $answer->question->option_2,
                            '3' => 'C. ' . $answer->question->option_3,
                            '4' => 'D. ' . $answer->question->option_4,
                            '5' => 'E. ' . $answer->question->option_5,
                        ];

                        $questionText = $answer->question->question;

                        $studentAnswer = $options[$answer->answer];
                        $correctAnswer = $options[$answer->question->answer];

                        return [
                            'text' => $questionText,
                            'student_answer' => $studentAnswer,
                            'correct_answer' => $correctAnswer,
                            'is_correct' => $answer->is_correct,
                        ];
                    });
                return $grade;
            });

        return Excel::download(new GradesExport($grades), 'grade : ' . $exam->title . ' — ' . $exam->lesson->title . ' — ' . Carbon::now() . '.xlsx');
    }

    public function show($id)
    {
        $examResult = Grade::with('student', 'exam.classroom', 'exam.lesson', 'exam_session')
            ->where('id', $id)
            ->first();

        $questionsWithAnswers = Answer::with('question')
            ->where('student_id', $examResult->student_id)
            ->where('exam_id', $examResult->exam_id)
            ->get()
            ->map(function ($answer) {
                $options = [
                    '1' => 'A.' . $answer->question->option_1,
                    '2' => 'B.' . $answer->question->option_2,
                    '3' => 'C.' . $answer->question->option_3,
                    '4' => 'D.' . $answer->question->option_4,
                    '5' => 'E.' . $answer->question->option_5,
                ];

                $questionText = $answer->question->question .
                    '<ol type="A" style="list-style-type: upper-alpha;">' .
                    '<li>' . $answer->question->option_1 . '</li>' .
                    '<li>' . $answer->question->option_2 . '</li>' .
                    '<li>' . $answer->question->option_3 . '</li>' .
                    '<li>' . $answer->question->option_4 . '</li>' .
                    '<li>' . $answer->question->option_5 . '</li>' .
                    '</ol>';

                $studentAnswer =  $options[$answer->answer];
                $correctAnswer =  $options[$answer->question->answer];

                return [
                    'text' => $questionText,
                    'student_answer' => $studentAnswer,
                    'correct_answer' => $correctAnswer,
                    'is_correct' => $answer->is_correct,
                ];
            });

        return inertia('Admin/Reports/Show', [
            'examResult' => $examResult,
            'questionsWithAnswers' => $questionsWithAnswers,
        ]);
    }
}
