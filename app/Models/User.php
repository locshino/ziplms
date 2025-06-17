<?php

namespace App\Models;

use App\States\Status;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;

class User extends Base\AuthModel implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasStates,
        InteractsWithMedia; // For profile_picture

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'status' => Status::class,
        ]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_picture')->singleFile();
    }
}
