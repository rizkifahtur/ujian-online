<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Imports\QuestionsImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get exams
        $exams = Exam::when(request()->q, function ($exams) {
            $exams = $exams->where('title', 'like', '%' . request()->q . '%');
        })->with('lesson', 'classroom', 'questions')->latest()->paginate(5);

        //append query string to pagination links
        $exams->appends(['q' => request()->q]);

        //render with inertia
        return inertia('Admin/Exams/Index', [
            'exams' => $exams,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get lessons
        $lessons = Lesson::all();

        //get classrooms
        $classrooms = Classroom::all();

        //render with inertia
        return inertia('Admin/Exams/Create', [
            'lessons' => $lessons,
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate request
        $request->validate([
            'title'             => 'required',
            'lesson_id'         => 'required|integer',
            'classroom_id'      => 'required|integer',
            'duration'          => 'required|integer',
            'description'       => 'required',
            'random_question'   => 'required',
            'random_answer'     => 'required',
            'show_answer'       => 'required',
        ]);

        //create exam
        Exam::create([
            'title'             => $request->title,
            'lesson_id'         => $request->lesson_id,
            'classroom_id'      => $request->classroom_id,
            'duration'          => $request->duration,
            'description'       => $request->description,
            'random_question'   => $request->random_question,
            'random_answer'     => $request->random_answer,
            'show_answer'       => $request->show_answer,
        ]);

        //redirect
        return redirect()->route('admin.exams.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get exam
        $exam = Exam::with('lesson', 'classroom')->findOrFail($id);

        //get relation questions with pagination
        $exam->setRelation('questions', $exam->questions()->paginate(5));

        //render with inertia
        return inertia('Admin/Exams/Show', [
            'exam' => $exam,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //get exam
        $exam = Exam::findOrFail($id);

        //get lessons
        $lessons = Lesson::all();

        //get classrooms
        $classrooms = Classroom::all();

        //render with inertia
        return inertia('Admin/Exams/Edit', [
            'exam' => $exam,
            'lessons' => $lessons,
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        //validate request
        $request->validate([
            'title'             => 'required',
            'lesson_id'         => 'required|integer',
            'classroom_id'      => 'required|integer',
            'duration'          => 'required|integer',
            'description'       => 'required',
            'random_question'   => 'required',
            'random_answer'     => 'required',
            'show_answer'       => 'required',
        ]);

        //update exam
        $exam->update([
            'title'             => $request->title,
            'lesson_id'         => $request->lesson_id,
            'classroom_id'      => $request->classroom_id,
            'duration'          => $request->duration,
            'description'       => $request->description,
            'random_question'   => $request->random_question,
            'random_answer'     => $request->random_answer,
            'show_answer'       => $request->show_answer,
        ]);

        //redirect
        return redirect()->route('admin.exams.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //get exam
        $exam = Exam::findOrFail($id);

        //delete exam
        $exam->delete();

        //redirect
        return redirect()->route('admin.exams.index');
    }

    /**
     * createQuestion
     *
     * @param  mixed $exam
     * @return void
     */
    public function createQuestion(Exam $exam)
    {
        //render with inertia
        return inertia('Admin/Questions/Create', [
            'exam' => $exam,
        ]);
    }

    /**
     * storeQuestion
     *
     * @param  mixed $request
     * @param  mixed $exam
     * @return void
     */
    public function storeQuestion(Request $request, Exam $exam)
    {
        //validate request
        $request->validate([
            'question'          => 'nullable',
            'question_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'option_1'          => 'nullable',
            'option_1_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'option_2'          => 'nullable',
            'option_2_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'option_3'          => 'nullable',
            'option_3_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'option_4'          => 'nullable',
            'option_4_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'option_5'          => 'nullable',
            'option_5_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'answer'            => 'required|integer|min:1',
        ]);

        //validate at least question text or image exists
        if (empty($request->question) && !$request->hasFile('question_image')) {
            return back()->withErrors(['question' => 'Soal harus diisi dengan teks atau gambar.']);
        }

        //validate at least option 1 and 2 have text or image
        if (empty($request->option_1) && !$request->hasFile('option_1_image')) {
            return back()->withErrors(['option_1' => 'Pilihan A harus diisi dengan teks atau gambar.']);
        }
        if (empty($request->option_2) && !$request->hasFile('option_2_image')) {
            return back()->withErrors(['option_2' => 'Pilihan B harus diisi dengan teks atau gambar.']);
        }

        //prepare data
        $data = [
            'exam_id'           => $exam->id,
            'question'          => $request->question,
            'option_1'          => $request->option_1,
            'option_2'          => $request->option_2,
            'option_3'          => $request->option_3,
            'option_4'          => $request->option_4,
            'option_5'          => $request->option_5,
            'answer'            => $request->answer,
        ];

        //handle image uploads
        if ($request->hasFile('question_image')) {
            $data['question_image'] = $request->file('question_image')->store('questions', 'public');
        }
        
        if ($request->hasFile('option_1_image')) {
            $data['option_1_image'] = $request->file('option_1_image')->store('questions', 'public');
        }
        
        if ($request->hasFile('option_2_image')) {
            $data['option_2_image'] = $request->file('option_2_image')->store('questions', 'public');
        }
        
        if ($request->hasFile('option_3_image')) {
            $data['option_3_image'] = $request->file('option_3_image')->store('questions', 'public');
        }
        
        if ($request->hasFile('option_4_image')) {
            $data['option_4_image'] = $request->file('option_4_image')->store('questions', 'public');
        }
        
        if ($request->hasFile('option_5_image')) {
            $data['option_5_image'] = $request->file('option_5_image')->store('questions', 'public');
        }

        //create question
        Question::create($data);

        //redirect
        return redirect()->route('admin.exams.show', $exam->id);
    }

    /**
     * editQuestion
     *
     * @param  mixed $exam
     * @param  mixed $question
     * @return void
     */
    public function editQuestion(Exam $exam, Question $question)
    {
        //render with inertia
        return inertia('Admin/Questions/Edit', [
            'exam' => $exam,
            'question' => $question,
        ]);
    }

    /**
     * updateQuestion
     *
     * @param  mixed $request
     * @param  mixed $exam
     * @param  mixed $question
     * @return void
     */
    public function updateQuestion(Request $request, Exam $exam, Question $question)
    {
        //validate request
        $request->validate([
            'question'          => 'nullable',
            'question_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'option_1'          => 'nullable',
            'option_1_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'option_2'          => 'nullable',
            'option_2_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'option_3'          => 'nullable',
            'option_3_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'option_4'          => 'nullable',
            'option_4_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'option_5'          => 'nullable',
            'option_5_image'    => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'answer'            => 'required|integer|min:1',
        ]);

        //validate at least question text or image exists
        if (empty($request->question) && !$request->hasFile('question_image') && empty($question->question_image)) {
            return back()->withErrors(['question' => 'Soal harus diisi dengan teks atau gambar.']);
        }

        //validate at least option 1 and 2 have text or image
        if (empty($request->option_1) && !$request->hasFile('option_1_image') && empty($question->option_1_image)) {
            return back()->withErrors(['option_1' => 'Pilihan A harus diisi dengan teks atau gambar.']);
        }
        if (empty($request->option_2) && !$request->hasFile('option_2_image') && empty($question->option_2_image)) {
            return back()->withErrors(['option_2' => 'Pilihan B harus diisi dengan teks atau gambar.']);
        }

        //prepare data
        $data = [
            'question'          => $request->question,
            'option_1'          => $request->option_1,
            'option_2'          => $request->option_2,
            'option_3'          => $request->option_3,
            'option_4'          => $request->option_4,
            'option_5'          => $request->option_5,
            'answer'            => $request->answer,
        ];

        //handle image uploads and delete old images if new ones are uploaded
        if ($request->hasFile('question_image')) {
            //delete old image
            if ($question->question_image) {
                \Storage::disk('public')->delete($question->question_image);
            }
            $data['question_image'] = $request->file('question_image')->store('questions', 'public');
        }
        
        if ($request->hasFile('option_1_image')) {
            if ($question->option_1_image) {
                \Storage::disk('public')->delete($question->option_1_image);
            }
            $data['option_1_image'] = $request->file('option_1_image')->store('questions', 'public');
        }
        
        if ($request->hasFile('option_2_image')) {
            if ($question->option_2_image) {
                \Storage::disk('public')->delete($question->option_2_image);
            }
            $data['option_2_image'] = $request->file('option_2_image')->store('questions', 'public');
        }
        
        if ($request->hasFile('option_3_image')) {
            if ($question->option_3_image) {
                \Storage::disk('public')->delete($question->option_3_image);
            }
            $data['option_3_image'] = $request->file('option_3_image')->store('questions', 'public');
        }
        
        if ($request->hasFile('option_4_image')) {
            if ($question->option_4_image) {
                \Storage::disk('public')->delete($question->option_4_image);
            }
            $data['option_4_image'] = $request->file('option_4_image')->store('questions', 'public');
        }
        
        if ($request->hasFile('option_5_image')) {
            if ($question->option_5_image) {
                \Storage::disk('public')->delete($question->option_5_image);
            }
            $data['option_5_image'] = $request->file('option_5_image')->store('questions', 'public');
        }

        //update question
        $question->update($data);

        //redirect
        return redirect()->route('admin.exams.show', $exam->id);
    }

    /**
     * destroyQuestion
     *
     * @param  mixed $exam
     * @param  mixed $question
     * @return void
     */
    public function destroyQuestion(Exam $exam, Question $question)
    {
        //delete question
        $question->delete();

        //redirect
        return redirect()->route('admin.exams.show', $exam->id);
    }

    /**
     * import
     *
     * @return void
     */
    public function import(Exam $exam)
    {
        return inertia('Admin/Questions/Import', [
            'exam' => $exam
        ]);
    }

    /**
     * storeImport
     *
     * @param  mixed $request
     * @return void
     */
    public function storeImport(Request $request, Exam $exam)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        // import data
        Excel::import(new QuestionsImport(), $request->file('file'));

        //redirect
        return redirect()->route('admin.exams.show', $exam->id);
    }
}
