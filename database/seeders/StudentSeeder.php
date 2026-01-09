<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            // X IPA 1 (classroom_id: 1)
            ['classroom_id' => 1, 'nisn' => '1234567', 'name' => 'Ahmad Fauzi', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 1, 'nisn' => '1234568', 'name' => 'Budi Santoso', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 1, 'nisn' => '1234569', 'name' => 'Citra Dewi', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 1, 'nisn' => '1234570', 'name' => 'Dian Putri', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 1, 'nisn' => '1234571', 'name' => 'Eko Prasetyo', 'password' => Hash::make('password'), 'gender' => 'L'],

            // X IPA 2 (classroom_id: 2)
            ['classroom_id' => 2, 'nisn' => '1234572', 'name' => 'Fitri Handayani', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 2, 'nisn' => '1234573', 'name' => 'Gilang Ramadhan', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 2, 'nisn' => '1234574', 'name' => 'Hani Maharani', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 2, 'nisn' => '1234575', 'name' => 'Irfan Hakim', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 2, 'nisn' => '1234576', 'name' => 'Joko Widodo', 'password' => Hash::make('password'), 'gender' => 'L'],

            // X IPS 1 (classroom_id: 3)
            ['classroom_id' => 3, 'nisn' => '1234577', 'name' => 'Kartika Sari', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 3, 'nisn' => '1234578', 'name' => 'Linda Wijaya', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 3, 'nisn' => '1234579', 'name' => 'Muhammad Ali', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 3, 'nisn' => '1234580', 'name' => 'Nadia Salsabila', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 3, 'nisn' => '1234581', 'name' => 'Omar Abdullah', 'password' => Hash::make('password'), 'gender' => 'L'],

            // X IPS 2 (classroom_id: 4)
            ['classroom_id' => 4, 'nisn' => '1234582', 'name' => 'Putri Ayu', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 4, 'nisn' => '1234583', 'name' => 'Rahmat Hidayat', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 4, 'nisn' => '1234584', 'name' => 'Siti Nurhaliza', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 4, 'nisn' => '1234585', 'name' => 'Taufik Rahman', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 4, 'nisn' => '1234586', 'name' => 'Umar Bakri', 'password' => Hash::make('password'), 'gender' => 'L'],

            // XI IPA 1 (classroom_id: 5)
            ['classroom_id' => 5, 'nisn' => '1234587', 'name' => 'Vina Mahardika', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 5, 'nisn' => '1234588', 'name' => 'Wawan Setiawan', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 5, 'nisn' => '1234589', 'name' => 'Yuni Astuti', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 5, 'nisn' => '1234590', 'name' => 'Zaki Mubarak', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 5, 'nisn' => '1234591', 'name' => 'Aisyah Rahmawati', 'password' => Hash::make('password'), 'gender' => 'P'],

            // XI IPA 2 (classroom_id: 6)
            ['classroom_id' => 6, 'nisn' => '1234592', 'name' => 'Bambang Suryanto', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 6, 'nisn' => '1234593', 'name' => 'Cahya Purnama', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 6, 'nisn' => '1234594', 'name' => 'Denny Kurniawan', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 6, 'nisn' => '1234595', 'name' => 'Erna Wati', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 6, 'nisn' => '1234596', 'name' => 'Fahri Hamzah', 'password' => Hash::make('password'), 'gender' => 'L'],

            // XI IPS 1 (classroom_id: 7)
            ['classroom_id' => 7, 'nisn' => '1234597', 'name' => 'Gita Savitri', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 7, 'nisn' => '1234598', 'name' => 'Hendra Gunawan', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 7, 'nisn' => '1234599', 'name' => 'Indah Permatasari', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 7, 'nisn' => '1234600', 'name' => 'Jaya Kusuma', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 7, 'nisn' => '1234601', 'name' => 'Kiki Amalia', 'password' => Hash::make('password'), 'gender' => 'P'],

            // XI IPS 2 (classroom_id: 8)
            ['classroom_id' => 8, 'nisn' => '1234602', 'name' => 'Lia Anggraini', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 8, 'nisn' => '1234603', 'name' => 'Maman Surahman', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 8, 'nisn' => '1234604', 'name' => 'Nina Septiani', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 8, 'nisn' => '1234605', 'name' => 'Oki Setiana', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 8, 'nisn' => '1234606', 'name' => 'Pandu Wijaya', 'password' => Hash::make('password'), 'gender' => 'L'],

            // XII IPA 1 (classroom_id: 9)
            ['classroom_id' => 9, 'nisn' => '1234607', 'name' => 'Qori Sandioriva', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 9, 'nisn' => '1234608', 'name' => 'Rudi Hartono', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 9, 'nisn' => '1234609', 'name' => 'Sari Indah', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 9, 'nisn' => '1234610', 'name' => 'Tono Suratno', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 9, 'nisn' => '1234611', 'name' => 'Umi Kalsum', 'password' => Hash::make('password'), 'gender' => 'P'],

            // XII IPA 2 (classroom_id: 10)
            ['classroom_id' => 10, 'nisn' => '1234612', 'name' => 'Vicky Prasetya', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 10, 'nisn' => '1234613', 'name' => 'Wulan Sari', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 10, 'nisn' => '1234614', 'name' => 'Yanto Basuki', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 10, 'nisn' => '1234615', 'name' => 'Zahra Aulia', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 10, 'nisn' => '1234616', 'name' => 'Arief Wibowo', 'password' => Hash::make('password'), 'gender' => 'L'],

            // XII IPS 1 (classroom_id: 11)
            ['classroom_id' => 11, 'nisn' => '1234617', 'name' => 'Bella Safira', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 11, 'nisn' => '1234618', 'name' => 'Cecep Roni', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 11, 'nisn' => '1234619', 'name' => 'Dewi Lestari', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 11, 'nisn' => '1234620', 'name' => 'Eko Yulianto', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 11, 'nisn' => '1234621', 'name' => 'Fina Amelia', 'password' => Hash::make('password'), 'gender' => 'P'],

            // XII IPS 2 (classroom_id: 12)
            ['classroom_id' => 12, 'nisn' => '1234622', 'name' => 'Gandi Saputra', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 12, 'nisn' => '1234623', 'name' => 'Hesti Wulandari', 'password' => Hash::make('password'), 'gender' => 'P'],
            ['classroom_id' => 12, 'nisn' => '1234624', 'name' => 'Iwan Fals', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 12, 'nisn' => '1234625', 'name' => 'Joni Iskandar', 'password' => Hash::make('password'), 'gender' => 'L'],
            ['classroom_id' => 12, 'nisn' => '1234626', 'name' => 'Karina Salim', 'password' => Hash::make('password'), 'gender' => 'P'],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}
