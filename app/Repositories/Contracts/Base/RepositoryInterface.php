<?php

namespace App\Repositories\Contracts\Base;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{
    /**
     * Get a new query builder for the model.
     */
    public function query(): Builder;

    /**
     * Get all records.
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Paginate the given query.
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Find a record by its primary key.
     *
     * @param  mixed  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null
     */
    public function find($id, array $columns = ['*']);

    /**
     * Find a record by its primary key, including soft-deleted records.
     *
     * @param  mixed  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null
     */
    public function findWithTrashed($id);

    /**
     * Create a new record.
     */
    public function create(array $data): Model;

    /**
     * Update a record by its primary key.
     *
     * @param  mixed  $id
     */
    public function update($id, array $data): ?Model;

    /**
     * Delete a record by its primary key.
     *
     * @param  mixed  $id
     */
    public function delete($id): bool;

    /**
     * Permanently delete a record by its primary key.
     *
     * @param  mixed  $id
     */
    public function forceDelete($id): bool;

    /**
     * Restore a soft-deleted record by its primary key.
     *
     * @param  mixed  $id
     */
    public function restore($id): bool;

    /**
     * Get records where the model has a specific role.
     */
    public function whereRole(string|\BackedEnum $role): Collection;

    /**
     * Get records where the model has a specific permission.
     */
    public function wherePermission(string|\BackedEnum $permission): Collection;
}
