<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserImportBatch extends Model
{
    use HasFactory;

    protected $casts = ['error_log' => 'json'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
