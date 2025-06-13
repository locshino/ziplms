<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LectureMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = ['name' => 'json', 'description' => 'json'];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
