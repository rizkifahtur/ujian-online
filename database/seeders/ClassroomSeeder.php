<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = [
            ['title' => 'X IPA 1'],
            ['title' => 'X IPA 2'],
            ['title' => 'X IPS 1'],
            ['title' => 'X IPS 2'],
            ['title' => 'XI IPA 1'],
            ['title' => 'XI IPA 2'],
            ['title' => 'XI IPS 1'],
            ['title' => 'XI IPS 2'],
            ['title' => 'XII IPA 1'],
            ['title' => 'XII IPA 2'],
            ['title' => 'XII IPS 1'],
            ['title' => 'XII IPS 2'],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::create($classroom);
        }
    }
}
