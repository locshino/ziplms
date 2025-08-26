<x-filament-panels::page :fullHeight="true">
    <div class="space-y-6">

        <!-- Tabs & Filters -->
        <x-tabs-nav :navs="[
        ['key' => 'ongoing', 'label' => 'Đang diễn ra', 'count' => count($ongoingCourses)],
        ['key' => 'completed', 'label' => 'Đã kết thúc', 'count' => count($completedCourses)],
    ]">

            <!-- Toolbar: Filter + Sort + Search -->
            <div
                class="mb-6 flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-3">
                <div class="flex space-x-3">
                    <!-- Nút Bộ Lọc -->
                    <div x-data="{ open: false, openTag: false, openTeacher: false }"
                        class="relative inline-block text-left">
                        <button @click="open = !open" class="inline-flex items-center rounded-full px-5 py-2 shadow-md text-white font-medium
               bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-blue-600
               border border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400
               transition-transform duration-200">
                            Bộ lọc
                            <svg class="ml-2 h-5 w-5 transform transition-transform duration-200"
                                :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-64 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-20">

                            <div class="py-2 max-h-72 overflow-y-auto">

                                <!-- Nhóm Tag -->
                                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <button @click="openTag = !openTag"
                                        class="w-full flex justify-between items-center text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Tags
                                        <svg class="h-4 w-4 transform transition-transform duration-200"
                                            :class="{ 'rotate-180': openTag }" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="openTag" class="mt-2 space-y-1">
                                        <a href="#" wire:click.prevent="filterCoursesByTag(null)"
                                            class="block px-2 py-1 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                            Tất cả tag
                                        </a>
                                        @foreach($this->tags as $tag)
                                            <a href="#" wire:click.prevent="filterCoursesByTag('{{ $tag }}')"
                                                class="block px-2 py-1 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                                                {{ $tag }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Nhóm Teacher -->
                                <div class="px-4 py-2">
                                    <button @click="openTeacher = !openTeacher"
                                        class="w-full flex justify-between items-center text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Teachers
                                        <svg class="h-4 w-4 transform transition-transform duration-200"
                                            :class="{ 'rotate-180': openTeacher }" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="openTeacher" class="mt-2 space-y-1">
                                        <a href="#" wire:click.prevent="filterCoursesByTeacher(null)"
                                            class="block px-2 py-1 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                            Tất cả giáo viên
                                        </a>
                                        @foreach($this->teachers as $teacher)
                                            <a href="#" wire:click.prevent="filterCoursesByTeacher({{ $teacher->id }})"
                                                class="block px-2 py-1 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                                                {{ $teacher->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>




                    <!-- Nút Sắp xếp -->
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click="open = !open" class="inline-flex items-center rounded-full px-5 py-2 shadow-md text-white font-medium
                                   bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600
                                   border border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400
                                   transition-transform duration-200">
                            Sắp xếp
                            <svg class="ml-2 h-5 w-5 transform transition-transform duration-200"
                                :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-56 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-20">
                            <div class="py-1">
                                <a href="#" wire:click.prevent="sortCourses('newest')"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Mới
                                    nhất</a>
                                <a href="#" wire:click.prevent="sortCourses('oldest')"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Cũ
                                    nhất</a>
                                <a href="#" wire:click.prevent="sortCourses('end_at')"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Ngày
                                    kết thúc gần nhất</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thanh tìm kiếm + nút Load lại -->
                <div class="w-full md:w-1/2 lg:w-1/3 flex items-center space-x-2">
                    <div class="relative flex-1">
                        <input type="text" wire:model="searchCourse" placeholder="Tìm kiếm..." class="w-full pl-12 pr-4 py-2.5 rounded-3xl border border-gray-300 dark:border-gray-600
                                   bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-md
                                   focus:outline-none focus:ring-4 focus:ring-indigo-400 focus:border-indigo-500
                                   transition-all duration-300" />
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 cursor-pointer"
                            wire:click="searchCourses">
                            <x-heroicon-s-magnifying-glass class="w-5 h-5 text-indigo-500" />
                        </div>
                    </div>
                    <button onclick="window.location.reload()"
                        class="p-2 text-green-500 hover:text-green-700 focus:outline-none">
                        <x-heroicon-s-arrow-path class="w-6 h-6 animate-spin-slow" />
                    </button>


                </div>
            </div>

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
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                            Chưa có khóa học nào đang diễn ra.
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
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                            Chưa có khóa học nào đã kết thúc.
                        </div>
                    @endif
                </div>
            </div>

        </x-tabs-nav>
    </div>
</x-filament-panels::page>