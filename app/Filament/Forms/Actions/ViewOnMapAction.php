<?php

namespace App\Filament\Forms\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;

class ViewOnMapAction
{
    /**
     * Creates a new action to view a location on a map.
     *
     * @param  string|array  $input  The input can be a string (JSON) or an array with 'lat' and 'lng' keys.
     * @return Action The action to view on the map.
     *
     * @example
     * // Using a string input (JSON)
     * $action = ViewOnMapAction::make('locate');
     *
     * // Using an array input
     * $action = ViewOnMapAction::make(['lat' => 37.7749, 'lng' => -122.4194]);
     */
    public static function make(string|array $input): Action
    {
        return Action::make('view_on_map')
            ->label('Xem trên bản đồ')
            ->icon('heroicon-o-map')
            ->color('primary')
            ->url(function (Get $get) use ($input) {
                $coords = is_string($input) ? $get($input) : $input;

                return static::buildGoogleMapsUrl($coords);
            })
            ->openUrlInNewTab()
            ->visible(function (Get $get) use ($input) {
                $coords = is_string($input) ? $get($input) : $input;

                return static::hasCoordinates($coords);
            });
    }

    /**
     * Builds a Google Maps URL from the given coordinates.
     *
     * @param  array|null  $coords  The coordinates array with 'lat' and 'lng' keys.
     * @return string|null The Google Maps URL or null if coordinates are invalid.
     */
    protected static function buildGoogleMapsUrl(?array $coords): ?string
    {
        if (! static::hasCoordinates($coords)) {
            return null;
        }

        return "https://www.google.com/maps/search/?api=1&query={$coords['lat']},{$coords['lng']}";
    }

    /**
     * Checks if the given coordinates array has valid latitude and longitude.
     *
     * @param  array|null  $coords  The coordinates array with 'lat' and 'lng' keys.
     * @return bool True if the coordinates are valid, false otherwise.
     */
    protected static function hasCoordinates(?array $coords): bool
    {
        return is_array($coords)
            && filled($coords['lat'] ?? null)
            && filled($coords['lng'] ?? null);
    }
}
