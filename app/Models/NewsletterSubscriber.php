<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $casts = ['extra_attributes' => 'json'];
}
