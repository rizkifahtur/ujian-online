<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    //
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'title',
    ];

    /**
     * exams
     *
     * @return void
     */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
