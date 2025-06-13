<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = ['name' => 'json', 'address' => 'json', 'settings' => 'json'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function classesMajors()
    {
        return $this->hasMany(ClassesMajor::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function badges()
    {
        return $this->hasMany(Badge::class);
    }

    public function userImportBatches()
    {
        return $this->hasMany(UserImportBatch::class);
    }
}
