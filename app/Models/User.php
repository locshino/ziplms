<?php

namespace App\Models;

use App\States\Status;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\ModelStates\HasStates;

/**
 * The User model represents a user in the system.
 *
 * Integrates with Filament, Spatie MediaLibrary, and Spatie Model States to manage authentication, files, and states.
 *
 * @property string $id
 * @property string|null $code
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $phone_number
 * @property string|null $address
 * @property string|null $last_login_at
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property Status $status
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organization> $organizations
 * @property-read int|null $organizations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassesMajor> $classesMajors
 * @property-read int|null $classes_majors_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\OneTimePasswords\Models\OneTimePassword> $oneTimePasswords
 * @property-read int|null $one_time_passwords_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User orWhereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User orWhereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @property-read \App\Models\UserClassMajorEnrollment|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
class User extends Base\AuthModel implements FilamentUser, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Notifiable, Authorizable, HasStates, InteractsWithMedia, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'status',
        'name',
        'email',
        'password',
        'phone_number',
        'address',
    ];
    /**
     * Defines the attributes that should be cast to native types.
     * The 'status' attribute will be automatically cast to a Status::class object.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'status' => Status::class,
        ]);
    }

    /**
     * Defines the many-to-many relationship with the Organization Model.
     * A user can belong to multiple organizations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_users', 'user_id', 'organization_id')
            ->withTimestamps();
    }

    /**
     * Defines the many-to-many relationship with the ClassesMajor Model.
     * A user can enroll in multiple classes or majors.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function classesMajors()
    {
        return $this->belongsToMany(\App\Models\ClassesMajor::class, 'user_class_major_enrollments', 'user_id', 'class_major_id')
            ->using(\App\Models\UserClassMajorEnrollment::class);
    }


    /**
     * Registers media collections for the model.
     * This method defines the 'profile_picture' collection, which allows only a single file
     * and restricts accepted file types to images.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('profile_picture')
            ->singleFile() // Only allow a single profile picture
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']); // Limit file types
    }

    /**
     * Registers media conversions that will be created automatically.
     * This method will create a 'thumb' version (100x100px) from the original image
     * whenever a file is added to the collection.
     *
     * @param \Spatie\MediaLibrary\MediaCollections\Models\Media|null $media
     * @return void
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->sharpen(10);
    }

    /**
     * Checks if the user can access a specific Filament Panel.
     * This is the central authorization method for Filament panels.
     *
     * @param  \Filament\Panel  $panel  The panel instance being accessed.
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // The logic checks based on the panel's ID and the user's role.
        // For example:
        // - To access the 'admin' panel (at /admin), the user must have the 'admin' role.
        // - To access the 'teacher' panel (at /teacher), the user must have the 'teacher' role.

        // A match statement is used to handle more complex cases.
        return match ($panel->getId()) {
            'admin' => $this->hasRole('admin'),
            'manager' => $this->hasRole(['manager', 'admin']), // 'manager' or 'admin' role can access the manager panel
            'teacher' => $this->hasAnyRole(['teacher', 'admin']), // 'teacher' or 'admin' role can access the teacher panel
            'student' => $this->hasRole('student'),
            default => false,
        };
    }
    /**
     * Get the color associated with a user status.
     *
     * @param string $state The status value.
     * @return string The color for Filament badge.
     */
    public static function getStatusColor(string $state): string
    {
        return match (strtolower($state)) {
            'active' => 'success',
            'pending' => 'warning',
            'banned' => 'danger',
            'inactive' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get the status options for the table filter.
     *
     * @return array
     */
    public static function getStatusOptions(): array
    {
        return [
            'active'    => 'Active',
            'pending'   => 'Pending',
            'banned'    => 'Banned',
            'inactive'  => 'Inactive',
        ];
    }
}