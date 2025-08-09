<x-filament-panels::page>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ $this->getTitle() }}</h1>
        <div>
            {{ $this->createCourseAction }}
        </div>
    </div>
    <div class="mt-6">
        <div class="flex items-center justify-between gap-4">
            <div class="w-full max-w-md">
                <div class="relative">
                    <x-filament::icon
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5 pointer-events-none" />
                    <x-filament::input type="search" wire:model.live.debounce.500ms="search"
                        placeholder="Tìm kiếm khóa học..."
                        class="pl-10 pr-4 py-2 rounded-xl border-2 border-gray-300 hover:border-primary-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-400/30 shadow-sm transition-all duration-200 w-full" />
                </div>
            </div>
        </div>
    </div>

    @php
        $courses = $this->courses;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 hover:gap-6">
        @forelse ($this->courses as $course)
            <div
                class="flex flex-col bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden 
                                            transition-all duration-300 hover:shadow-lg dark:bg-gray-800 dark:border-gray-700">

                <a href="{{ \App\Filament\Pages\ViewCourseDetails::getUrl(['record' => $course->id]) }}" class="block">
                    <div class="aspect-video bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        @if ($course->thumbnail_url)
                            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}"
                                class="object-cover w-full h-full transition-transform duration-300 hover:scale-105">
                        @else
                            <div class="flex items-center justify-center w-full h-full">
                                <x-heroicon-o-photo class="w-12 h-12 text-gray-400" />
                            </div>
                        @endif
                    </div>
                </a>
                <div class="flex flex-col flex-1  p-1">
                    <div class="flex items-start justify-between gap-4">
                        <h3 class="flex-1 text-lg font-bold text-gray-900 dark:text-gray-100">
                            <a href="{{ \App\Filament\Pages\ViewCourseDetails::getUrl(['record' => $course->id]) }}"
                                class="hover:underline">
                                {{ \Illuminate\Support\Str::limit($course->title, 40) }}
                            </a>
                        </h3>
                    </div>

                    <p class="mt-2 text-sm text-gray-500">
                        {{ \Illuminate\Support\Str::limit(strip_tags($course->description), 100, '...') }}
                    </p>

                </div>

                <div
                    class="flex items-center justify-between p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <img class="object-cover w-10 h-10 rounded-full ring-2 ring-indigo-200 dark:ring-indigo-500"
                            src="https://ui-avatars.com/api/?name={{ urlencode($course->teacher->name ?? 'T') }}&background=random&color=fff"
                            alt="{{ $course->teacher->name ?? 'Teacher' }}">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ $course->teacher->name ?? 'N/A' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <x-heroicon-o-academic-cap class="w-4 h-4" />
                                Giảng viên
                            </p>
                        </div>
                    </div>
                    <x-filament-actions::group :actions="[
                ($this->viewCourseAction)(['record' => $course->id]),
                ($this->editCourseAction)(['record' => $course->id]),
                ($this->deleteCourseAction)(['record' => $course->id]),
            ]" icon="heroicon-m-ellipsis-vertical" color="gray" dropdown-placement="top-end" />
                </div>

            </div>
        @empty
            <div
                class="col-span-full rounded-lg border border-gray-200 bg-white p-6 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-gray-500 dark:text-gray-400">
                    Không có khóa học nào.
                </p>
            </div>
        @endforelse
    </div>

    @if ($this->courses->hasPages())
        <div class="py-4 mt-6">
            {{ $this->courses->links() }}
        </div>
    @endif
</x-filament-panels::page>