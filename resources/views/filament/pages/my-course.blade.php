<x-filament-panels::page :fullHeight="true">
    <div class="space-y-6">
        <x-tabs-nav :navs="[
            ['key' => 'ongoing', 'label' => 'Đang diễn ra', 'count' => count($ongoingCourses)],
            ['key' => 'completed', 'label' => 'Đã kết thúc', 'count' => count($completedCourses)],
        ]">
            <!-- Tab Content -->
            <div>
                <!-- Ongoing Courses Tab -->
                <div x-show="activeTab === 'ongoing'" class="space-y-4">
                    @if ($ongoingCourses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($ongoingCourses as $course)
                                <x-course-card :course="$course" :redirectTo="$this->getLinkToCourseDetail($course)" />
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto h-12 w-12 text-gray-400">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa có khóa học nào
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bạn chưa tham gia khóa học nào đang
                                diễn ra.</p>
                        </div>
                    @endif
                </div>

                <!-- Completed Courses Tab -->
                <div x-show="activeTab === 'completed'" class="space-y-4">
                    @if ($completedCourses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($completedCourses as $course)
                                <x-course-card :course="$course" :completed="true" />
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto h-12 w-12 text-gray-400">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa hoàn thành khóa
                                học
                                nào</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bạn chưa hoàn thành khóa học nào.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </x-tabs-nav>

        <!-- Tab Content -->
        <div>
            <!-- Ongoing Courses Tab -->
            <div x-show="activeTab === 'ongoing'" class="space-y-4">
                @if ($ongoingCourses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($ongoingCourses as $course)
                            <x-course-card :course="$course" />
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-gray-400">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa có khóa học nào</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bạn chưa tham gia khóa học nào đang
                            diễn ra.</p>
                    </div>
                @endif
            </div>

            <!-- Completed Courses Tab -->
            <div x-show="activeTab === 'completed'" class="space-y-4">
                @if ($completedCourses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($completedCourses as $course)
                            <x-course-card :course="$course" :completed="true" />
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-gray-400">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa hoàn thành khóa học
                            nào</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bạn chưa hoàn thành khóa học nào.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
