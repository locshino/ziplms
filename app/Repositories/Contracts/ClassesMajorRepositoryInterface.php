<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\Base\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

interface ClassesMajorRepositoryInterface extends RepositoryInterface
{
    public function getParentOptions(): array;

    public function applyParentFilter(Builder $query, $parentId): Builder;
}
