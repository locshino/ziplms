<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\Repositories\RepositoryException;
use App\Repositories\Interfaces\EloquentRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Base Eloquent repository implementation.
 *
 * Provides common database operations with proper exception handling.
 *
 * @throws RepositoryException When repository operations fail
 * @throws Exception When model class issues occur
 */
abstract class EloquentRepository implements EloquentRepositoryInterface
{
    /**
     * The repository model
     *
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Get the model class name.
     *
     * @return string
     */
    abstract protected function model(): string;

    /**
     * Set the Eloquent model instance for the repository.
     *
     * @return void
     * @throws Exception
     */
    public function setModel(): void
    {
        $modelClass = $this->model();

        if (! class_exists($modelClass)) {
            throw new Exception("Model class {$modelClass} does not exist.");
        }

        $modelInstance = app()->make($modelClass);

        if (! $modelInstance instanceof Model) {
            throw new Exception("Class {$modelClass} must be an instance of Illuminate\\Database\\Eloquent\\Model.");
        }

        $this->model = $modelInstance;
    }

    /**
     * Get all records.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        try {
            return $this->model->with($relations)->get($columns);
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Find record by ID.
     *
     * @param mixed $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model|null
     * @throws RepositoryException When database error occurs
     */
    public function findById(
        mixed $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model {
        try {
            return $this->model->select($columns)->with($relations)->find($modelId)?->append($appends);
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Create new record.
     *
     * @param array $payload
     * @return Model
     * @throws RepositoryException When creation fails or validation errors occur
     */
    public function create(array $payload): Model
    {
        try {
            return $this->model->create($payload);
        } catch (Exception $e) {
            throw RepositoryException::createFailed($e->getMessage());
        }
    }

    /**
     * Update record by ID.
     *
     * @param mixed $modelId
     * @param array $payload
     * @return bool
     * @throws RepositoryException When update fails or record not found
     */
    public function updateById(mixed $modelId, array $payload): bool
    {
        try {
            $model = $this->findById($modelId);

            if (! $model) {
                throw RepositoryException::notFound();
            }

            return $model->update($payload);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw RepositoryException::updateFailed($e->getMessage());
        }
    }

    /**
     * Delete record by ID.
     *
     * @param mixed $modelId
     * @return bool
     * @throws RepositoryException When deletion fails or record not found
     */
    public function deleteById(mixed $modelId): bool
    {
        try {
            $model = $this->findById($modelId);

            if (! $model) {
                throw RepositoryException::notFound();
            }

            return $model->delete();
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw RepositoryException::deleteFailed($e->getMessage());
        }
    }
}
