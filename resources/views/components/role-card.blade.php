@props([
    'href' => '#',
    'title' => 'Card Title',
    'subtitle' => 'Card subtitle description.',
])

{{-- Use DaisyUI card component classes --}}
<a href="{{ $href }}"
    {{ $attributes->merge(['class' => 'card card-bordered bg-base-100 shadow-md transition-all duration-300 group hover:scale-[1.03] hover:border-primary hover:shadow-2xl hover:shadow-primary/25']) }}>
    <div class="card-body items-center text-center p-6">
        {{-- Icon Slot --}}
        @if (isset($icon))
            <div class="mb-4 text-primary transition-colors">
                <div class="h-16 w-16">
                    {{ $icon }}
                </div>
            </div>
        @endif

        {{-- Title --}}
        <h2 class="card-title text-base-content">
            {{ $title }}
        </h2>

        {{-- Subtitle --}}
        <p class="text-base-content/70 text-sm">
            {{ $subtitle }}
        </p>
    </div>
</a>
