<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamSession;
use Illuminate\Database\Seeder;

class ExamSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exam1 = Exam::where('title', 'Ujian Matematika Semester 1')->first();
        $exam2 = Exam::where('title', 'Ujian Matematika Semester 2')->first();

        if ($exam1) {
            ExamSession::create([
                'exam_id' => $exam1->id,
                'title' => 'Sesi Ujian Matematika Semester 1 - Hari 1',
                'start_time' => now()->addDays(1)->setTime(8, 0),
                'end_time' => now()->addDays(1)->setTime(10, 0),
            ]);
        }

        if ($exam2) {
            ExamSession::create([
                'exam_id' => $exam2->id,
                'title' => 'Sesi Ujian Matematika Semester 2 - Hari 2',
                'start_time' => now()->addDays(2)->setTime(14, 0),
                'end_time' => now()->addDays(2)->setTime(16, 0),
            ]);
        }
    }
}
