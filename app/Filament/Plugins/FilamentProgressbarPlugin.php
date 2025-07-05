<?php

namespace App\Filament\Plugins;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class FilamentProgressbarPlugin implements Plugin
{
    protected string $id = 'filament-progressbar';

    protected ?string $color;

    protected ?string $height;

    protected ?string $theme;

    protected ?bool $ajax;

    protected ?bool $document;

    protected ?bool $eventLag;

    protected ?int $restartDelay;

    protected ?string $renderHook;

    public function getId(): string
    {
        return $this->id;
    }

    public function config(): void
    {
        $config = config('filament-progressbar', []);

        $this->color ??= $config['color'];
        $this->height ??= $config['height'];
        $this->theme ??= $config['theme'];
        $this->ajax ??= $config['ajax'];
        $this->document ??= $config['document'];
        $this->eventLag ??= $config['eventLag'];
        $this->restartDelay ??= $config['restartDelay'];
        $this->renderHook ??= $config['renderHook'];

    }

    public function getThemeCss(?string $theme = null): string
    {
        $theme ??= $this->theme;

        return "css/pace/pace-theme-{$theme}.css";
    }

    public function renderHook(string $renderHook): static
    {
        $this->renderHook = $renderHook;

        return $this;
    }

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function height(string $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function theme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function ajax(bool $enabled): static
    {
        $this->ajax = $enabled;

        return $this;
    }

    public function document(bool $enabled): static
    {
        $this->document = $enabled;

        return $this;
    }

    public function eventLag(bool $enabled): static
    {
        $this->eventLag = $enabled;

        return $this;
    }

    public function restartDelay(int $ms): static
    {
        $this->restartDelay = $ms;

        return $this;
    }

    public function register(Panel $panel): void
    {
        $this->config();

        $themeCssPath = $this->getThemeCss();

        if (! file_exists(public_path($themeCssPath))) {
            $themeCssPath = $this->getThemeCss('default');
        }

        $paceJs = asset('js/pace/pace.js');
        $paceCss = asset($themeCssPath);

        FilamentView::registerRenderHook($this->renderHook, function () use ($paceJs, $paceCss) {
            return Blade::render(<<<'HTML'
                <!-- Pace Theme CSS -->
                <link rel="stylesheet" href="{{ $paceCss }}">

                <!-- Pace Options -->
                <script>
                    window.paceOptions = {
                        ajax: {{ $ajax ? 'true' : 'false' }},
                        document: {{ $document ? 'true' : 'false' }},
                        eventLag: {{ $eventLag ? 'true' : 'false' }},
                        restartOnRequestAfter: {{ $restartDelay }},
                    };
                </script>

                <!-- Pace JS -->
                <script src="{{ $paceJs }}"></script>

                <!-- Custom styling -->
                <style>
                    .pace .pace-progress {
                        background: {{ $color }};
                        height: {{ $height }};
                    }
                </style>
            HTML, [
                'paceCss' => $paceCss,
                'paceJs' => $paceJs,
                'color' => $this->color,
                'height' => $this->height,
                'ajax' => $this->ajax,
                'document' => $this->document,
                'eventLag' => $this->eventLag,
                'restartDelay' => $this->restartDelay,
            ]);
        });
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static;
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
