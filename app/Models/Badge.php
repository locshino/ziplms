<?php

namespace App\Models;

class Badge extends Base\Model
{
    protected $casts = [
        'name' => 'json',
        'description' => 'json',
        'criteria_description' => 'json',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges');
    }
}
