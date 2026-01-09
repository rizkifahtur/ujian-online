<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $exam1 = Exam::where('title', 'Ujian Matematika Semester 1')->first();
        $exam2 = Exam::where('title', 'Ujian Matematika Semester 2')->first();

        if ($exam1) {
            Question::create([
                'exam_id' => $exam1->id,
                'question' => 'Berapakah hasil dari 2 + 2?',
                'option_1' => '3',
                'option_2' => '4',
                'option_3' => '5',
                'option_4' => '6',
                'option_5' => '7',
                'answer' => 2,
            ]);

            Question::create([
                'exam_id' => $exam1->id,
                'question' => 'Berapakah akar kuadrat dari 16?',
                'option_1' => '2',
                'option_2' => '3',
                'option_3' => '4',
                'option_4' => '5',
                'option_5' => '6',
                'answer' => 3,
            ]);

            Question::create([
                'exam_id' => $exam1->id,
                'question' => 'Berapakah 10 x 5?',
                'option_1' => '40',
                'option_2' => '45',
                'option_3' => '50',
                'option_4' => '55',
                'option_5' => '60',
                'answer' => 3,
            ]);
        }

        if ($exam2) {
            Question::create([
                'exam_id' => $exam2->id,
                'question' => 'Berapakah hasil dari 15 - 7?',
                'option_1' => '6',
                'option_2' => '7',
                'option_3' => '8',
                'option_4' => '9',
                'option_5' => '10',
                'answer' => 3,
            ]);

            Question::create([
                'exam_id' => $exam2->id,
                'question' => 'Berapakah 3 pangkat 2?',
                'option_1' => '6',
                'option_2' => '8',
                'option_3' => '9',
                'option_4' => '12',
                'option_5' => '15',
                'answer' => 3,
            ]);
        }
    }
}
