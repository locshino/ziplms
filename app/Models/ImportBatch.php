<?php

namespace App\Models;

class ImportBatch extends Base\Model
{

    protected $casts = [
        'error_log' => 'json',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
