<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class GeneralSettings
 *
 * This class represents the general settings for the application.
 * It extends the Settings class and implements HasMedia to handle media files.
 *
 * @property string $site_name The name of the site, e.g., 'ZipLMS'.
 * @property bool $site_active Indicates if the site is active or in maintenance mode.
 * @property string|null $logo_path The path to the logo image.
 * @property string|null $favicon_path The path to the favicon image.
 * @property string|null $theme_color The primary theme color, e.g., '#0d6efd'.
 * @property string|null $support_email The support email address.
 * @property bool $is_setup_complete Indicates if the initial setup is complete.
 */
class GeneralSettings extends Settings implements HasMedia
{
    use InteractsWithMedia;

    public string $site_name = 'ZipLMS';

    public ?bool $site_active = false;

    public ?string $logo_path = null;

    public ?string $favicon_path = null;

    public ?string $theme_color = null;

    public ?string $support_email = null;

    public static function group(): string
    {
        return 'general'; // This is the database group key.
    }

    /**
     * Register media collections for the GeneralSettings model.
     *
     * This method defines the media collections that can be used with this settings model.
     * It allows for single file uploads for logos and favicons.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logos')->singleFile();
        $this->addMediaCollection('favicons')->singleFile();
    }
}
