<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamViolation extends Model
{
    protected $fillable = [
        'exam_id',
        'exam_session_id',
        'student_id',
        'exam_group_id',
        'violation_type',
        'violation_count',
        'description',
        'violated_at',
        'status',
        'forgiven_by',
        'forgiven_at',
        'forgiven_reason',
    ];

    protected $casts = [
        'violated_at' => 'datetime',
        'forgiven_at' => 'datetime',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function examGroup(): BelongsTo
    {
        return $this->belongsTo(ExamGroup::class);
    }

    public function forgivenByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'forgiven_by');
    }
}
