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
     */
    public function getMediaByModel(string $modelType, string $modelId, ?string $collection = null): Collection;

    /**
     * Check if user can access media based on enrollment and permissions.
     */
    public function canUserAccessMedia(Media $media, User $user): bool;

    /**
     * Get media by collection name.
     */
    public function getMediaByCollection(string $collection): Collection;

    /**
     * Check if media file exists on disk.
     */
    public function mediaFileExists(Media $media): bool;

    /**
     * Get media with related model information.
     */
    public function getMediaWithModel(string $mediaId): ?Media;
}
