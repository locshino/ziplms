<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBadge extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
