<?php

namespace App\Services;

use App\Exceptions\Repositories\RepositoryException;
use App\Exceptions\Services\ServiceException;
use App\Repositories\Interfaces\EloquentRepositoryInterface;
use App\Services\Interfaces\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Base service implementation.
 * 
 * Provides common business logic operations with proper exception handling.
 * 
 * @throws ServiceException When service operations fail
 * @throws RepositoryException When repository operations fail
 */
abstract class BaseService implements BaseServiceInterface
{
    /**
     * The repository instance.
     *
     * @var EloquentRepositoryInterface
     */
    protected $repository;

    /**
     * BaseService constructor.
     *
     * @param EloquentRepositoryInterface $repository
     */
    public function __construct(EloquentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     * 
     * @throws RepositoryException When database error occurs
     */
    public function all(): Collection
    {
        return $this->repository->all();
    }

    /**
     * {@inheritDoc}
     * 
     * @throws RepositoryException When database error occurs
     */
    public function findById(mixed $id): ?Model
    {
        return $this->repository->findById($id);
    }

    /**
     * {@inheritDoc}
     * 
     * @throws ServiceException When validation fails or business logic errors occur
     * @throws RepositoryException When creation fails or database error occurs
     */
    public function create(array $payload): Model
    {
        try {
            return DB::transaction(function () use ($payload) {
                return $this->repository->create($payload);
            });
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to create record: ' . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     * 
     * @throws ServiceException When validation fails or business logic errors occur
     * @throws RepositoryException When update fails or record not found
     */
    public function updateById(mixed $id, array $payload): bool
    {
        try {
            return DB::transaction(function () use ($id, $payload) {
                return $this->repository->updateById($id, $payload);
            });
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to update record: ' . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     * 
     * @throws ServiceException When business logic prevents deletion
     * @throws RepositoryException When deletion fails or record not found
     */
    public function deleteById(mixed $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                return $this->repository->deleteById($id);
            });
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to delete record: ' . $e->getMessage());
        }
    }

    /**
     * Begin a database transaction.
     *
     * @return void
     */
    protected function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * Commit the active database transaction.
     *
     * @return void
     */
    protected function commit(): void
    {
        DB::commit();
    }

    /**
     * Rollback the active database transaction.
     *
     * @return void
     */
    protected function rollback(): void
    {
        DB::rollback();
    }
}
