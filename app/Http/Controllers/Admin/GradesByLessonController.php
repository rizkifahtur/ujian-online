<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lesson;
use App\Models\Grade;
use App\Models\Exam;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GradesExport;

class GradesByLessonController extends Controller
{
    /**
     * Display a listing of grades grouped by lessons.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all lessons
        $lessons = Lesson::orderBy('title', 'ASC')->get();

        return inertia('Admin/GradesByLesson/Index', [
            'lessons' => $lessons,
        ]);
    }

    /**
     * Display grades for specific lesson.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lessonId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $lessonId)
    {
        // Get lesson
        $lesson = Lesson::findOrFail($lessonId);

        // Get classroom_id from request
        $classroomId = $request->input('classroom_id');

        // Get all classrooms that have exams for this lesson
        $classrooms = Classroom::whereHas('exams', function ($query) use ($lessonId) {
            $query->where('lesson_id', $lessonId);
        })->orderBy('title', 'ASC')->get();

        // Build exams query
        $examsQuery = Exam::with(['lesson', 'classroom'])
            ->where('lesson_id', $lessonId);
        
        if ($classroomId) {
            $examsQuery->where('classroom_id', $classroomId);
        }
        
        $exams = $examsQuery->get();

        // Build grades query
        $gradesQuery = Grade::with(['student', 'exam.classroom', 'exam.lesson', 'exam_session'])
            ->whereHas('exam', function ($query) use ($lessonId, $classroomId) {
                $query->where('lesson_id', $lessonId);
                if ($classroomId) {
                    $query->where('classroom_id', $classroomId);
                }
            })
            ->orderBy('exam_id', 'ASC')
            ->orderBy('grade', 'DESC');

        $grades = $gradesQuery->get();

        // Group grades by exam
        $gradesByExam = [];
        foreach ($exams as $exam) {
            $examGrades = $grades->where('exam_id', $exam->id);
            
            if ($examGrades->count() > 0) {
                $gradesByExam[] = [
                    'exam' => $exam,
                    'grades' => $examGrades->values(),
                    'average' => round($examGrades->avg('grade'), 2),
                    'highest' => $examGrades->max('grade'),
                    'lowest' => $examGrades->min('grade'),
                    'total_students' => $examGrades->count()
                ];
            }
        }

        return inertia('Admin/GradesByLesson/Show', [
            'lesson' => $lesson,
            'gradesByExam' => $gradesByExam,
            'totalStudents' => $grades->unique('student_id')->count(),
            'totalExams' => count($gradesByExam),
            'classrooms' => $classrooms,
            'selectedClassroomId' => $classroomId,
        ]);
    }

    /**
     * Export grades by lesson to Excel
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lessonId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $classroomId = $request->input('classroom_id');

        // Get all grades for this lesson
        $gradesQuery = Grade::with(['student', 'exam.classroom', 'exam.lesson', 'exam_session'])
            ->whereHas('exam', function ($query) use ($lessonId, $classroomId) {
                $query->where('lesson_id', $lessonId);
                if ($classroomId) {
                    $query->where('classroom_id', $classroomId);
                }
            })
            ->orderBy('student_id', 'ASC')
            ->orderBy('exam_id', 'ASC');

        $grades = $gradesQuery->get();

        // Format data for export
        $exportData = [];
        $currentStudent = null;
        $studentRow = [];

        foreach ($grades as $grade) {
            if ($currentStudent !== $grade->student_id) {
                if ($currentStudent !== null && !empty($studentRow)) {
                    $exportData[] = $studentRow;
                }
                
                $studentRow = [
                    'NISN' => $grade->student->nisn,
                    'Nama Siswa' => $grade->student->name,
                    'Kelas' => $grade->exam->classroom->title,
                ];
                $currentStudent = $grade->student_id;
            }
            
            // Add exam grade as column
            $studentRow[$grade->exam->title] = $grade->grade;
        }

        // Add last student
        if (!empty($studentRow)) {
            $exportData[] = $studentRow;
        }

        // Export to Excel
        return Excel::download(
            new class($exportData, $lesson->title) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                protected $data;
                protected $lessonTitle;

                public function __construct($data, $lessonTitle)
                {
                    $this->data = collect($data);
                    $this->lessonTitle = $lessonTitle;
                }

                public function collection()
                {
                    return $this->data;
                }

                public function headings(): array
                {
                    if ($this->data->isEmpty()) {
                        return ['NISN', 'Nama Siswa', 'Kelas'];
                    }
                    return array_keys($this->data->first());
                }
            },
            'Nilai_' . str_replace(' ', '_', $lesson->title) . 
            ($classroomId ? '_Kelas_' . str_replace(' ', '_', Classroom::find($classroomId)->title) : '') . 
            '_' . date('Y-m-d') . '.xlsx'
        );
    }
}
