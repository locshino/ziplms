<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Base\Repository implements Contracts\UserRepositoryInterface
{
    protected function model(): string
    {
        return User::class;
    }

    public function findByName(string $name)
    {
        return $this->query()->where('name', $name)->first();
    }
}
