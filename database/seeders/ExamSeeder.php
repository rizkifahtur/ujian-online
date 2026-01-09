<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lesson = Lesson::firstOrCreate([
            'title' => 'Matematika',
        ]);

        $classroom = Classroom::firstOrCreate([
            'title' => 'X IPA 1',
        ]);

        Exam::create([
            'title' => 'Ujian Matematika Semester 1',
            'lesson_id' => $lesson->id,
            'classroom_id' => $classroom->id,
            'duration' => 60,
            'description' => 'Ujian akhir semester 1 untuk mata pelajaran Matematika.',
            'random_question' => 'Y',
            'random_answer' => 'Y',
            'show_answer' => 'N',
        ]);

        Exam::create([
            'title' => 'Ujian Matematika Semester 2',
            'lesson_id' => $lesson->id,
            'classroom_id' => $classroom->id,
            'duration' => 120,
            'description' => 'Ujian akhir semester 2 untuk mata pelajaran Matematika.',
            'random_question' => 'N',
            'random_answer' => 'N',
            'show_answer' => 'Y',
        ]);
    }
}
