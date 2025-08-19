@props(['course', 'redirectTo' => '#', 'completed' => false])

@php
    // Get course cover image or generate random gradient
    $coverImage = $course->getFirstMediaUrl('course_cover');
    $gradientColors = [
        'from-blue-500 via-blue-600 to-blue-700',
        'from-purple-500 via-purple-600 to-purple-700',
        'from-emerald-500 via-emerald-600 to-emerald-700',
        'from-rose-500 via-rose-600 to-rose-700',
        'from-amber-500 via-amber-600 to-amber-700',
        'from-indigo-500 via-indigo-600 to-indigo-700',
        'from-pink-500 via-pink-600 to-pink-700',
        'from-teal-500 via-teal-600 to-teal-700',
        'from-cyan-500 via-cyan-600 to-cyan-700',
        'from-orange-500 via-orange-600 to-orange-700',
    ];
    // Use stable color by hashing course id to a palette index
    $randomGradient = $gradientColors[crc32($course->id) % count($gradientColors)];

    // Gradients for title hover
    $titleGradients = [
        'from-pink-500 via-red-500 to-yellow-500',
        'from-green-400 via-cyan-500 to-blue-600',
        'from-purple-500 via-pink-500 to-red-600',
        'from-yellow-400 via-orange-500 to-red-600',
        'from-teal-400 via-blue-500 to-indigo-600',
    ];
    $randomTitleGradient = $titleGradients[crc32('title-' . $course->id) % count($titleGradients)];

    // Different wave separators so not every card has the same curve.
    $wavePaths = [
        'M0,0 C150,120 350,0 500,60 C650,120 850,0 1000,60 C1100,120 1200,60 1200,60 L1200,120 L0,120 Z',
        'M0,0 C200,80 300,0 480,40 C660,80 820,10 1000,40 C1100,60 1200,20 1200,20 L1200,120 L0,120 Z',
        'M0,0 C120,100 320,20 480,60 C640,100 840,20 1000,60 C1100,90 1200,60 1200,60 L1200,120 L0,120 Z',
        'M0,0 C100,40 250,120 420,60 C590,0 760,80 940,40 C1080,10 1200,80 1200,80 L1200,120 L0,120 Z',
        'M0,0 C180,120 360,40 540,80 C720,120 900,20 1080,80 C1150,110 1200,60 1200,60 L1200,120 L0,120 Z',
    ];

    // Deterministic selection so cards keep the same curve across requests for same course
    $wavePath = $wavePaths[crc32($course->id) % count($wavePaths)];
@endphp
<a href="{{ $redirectTo }}" class="block">
    <div x-data="{ expanded: false }"
        class="group bg-white dark:bg-gray-800 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 dark:border-gray-700 hover:scale-[1.02] hover:-translate-y-1 h-full flex flex-col">
        <!-- Course Cover Image or Gradient -->
        <div class="relative h-48 overflow-hidden">
            @if ($coverImage)
                <img src="{{ $coverImage }}" alt="{{ $course->title }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <!-- Overlay gradient for better text readability -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
            @else
                <!-- Enhanced gradient background with pattern -->
                <div
                    class="relative w-full h-full bg-gradient-to-br {{ $randomGradient }} flex items-center justify-center overflow-hidden">
                    <!-- Animated background pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div
                            class="absolute top-0 -left-4 w-72 h-72 bg-white rounded-full mix-blend-multiply filter blur-xl animate-pulse">
                        </div>
                        <div
                            class="absolute top-0 -right-4 w-72 h-72 bg-white rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000">
                        </div>
                        <div
                            class="absolute -bottom-8 left-20 w-72 h-72 bg-white rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-4000">
                        </div>
                    </div>

                    <!-- Course icon/text -->
                    <div class="relative text-white text-center z-10">
                        <div
                            class="w-16 h-16 mx-auto mb-2 bg-white/20 rounded-2xl backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <div class="text-sm font-medium opacity-90">Course Image</div>
                    </div>
                </div>
            @endif

            <!-- Modern wave separator -->
            <div class="absolute bottom-0 left-0 w-full">
                <svg class="w-full h-6 text-white dark:text-gray-800" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="{{ $wavePath }}" fill="currentColor" />
                </svg>
            </div>

            <!-- Completed Badge with enhanced styling -->
            @if ($completed)
                <div class="absolute top-4 right-4 z-10">
                    <div
                        class="flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-500 text-white shadow-lg backdrop-blur-sm border border-emerald-400/30">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Hoàn thành
                    </div>
                </div>
            @endif
        </div>

        <!-- Course Content with enhanced styling -->
        <div
            class="p-6 bg-gradient-to-b from-white to-gray-50/50 dark:from-gray-800 dark:to-gray-800/50 flex-grow flex flex-col">
            <!-- Course Title with better typography -->
            <h3
                class="text-lg font-bold text-gray-900 dark:text-white line-clamp-2 leading-6 mb-2 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r {{ $randomTitleGradient }} transition-all duration-300">
                {{ $course->title }}
            </h3>

            <!-- Teacher Info -->
            <div class="flex items-center gap-x-2 text-xs text-gray-500 dark:text-gray-400 mb-4">
                <img class="h-6 w-6 rounded-full object-cover"
                    src="{{ $course->teacher->getMedia('avatar')->first() ?? '/images/avatars/default.png' }}"
                    alt="{{ $course->teacher->name }}">
                <span>{{ $course->teacher->name }}</span>
            </div>


            <!-- Course Tags with improved styling -->
            <div class="flex flex-wrap gap-2 items-center">
                @if ($course->tags && $course->tags->count() > 0)
                    @foreach ($course->tags->take(2) as $tag)
                        <span
                            class="tag-item inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 dark:from-blue-900/50 dark:to-indigo-900/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800 hover:shadow-sm transition-all duration-200">
                            {{ $tag->name }}
                        </span>
                    @endforeach

                    <template x-if="expanded">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($course->tags->skip(2) as $tag)
                                <span
                                    class="tag-item inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 dark:from-blue-900/50 dark:to-indigo-900/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800 hover:shadow-sm transition-all duration-200">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </template>

                    @if ($course->tags->count() > 2)
                        <button @click.prevent.stop="expanded = !expanded"
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 z-10">
                            <span x-show="!expanded">+{{ $course->tags->count() - 2 }}</span>
                            <svg x-show="expanded" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                    @endif
                @else
                    <!-- Default tags with enhanced styling -->
                    <span
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gradient-to-r from-orange-50 to-red-50 text-orange-700 dark:from-orange-900/50 dark:to-red-900/50 dark:text-orange-300 border border-orange-200 dark:border-orange-800">
                        PHP
                    </span>
                    <span
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gradient-to-r from-red-50 to-pink-50 text-red-700 dark:from-red-900/50 dark:to-pink-900/50 dark:text-red-300 border border-red-200 dark:border-red-800">
                        Laravel
                    </span>
                @endif
            </div>

            <!-- Course metadata (optional) -->
            <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Cập nhật {{ $course->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</a>

<style>
    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.1;
        }

        50% {
            transform: scale(1.05);
            opacity: 0.2;
        }
    }

    .animation-delay-2000 {
        animation-delay: 2s;
    }

    .animation-delay-4000 {
        animation-delay: 4s;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .tag-item {
        flex-shrink: 0;
    }
</style>
