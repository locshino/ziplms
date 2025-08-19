@props(['navs' => [], 'initial' => null])

@php
    // Determine first key from navs to use as default active if none provided
    $firstKey = null;
    if (is_array($navs) && count($navs) > 0) {
        $first = $navs[0];
        $firstKey = is_array($first) ? $first['key'] ?? (array_values($first)[0] ?? null) : $first;
    }
    $init = $initial ?? $firstKey;
    // Fallback to empty string to avoid Alpine errors
    $init = $init ?? '';
@endphp

<div x-data="{ activeTab: '{{ $init }}' }" class="space-y-6">
    <!-- Tab Navigation (pill style) -->
    <div class="px-4">
        <div class="max-w-5xl mx-auto">
            <div class="border border-gray-200 dark:border-gray-700 rounded-full p-1">
                <div class="flex items-center gap-2">
                    @foreach ($navs as $nav)
                        @php
                            $key = is_array($nav) ? $nav['key'] ?? null : $nav;
                            $label = is_array($nav) ? $nav['label'] ?? ($nav['name'] ?? $key) : $nav;
                            $count = is_array($nav) ? $nav['count'] ?? null : null;
                        @endphp

                        <button @click="activeTab = '{{ $key }}'"
                            :class="activeTab === '{{ $key }}' ?
                                'bg-white dark:bg-gray-800 text-primary-600 dark:text-primary-400 shadow-sm' :
                                'bg-transparent text-gray-500 dark:text-gray-400'"
                            class="flex-1 text-center py-3 px-6 rounded-full font-semibold transition-colors duration-200 relative"
                            type="button" id="{{ $key ?? 'tab' }}-tab">
                            <span class="relative">{{ $label }}</span>
                            @if (!is_null($count))
                                <span
                                    class="ml-2 inline-block bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                    {{ $count }}
                                </span>
                            @endif
                        </button>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    {{-- Render named slot 'controls' if provided by the caller --}}
    @isset($controls)
        <div class="ml-4 flex-shrink-0">{{ $controls }}</div>
    @endisset

    <div>
        {{ $slot }}
    </div>
</div>
