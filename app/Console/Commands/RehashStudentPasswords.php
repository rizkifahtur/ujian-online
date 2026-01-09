<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RehashStudentPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:rehash-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rehash plain text student passwords to bcrypt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting password rehashing...');
        
        // Get all students
        $students = Student::all();
        $count = 0;
        
        foreach ($students as $student) {
            // Check if password is already hashed (bcrypt hashes start with $2y$)
            if (!str_starts_with($student->getAttributes()['password'], '$2y$')) {
                // Get the plain password from the database directly
                $plainPassword = DB::table('students')
                    ->where('id', $student->id)
                    ->value('password');
                
                // Hash and update using raw query to bypass model casting
                DB::table('students')
                    ->where('id', $student->id)
                    ->update(['password' => Hash::make($plainPassword)]);
                
                $count++;
                $this->info("Rehashed password for student: {$student->name} (NISN: {$student->nisn})");
            }
        }
        
        $this->info("Successfully rehashed {$count} student passwords.");
        
        return Command::SUCCESS;
    }
}
