<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot as EloquentPivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

abstract class Pivot extends EloquentPivot
{
    use HasFactory,
        HasUuids,
        SoftDeletes;

    public function getTable()
    {
        return Str::snake(Str::pluralStudly(class_basename($this)));
    }
}
