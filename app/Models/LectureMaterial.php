<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $lecture_id
 * @property array $name
 * @property array|null $description
 * @property string|null $uploaded_by
 * @property array|null $video_links
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Lecture $lecture
 * @property-read \App\Models\User|null $uploader
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read mixed $translations
 *
 * @method static \Database\Factories\LectureMaterialFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|static newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|static newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|static onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static \Illuminate\Database\Eloquent\Builder|static withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|static withoutTrashed()
 */
class LectureMaterial extends Model implements HasMedia
{
    use HasFactory,
        HasTranslations,
        HasUuids,
        InteractsWithMedia, SoftDeletes;

    /**
     * Các thuộc tính nên được ép kiểu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'json',
        'description' => 'json',
        'video_links' => 'json',
    ];

    /**
     * Các thuộc tính có thể dịch.
     *
     * @var array<int, string>
     */
    public array $translatable = [
        'name',
        'description',
    ];

    /**
     * Các thuộc tính có thể được gán hàng loạt.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lecture_id',
        'name',
        'description',
        'uploaded_by',
        'video_links', // Đã thêm
    ];

    /**
     * Đăng ký các media collection.
     */
    public function registerMediaCollections(): void
    {
        // Đặt tên collection tường minh là 'attachments' để khớp với Filament Resource
        $this->addMediaCollection('attachments')
            ->useDisk('public');
    }

    /**
     * Lấy bài giảng mà tài liệu này thuộc về.
     */
    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    /**
     * Lấy người dùng đã tải lên tài liệu này.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
