<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\Repositories\RepositoryException;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Media;
use App\Models\User;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Media repository implementation.
 *
 * Handles media data access operations with proper exception handling.
 */
class MediaRepository extends EloquentRepository implements MediaRepositoryInterface
{
    /**
     * Get the model class name.
     */
    protected function model(): string
    {
        return Media::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaByModel(string $modelType, string $modelId, ?string $collection = null): Collection
    {
        try {
            $query = $this->model->where('model_type', $modelType)
                ->where('model_id', $modelId);

            if ($collection) {
                $query->where('collection_name', $collection);
            }

            return $query->get();
        } catch (\Exception $e) {
            throw new RepositoryException('Failed to get media by model: '.$e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function canUserAccessMedia(Media $media, User $user): bool
    {
        try {
            $model = $media->model;

            // For Course documents
            if ($model instanceof Course) {
                // Check if user is enrolled in the course
                return $model->enrollments()->where('student_id', $user->id)->exists();
            }

            // For Assignment documents
            if ($model instanceof Assignment) {
                $course = $model->course;

                // Check if user is enrolled in the course that contains the assignment
                return $course->enrollments()->where('student_id', $user->id)->exists();
            }

            return false;
        } catch (\Exception $e) {
            throw new RepositoryException('Failed to check user media access: '.$e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaByCollection(string $collection): Collection
    {
        try {
            return $this->model->where('collection_name', $collection)->get();
        } catch (\Exception $e) {
            throw new RepositoryException('Failed to get media by collection: '.$e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function mediaFileExists(Media $media): bool
    {
        try {
            $filePath = $media->getPath();

            return file_exists($filePath);
        } catch (\Exception $e) {
            throw new RepositoryException('Failed to check media file existence: '.$e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaWithModel(string $mediaId): ?Media
    {
        try {
            return $this->model->with('model')->find($mediaId);
        } catch (\Exception $e) {
            throw new RepositoryException('Failed to get media with model: '.$e->getMessage());
        }
    }
}
