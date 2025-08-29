<?php

namespace App\Services;

use App\Exceptions\Services\MediaServiceException;
use App\Libs\Permissions\PermissionHelper;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Media;
use App\Models\User;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use App\Services\Interfaces\MediaServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Media service implementation.
 *
 * Handles media business logic including permission checks,
 * file access validation, and download operations.
 */
class MediaService extends BaseService implements MediaServiceInterface
{
    /**
     * The media repository instance.
     */
    protected MediaRepositoryInterface $mediaRepository;

    /**
     * MediaService constructor.
     */
    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        parent::__construct($mediaRepository);
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function canUserDownloadMedia(Media $media, User $user): bool
    {
        try {
            $model = $media->model;

            // Check if user has general download permission for all media
            if ($user->can(PermissionHelper::make()->download()->media()->all()->build())) {
                return true;
            }

            // For Course documents
            if ($model instanceof Course) {
                // Check if user has course manager permission for this specific course
                if ($user->can(PermissionHelper::make()->manage()->course()->id($model->id)->build())) {
                    return true;
                }
                // Check if user has download permission for assigned media (teachers)
                if ($user->can(PermissionHelper::make()->download()->media()->assigned()->build()) && $model->teacher_id === $user->id) {
                    return true;
                }
                // Check if user has download permission for enrolled media (students)
                if ($user->can(PermissionHelper::make()->download()->media()->enrolled()->build())) {
                    return $this->mediaRepository->canUserAccessMedia($media, $user);
                }
            }
            // For Assignment documents
            elseif ($model instanceof Assignment) {
                $course = $model->course;
                // Check if user has download permission for assigned media (teachers)
                if ($user->can(PermissionHelper::make()->download()->media()->assigned()->build()) && $course->teacher_id === $user->id) {
                    return true;
                }
                // Check if user has download permission for enrolled media (students)
                if ($user->can(PermissionHelper::make()->download()->media()->enrolled()->build())) {
                    return $this->mediaRepository->canUserAccessMedia($media, $user);
                }
            }

            return false;
        } catch (\Exception $e) {
            throw new MediaServiceException('Failed to check download permission: '.$e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function downloadMedia(Media $media, User $user): BinaryFileResponse
    {
        // Check download permission
        if (! $this->canUserDownloadMedia($media, $user)) {
            throw MediaServiceException::accessDenied('download');
        }

        // Validate file exists
        if (! $this->validateMediaFileExists($media)) {
            throw MediaServiceException::fileNotFound($media->file_name);
        }

        try {
            $filePath = $media->getPath();

            return response()->download($filePath, $media->file_name);
        } catch (\Exception $e) {
            throw MediaServiceException::downloadFailed($e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getAccessibleMediaByModel(string $modelType, string $modelId, User $user, ?string $collection = null): Collection
    {
        try {
            $allMedia = $this->mediaRepository->getMediaByModel($modelType, $modelId, $collection);

            // Filter media based on user permissions
            return $allMedia->filter(function (Media $media) use ($user) {
                return $this->canUserViewMedia($media, $user);
            });
        } catch (\Exception $e) {
            throw new MediaServiceException('Failed to get accessible media: '.$e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function canUserViewMedia(Media $media, User $user): bool
    {
        try {
            $model = $media->model;

            // Check if user has general view permission for all media
            if ($user->can(PermissionHelper::make()->view()->media()->all()->build())) {
                return true;
            }

            // For Course documents
            if ($model instanceof Course) {
                // Check if user has course manager permission for this specific course
                if ($user->can(PermissionHelper::make()->manage()->course()->id($model->id)->build())) {
                    return true;
                }
                // Check if user has view permission for assigned media (teachers)
                if ($user->can(PermissionHelper::make()->view()->media()->assigned()->build()) && $model->teacher_id === $user->id) {
                    return true;
                }
                // Check if user has view permission for enrolled media (students)
                if ($user->can(PermissionHelper::make()->view()->media()->enrolled()->build())) {
                    return $this->mediaRepository->canUserAccessMedia($media, $user);
                }
            }
            // For Assignment documents
            elseif ($model instanceof Assignment) {
                $course = $model->course;
                // Check if user has view permission for assigned media (teachers)
                if ($user->can(PermissionHelper::make()->view()->media()->assigned()->build()) && $course->teacher_id === $user->id) {
                    return true;
                }
                // Check if user has view permission for enrolled media (students)
                if ($user->can(PermissionHelper::make()->view()->media()->enrolled()->build())) {
                    return $this->mediaRepository->canUserAccessMedia($media, $user);
                }
            }

            return false;
        } catch (\Exception $e) {
            throw new MediaServiceException('Failed to check view permission: '.$e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function canUserStreamMedia(Media $media, User $user): bool
    {
        try {
            $model = $media->model;

            // Check if user has general stream permission for all media
            if ($user->can(PermissionHelper::make()->stream()->media()->all()->build())) {
                return true;
            }

            // For Course documents
            if ($model instanceof Course) {
                // Check if user has course manager permission for this specific course
                if ($user->can(PermissionHelper::make()->manage()->course()->id($model->id)->build())) {
                    return true;
                }
                // Check if user has stream permission for assigned media (teachers)
                if ($user->can(PermissionHelper::make()->stream()->media()->assigned()->build()) && $model->teacher_id === $user->id) {
                    return true;
                }
                // Check if user has stream permission for enrolled media (students)
                if ($user->can(PermissionHelper::make()->stream()->media()->enrolled()->build())) {
                    return $this->mediaRepository->canUserAccessMedia($media, $user);
                }
            }
            // For Assignment documents
            elseif ($model instanceof Assignment) {
                $course = $model->course;
                // Check if user has stream permission for assigned media (teachers)
                if ($user->can(PermissionHelper::make()->stream()->media()->assigned()->build()) && $course->teacher_id === $user->id) {
                    return true;
                }
                // Check if user has stream permission for enrolled media (students)
                if ($user->can(PermissionHelper::make()->stream()->media()->enrolled()->build())) {
                    return $this->mediaRepository->canUserAccessMedia($media, $user);
                }
            }

            return false;
        } catch (\Exception $e) {
            throw new MediaServiceException('Failed to check stream permission: '.$e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function validateMediaFileExists(Media $media): bool
    {
        try {
            return $this->mediaRepository->mediaFileExists($media);
        } catch (\Exception $e) {
            throw new MediaServiceException('Failed to validate media file existence: '.$e->getMessage());
        }
    }
}
