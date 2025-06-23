<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface extends Base\RepositoryInterface
{
    public function findByName(string $name);
}
