<?php

namespace App\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use \Guava\Calendar\Filament\CalendarWidget as GuavaCalendarWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\FetchInfo;

class CalendarWidget extends GuavaCalendarWidget
{
    use HasWidgetShield;

    protected function getEvents(FetchInfo $info): Collection | array | Builder {
        return [];
    }
}
