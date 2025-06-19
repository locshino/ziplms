<?php

namespace App\Models;

use App\Enums\AttachmentType;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $lecture_id
 * @property array<array-key, mixed> $name
 * @property array<array-key, mixed>|null $description
 * @property string|null $uploaded_by
 * @property array<array-key, mixed>|null $video_links
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Lecture $lecture
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read mixed $translations
 * @property-read \App\Models\User|null $uploader
 *
 * @method static \Database\Factories\LectureMaterialFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereLectureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereUploadedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial whereVideoLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LectureMaterial withoutTrashed()
 *
 * @mixin \Eloquent
 */
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
