<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\ExamGroup;
use App\Models\ExamViolation;
use App\Models\Grade;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * confirmation
     *
     * @param  mixed  $id
     * @return void
     */
    public function confirmation($id)
    {
        // get exam group
        $exam_group = ExamGroup::with('exam.lesson', 'exam_session', 'student.classroom')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('id', $id)
            ->first();

        // get grade / nilai
        $grade = Grade::where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        // return with inertia
        return inertia('Student/Exams/Confirmation', [
            'exam_group' => $exam_group,
            'grade' => $grade,
        ]);
    }

    /**
     * startExam
     *
     * @param  mixed  $id
     * @return void
     */
    public function startExam($id)
    {
        // get exam group
        $exam_group = ExamGroup::with('exam.lesson', 'exam_session', 'student.classroom')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('id', $id)
            ->first();

        // get grade / nilai
        $grade = Grade::where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        // update start time di table grades
        $grade->start_time = Carbon::now();
        $grade->update();

        // cek apakah questions / soal ujian di random
        if ($exam_group->exam->random_question == 'Y') {

            // get questions / soal ujian
            $questions = Question::where('exam_id', $exam_group->exam->id)->inRandomOrder()->get();
        } else {

            // get questions / soal ujian
            $questions = Question::where('exam_id', $exam_group->exam->id)->get();
        }

        // define pilihan jawaban default
        $question_order = 1;

        foreach ($questions as $question) {

            // buat array jawaban / answer
            $options = [1, 2];
            if (! empty($question->option_3)) {
                $options[] = 3;
            }
            if (! empty($question->option_4)) {
                $options[] = 4;
            }
            if (! empty($question->option_5)) {
                $options[] = 5;
            }

            // acak jawaban / answer
            if ($exam_group->exam->random_answer == 'Y') {
                shuffle($options);
            }

            // cek apakah sudah ada data jawaban
            $answer = Answer::where('student_id', auth()->guard('student')->user()->id)
                ->where('exam_id', $exam_group->exam->id)
                ->where('exam_session_id', $exam_group->exam_session->id)
                ->where('question_id', $question->id)
                ->first();

            // jika sudah ada jawaban / answer
            if ($answer) {

                // update urutan question / soal
                $answer->question_order = $question_order;
                $answer->update();
            } else {

                // buat jawaban default baru
                Answer::create([
                    'exam_id' => $exam_group->exam->id,
                    'exam_session_id' => $exam_group->exam_session->id,
                    'question_id' => $question->id,
                    'student_id' => auth()->guard('student')->user()->id,
                    'question_order' => $question_order,
                    'answer_order' => implode(',', $options),
                    'answer' => 0,
                    'is_correct' => 'N',
                ]);
            }
            $question_order++;
        }

        // redirect ke ujian halaman 1
        return redirect()->route('student.exams.show', [
            'id' => $exam_group->id,
            'page' => 1,
        ]);
    }

    /**
     * show
     *
     * @param  mixed  $id
     * @param  mixed  $page
     * @return void
     */
    public function show($id, $page)
    {
        // get exam group
        $exam_group = ExamGroup::with('exam.lesson', 'exam_session', 'student.classroom')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('id', $id)
            ->first();

        if (! $exam_group) {
            return redirect()->route('student.dashboard');
        }

        // get all questions
        $all_questions = Answer::with('question')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('exam_id', $exam_group->exam->id)
            ->orderBy('question_order', 'ASC')
            ->get();

        // count all question answered
        $question_answered = Answer::with('question')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('exam_id', $exam_group->exam->id)
            ->where('answer', '!=', 0)
            ->count();

        // get question active
        $question_active = Answer::with('question.exam')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('exam_id', $exam_group->exam->id)
            ->where('question_order', $page)
            ->first();

        // explode atau pecah jawaban
        if ($question_active) {
            $answer_order = explode(',', $question_active->answer_order);
        } else {
            $answer_order = [];
        }

        // get duration
        $duration = Grade::where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        // jika duration null, buat baru
        if (! $duration) {
            $duration = new Grade;
            $duration->exam_id = $exam_group->exam->id;
            $duration->exam_session_id = $exam_group->exam_session->id;
            $duration->student_id = auth()->guard('student')->user()->id;
            $duration->duration = $exam_group->exam->duration * 60000;
            $duration->total_correct = 0;
            $duration->grade = 0;
            $duration->save();
        }

        // get current violation count from database
        $violation = ExamViolation::where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        $currentViolationCount = 0;
        if ($violation && $violation->status === 'active') {
            $currentViolationCount = $violation->violation_count;
        }

        // return with inertia
        return inertia('Student/Exams/Show', [
            'id' => (int) $id,
            'page' => (int) $page,
            'exam_group' => $exam_group,
            'all_questions' => $all_questions,
            'question_answered' => $question_answered,
            'question_active' => $question_active,
            'answer_order' => $answer_order,
            'duration' => $duration,
            'current_violation_count' => $currentViolationCount,
        ]);
    }

    /**
     * updateDuration
     *
     * @param  mixed  $request
     * @param  mixed  $grade_id
     * @return void
     */
    public function updateDuration(Request $request, $grade_id)
    {
        $grade = Grade::find($grade_id);
        $grade->duration = $request->duration;
        $grade->update();

        return response()->json([
            'success' => true,
            'message' => 'Duration updated successfully.',
        ]);
    }

    /**
     * answerQuestion
     *
     * @param  mixed  $request
     * @return void
     */
    public function answerQuestion(Request $request)
    {
        // update duration
        $grade = Grade::where('exam_id', $request->exam_id)
            ->where('exam_session_id', $request->exam_session_id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        $grade->duration = $request->duration;
        $grade->update();

        // get question
        $question = Question::find($request->question_id);

        // cek apakah jawaban sudah benar
        if ($question->answer == $request->answer) {

            // jawaban benar
            $result = 'Y';
        } else {

            // jawaban salah
            $result = 'N';
        }

        // get answer
        $answer = Answer::where('exam_id', $request->exam_id)
            ->where('exam_session_id', $request->exam_session_id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('question_id', $request->question_id)
            ->first();

        // update jawaban
        if ($answer) {
            $answer->answer = $request->answer;
            $answer->is_correct = $result;
            $answer->update();
        }

        return redirect()->back();
    }

    /**
     * endExam
     *
     * @param  mixed  $request
     * @return void
     */
    public function endExam(Request $request)
    {
        // count jawaban benar
        $count_correct_answer = Answer::where('exam_id', $request->exam_id)
            ->where('exam_session_id', $request->exam_session_id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('is_correct', 'Y')
            ->count();

        // count jumlah soal
        $count_question = Question::where('exam_id', $request->exam_id)->count();

        // hitung nilai
        $grade_exam = round($count_correct_answer / $count_question * 100, 2);

        // update nilai di table grades
        $grade = Grade::where('exam_id', $request->exam_id)
            ->where('exam_session_id', $request->exam_session_id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        $grade->end_time = Carbon::now();
        $grade->total_correct = $count_correct_answer;
        $grade->grade = $grade_exam;
        $grade->update();

        // redirect hasil
        return redirect()->route('student.exams.resultExam', $request->exam_group_id);
    }

    /**
     * resultExam
     *
     * @param  mixed  $id
     * @return void
     */
    public function resultExam($exam_group_id)
    {
        // get exam group
        $exam_group = ExamGroup::with('exam.lesson', 'exam_session', 'student.classroom')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('id', $exam_group_id)
            ->first();

        // get grade / nilai
        $grade = Grade::where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        // return with inertia
        return inertia('Student/Exams/Result', [
            'exam_group' => $exam_group,
            'grade' => $grade,
        ]);
    }

    public function logViolation(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'exam_session_id' => 'required|exists:exam_sessions,id',
            'exam_group_id' => 'required|exists:exam_groups,id',
            'violation_type' => 'required|string',
            'violation_count' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $studentId = auth()->guard('student')->user()->id;

        $violation = ExamViolation::updateOrCreate(
            [
                'exam_id' => $request->exam_id,
                'exam_session_id' => $request->exam_session_id,
                'student_id' => $studentId,
                'exam_group_id' => $request->exam_group_id,
            ],
            [
                'violation_type' => $request->violation_type,
                'violation_count' => $request->violation_count,
                'description' => $request->description,
                'violated_at' => now(),
                'status' => $request->violation_count >= 3 ? 'ended' : 'active',
            ]
        );

        return response()->json([
            'success' => true,
            'violation' => $violation,
        ]);
    }

    public function checkViolationStatus(Request $request)
    {
        $studentId = auth()->guard('student')->user()->id;

        $violation = ExamViolation::where('exam_id', $request->exam_id)
            ->where('exam_session_id', $request->exam_session_id)
            ->where('student_id', $studentId)
            ->first();

        if ($violation && $violation->status === 'forgiven') {
            $violation->update([
                'status' => 'active',
                'violation_count' => 0,
            ]);

            return response()->json([
                'can_continue' => true,
                'message' => 'Admin telah mengizinkan Anda melanjutkan ujian.',
            ]);
        }

        return response()->json([
            'can_continue' => false,
            'violation' => $violation,
        ]);
    }
}
