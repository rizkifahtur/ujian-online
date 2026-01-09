<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserTableSeeder::class,
            ClassroomSeeder::class,
            LessonSeeder::class,
            StudentSeeder::class,
            ExamSeeder::class,
            ExamSessionSeeder::class,
            QuestionSeeder::class,
        ]);
    }
}
