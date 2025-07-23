<?php

// app/Repositories/Contracts/UserClassMajorEnrollmentRepositoryInterface.php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\Base\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

interface UserClassMajorEnrollmentRepositoryInterface extends RepositoryInterface
{
    public function getClassMajorFilterOptions(): array;

    public function applyClassMajorFilter(Builder $query, $classMajorId): Builder;
}
