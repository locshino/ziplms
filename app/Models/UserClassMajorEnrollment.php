<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserClassMajorEnrollment extends Model
{
    use HasFactory;

    protected $table = 'user_class_major_enrollments';

    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classMajor()
    {
        return $this->belongsTo(ClassesMajor::class, 'class_major_id');
    }
}
