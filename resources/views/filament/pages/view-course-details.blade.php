<x-filament-panels::page>
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <img class="object-cover w-10 h-10 rounded-full"
                    src="https://ui-avatars.com/api/?name={{ urlencode($record->teacher->name ?? 'T') }}&background=random&color=fff"
                    alt="{{ $record->teacher->name ?? 'Teacher' }}">
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $record->teacher->name ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                        <x-heroicon-o-academic-cap class="w-4 h-4" />
                        Giảng viên
                    </p>
                </div>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            {{ $record->title }}
        </h1>
        <div class="mt-4 prose prose-lg max-w-none dark:prose-invert">
            {!! $record->description !!}
        </div>

    </div>
    <div x-data="{
            activeTab: 'details',
            slider: {
                width: 0,
                left: 0,
            },
            updateSlider(element) {
                this.slider.width = element.offsetWidth + 'px';
                this.slider.left = element.offsetLeft + 'px';
            }
        }" x-init="() => {
            setTimeout(() => {
                updateSlider($refs.tab_details);
            }, 50);
        }" class="relative inline-block rounded-lg bg-gray-100 p-1 dark:bg-gray-800">
        <div class="absolute top-1 h-9 rounded-md bg-white shadow-sm transition-all duration-300 ease-in-out dark:bg-gray-700"
            :style="{
                width: slider.width,
                transform: `translateX(${slider.left})`
            }"></div>

        <nav class="relative flex" aria-label="Tabs">

            <button x-ref="tab_assignments"
                @click="activeTab = 'assignments'; updateSlider($el); $wire.set('activeTab', 'assignments')"
                type="button" :class="{
                    'text-primary-600 dark:text-primary-400': activeTab === 'assignments',
                    'text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200': activeTab !== 'assignments'
                }" class="relative z-10 rounded-md px-4 py-2 text-sm font-semibold transition-colors duration-300">
                Bài tập
            </button>
            <button x-ref="tab_quizzes"
                @click="activeTab = 'quizzes'; updateSlider($el); $wire.set('activeTab', 'quizzes')" type="button"
                :class="{
                    'text-primary-600 dark:text-primary-400': activeTab === 'quizzes',
                    'text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200': activeTab !== 'quizzes'
                }" class="relative z-10 rounded-md px-4 py-2 text-sm font-semibold transition-colors duration-300">
                Quiz
            </button>
            <button x-ref="tab_students"
                @click="activeTab = 'students'; updateSlider($el); $wire.set('activeTab', 'students')" type="button"
                :class="{
                    'text-primary-600 dark:text-primary-400': activeTab === 'students',
                    'text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200': activeTab !== 'students'
                }" class="relative z-10 rounded-md px-4 py-2 text-sm font-semibold transition-colors duration-300">
                Học viên
            </button>
        </nav>
    </div>
    <div class="pt-6">
        <div x-show="$wire.activeTab === 'quizzes'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100" x-cloak>
            <x-filament::section>
                @include('filament.pages.course-quizzes-list', ['quizzes' => $record->quizzes])
            </x-filament::section>
        </div>
        <div x-show="$wire.activeTab === 'assignments'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100" x-cloak>
            <x-filament::section>
                @include('filament.pages.course-assignments-list', ['assignments' => $record->assignments])
            </x-filament::section>
        </div>
        <div x-show="$wire.activeTab === 'students'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100" x-cloak>
            <x-filament::section>
                @include('filament.pages.course-students-list', ['students' => $record->students])
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>