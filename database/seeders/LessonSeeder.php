<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = [
            ['title' => 'Matematika'],
            ['title' => 'Bahasa Indonesia'],
            ['title' => 'Bahasa Inggris'],
            ['title' => 'Fisika'],
            ['title' => 'Kimia'],
            ['title' => 'Biologi'],
            ['title' => 'Sejarah'],
            ['title' => 'Geografi'],
            ['title' => 'Ekonomi'],
            ['title' => 'Sosiologi'],
            ['title' => 'Pendidikan Agama'],
            ['title' => 'Pendidikan Kewarganegaraan'],
            ['title' => 'Seni Budaya'],
            ['title' => 'Pendidikan Jasmani'],
            ['title' => 'Teknologi Informasi'],
        ];

        foreach ($lessons as $lesson) {
            Lesson::create($lesson);
        }
    }
}
