<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Badge extends Model
{
    use HasFactory;

    protected $casts = ['name' => 'json', 'description' => 'json', 'criteria_description' => 'json'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges');
    }
}
