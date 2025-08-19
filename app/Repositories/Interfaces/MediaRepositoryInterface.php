<?php

namespace App\Repositories\Interfaces;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Media repository interface.
 *
 * Defines contracts for media data access operations.
 */
interface MediaRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get media by model type and model ID.
     *
     * @param string $modelType
     * @param string $modelId
     * @param string|null $collection
     * @return Collection
     */
    public function getMediaByModel(string $modelType, string $modelId, ?string $collection = null): Collection;

    /**
     * Check if user can access media based on enrollment and permissions.
     *
     * @param Media $media
     * @param User $user
     * @return bool
     */
    public function canUserAccessMedia(Media $media, User $user): bool;

    /**
     * Get media by collection name.
     *
     * @param string $collection
     * @return Collection
     */
    public function getMediaByCollection(string $collection): Collection;

    /**
     * Check if media file exists on disk.
     *
     * @param Media $media
     * @return bool
     */
    public function mediaFileExists(Media $media): bool;

    /**
     * Get media with related model information.
     *
     * @param string $mediaId
     * @return Media|null
     */
    public function getMediaWithModel(string $mediaId): ?Media;
}