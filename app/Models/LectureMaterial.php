<?php

namespace App\Models;

use App\Enums\AttachmentType;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class LectureMaterial extends Base\Model implements HasMedia
{
    use HasTranslations,
        InteractsWithMedia;

    protected $casts = [
        'name' => 'json',
        'description' => 'json',
        'video_links' => 'json',
    ];

    public array $translatable = [
        'name',
        'description',
    ];

    protected $fillable = [
        'lecture_id',
        'name',
        'description',
        'uploaded_by',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(AttachmentType::key())
            ->useDisk('public')
            ->acceptsMimeTypes(AttachmentType::values())
            ->withResponsiveImages();
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
