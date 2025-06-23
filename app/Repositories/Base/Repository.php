<?php

namespace App\Repositories\Base;

use App\Repositories\Contracts\Base\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repository
 */
abstract class Repository implements RepositoryInterface
{
    /**
     * The model instance.
     */
    protected Model $model;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->model = $this->getModelInstance();
    }

    /**
     * Get the model class name.
     */
    abstract protected function model(): string;

    /**
     * Get a new instance of the model.
     */
    protected function getModelInstance(): Model
    {
        return app($this->model());
    }

    /**
     * Get a new query builder for the model.
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Get all records.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->get($columns);
    }

    /**
     * Paginate the given query.
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage, $columns);
    }

    /**
     * Find a record by its primary key.
     *
     * @param  mixed  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->query()->find($id, $columns);
    }

    /**
     * Find a record by its primary key, including soft-deleted records.
     *
     * @param  mixed  $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null
     */
    public function findWithTrashed($id)
    {
        return $this->query()->withTrashed()->find($id);
    }

    /**
     * Create a new record.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a record by its primary key.
     *
     * @param  mixed  $id
     */
    public function update($id, array $data): ?Model
    {
        $record = $this->find($id);

        return $record?->update($data) ? $record : null;
    }

    /**
     * Delete a record by its primary key.
     *
     * @param  mixed  $id
     */
    public function delete($id): bool
    {
        return (bool) $this->find($id)?->delete();
    }

    /**
     * Permanently delete a record by its primary key.
     *
     * @param  mixed  $id
     */
    public function forceDelete($id): bool
    {
        $model = $this->findWithTrashed($id);

        return $model && method_exists($model, 'forceDelete') ? $model->forceDelete() : false;
    }

    /**
     * Restore a soft-deleted record by its primary key.
     *
     * @param  mixed  $id
     */
    public function restore($id): bool
    {
        $model = $this->findWithTrashed($id);

        return $model && method_exists($model, 'restore') ? $model->restore() : false;
    }

    /**
     * Get records where the model has a specific role.
     */
    public function whereRole(string|\BackedEnum $role): Collection
    {
        $roleValue = $role instanceof \BackedEnum ? $role->value : $role;

        return $this->query()->role($roleValue)->get();
    }

    /**
     * Get records where the model has a specific permission.
     */
    public function wherePermission(string|\BackedEnum $permission): Collection
    {
        $permValue = $permission instanceof \BackedEnum ? $permission->value : $permission;

        return $this->query()->permission($permValue)->get();
    }
}
