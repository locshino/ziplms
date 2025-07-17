<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SpatieMedia extends Media
{
    use HasUuids;

    protected $table = 'spatie_media';
}
