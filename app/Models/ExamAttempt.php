<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $casts = ['started_at' => 'datetime', 'completed_at' => 'datetime', 'feedback' => 'json'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
