<?php

namespace App\Services\Interfaces;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Media service interface.
 *
 * Defines contracts for media business operations.
 */
interface MediaServiceInterface extends BaseServiceInterface
{
    /**
     * Check if user can download media based on role and permissions.
     *
     * @param Media $media
     * @param User $user
     * @return bool
     */
    public function canUserDownloadMedia(Media $media, User $user): bool;

    /**
     * Download media file with permission checks.
     *
     * @param Media $media
     * @param User $user
     * @return BinaryFileResponse
     * @throws \App\Exceptions\Services\ServiceException
     */
    public function downloadMedia(Media $media, User $user): BinaryFileResponse;

    /**
     * Get media by model with permission filtering.
     *
     * @param string $modelType
     * @param string $modelId
     * @param User $user
     * @param string|null $collection
     * @return Collection
     */
    public function getAccessibleMediaByModel(string $modelType, string $modelId, User $user, ?string $collection = null): Collection;

    /**
     * Check if user can view media.
     *
     * @param Media $media
     * @param User $user
     * @return bool
     */
    public function canUserViewMedia(Media $media, User $user): bool;

    /**
     * Check if user can stream media.
     *
     * @param Media $media
     * @param User $user
     * @return bool
     */
    public function canUserStreamMedia(Media $media, User $user): bool;

    /**
     * Validate media file existence.
     *
     * @param Media $media
     * @return bool
     */
    public function validateMediaFileExists(Media $media): bool;
}