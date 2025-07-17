@php
    use Filament\Support\Enums\MaxWidth;
@endphp

<div class="filament-tree-infolist flex">
    @foreach ($getComponents() as $infolistComponent)
        {{ $infolistComponent }}
    @endforeach
</div>
