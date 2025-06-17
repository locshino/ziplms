<?php

namespace App\Models;

use Spatie\Tags\HasTags;

class Organization extends Base\Model
{
    use HasTags;

    protected $casts = [
        'settings' => 'json',
    ];

    protected $fillable = [
        'name',
        'slug',
        'address',
        'settings',
        'phone_number',
    ];

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

    public function importBatches()
    {
        return $this->hasMany(ImportBatch::class);
    }
}
