<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Tags\Tag as SpatieTagModel;

class Tag extends SpatieTagModel
{
    use HasUuids;
}
