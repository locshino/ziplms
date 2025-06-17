<?php

namespace App\Models;

use Spatie\Tags\HasTags;

class UserClassMajorEnrollment extends Base\Model
{
    use HasTags;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $fillable = [
        'user_id',
        'class_major_id',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classMajor()
    {
        return $this->belongsTo(ClassesMajor::class, 'class_major_id');
    }
}
