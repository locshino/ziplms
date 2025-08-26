<x-filament-panels::page>
    <h2 class="text-2xl font-bold mb-6"> - Thống kê</h2>

    <!-- Tổng quan Cards -->
    <div class="grid md:grid-cols-3 sm:grid-cols-1 gap-4 mb-6">
        <!-- Quiz Card -->
        <div
            class="relative bg-white rounded-2xl shadow-md p-5 h-52 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-gray-800 font-semibold text-lg">Quiz</h3>
                    <span class="text-gray-900 font-bold text-2xl">12</span>
                </div>
                <div class="w-14 h-14 flex items-center justify-center bg-blue-100 rounded-full shadow-sm">
                    <x-heroicon-o-academic-cap class="w-7 h-7 text-blue-600" />
                </div>
            </div>
            <div class="flex flex-col gap-2 mt-5 text-sm font-medium">
                <div class="flex justify-between text-orange-600"><span>Đã hết hạn:</span><span>3</span></div>
                <div class="flex justify-between text-green-600"><span>Đã làm:</span><span>5</span></div>
                <div class="flex justify-between text-blue-600"><span>Chưa làm:</span><span>4</span></div>
            </div>
        </div>

        <!-- Assignment Card -->
        <div
            class="relative bg-white rounded-2xl shadow-md p-5 h-52 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-gray-800 font-semibold text-lg">Assignments</h3>
                    <span class="text-gray-900 font-bold text-2xl">8</span>
                </div>
                <div class="w-14 h-14 flex items-center justify-center bg-purple-100 rounded-full shadow-sm">
                    <x-heroicon-o-clipboard-document-check class="w-7 h-7 text-purple-600" />
                </div>
            </div>
            <div class="flex flex-col gap-2 mt-5 text-sm font-medium">
                <div class="flex justify-between text-orange-600"><span>Đã hết hạn:</span><span>2</span></div>
                <div class="flex justify-between text-green-600"><span>Đã làm:</span><span>4</span></div>
                <div class="flex justify-between text-blue-600"><span>Chưa nộp:</span><span>2</span></div>
            </div>
        </div>

        <!-- Documents Card -->
        <div
            class="relative bg-white rounded-2xl shadow-md p-5 h-52 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-gray-800 font-semibold text-lg">Documents</h3>
                    <span class="text-gray-900 font-bold text-2xl">20</span>
                </div>
                <div class="w-14 h-14 flex items-center justify-center bg-yellow-100 rounded-full shadow-sm">
                    <x-heroicon-o-document-text class="w-7 h-7 text-yellow-600" />
                </div>
            </div>
            <div class="flex flex-col gap-2 mt-5 text-sm font-medium">
                <div class="flex justify-between text-green-600"><span>Đã làm:</span><span>--</span></div>
                <div class="flex justify-between text-blue-600"><span>Chưa làm:</span><span>--</span></div>
            </div>
        </div>
    </div>

    <x-tabs-nav :navs="[['key' => 'quizzes', 'label' => 'Quizzes'], ['key' => 'assignments', 'label' => 'Assignments'], ['key' => 'document', 'label' => 'Document']]" :initial="'quizzes'">
        {{-- Tab Quizzes --}}
        <div x-data="{ open: false }" class="relative inline-block text-left">
            <!-- Button -->
            <button @click="open = !open" class="inline-flex items-center rounded-full px-4 py-2 shadow-sm bg-gray-800 text-white font-medium
               hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500
               transition-transform duration-200">
                Bộ lọc lớp
                <svg class="ml-2 h-4 w-4 transform transition-transform duration-200" :class="{ 'rotate-180': open }"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                class="absolute left-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-10 z-20">

                <div class="py-1 max-h-60 overflow-y-auto">
                    <!-- Tất cả lớp -->
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Tất cả lớp
                    </a>

                    <!-- Lớp mẫu -->
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Lớp 10A1
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Lớp 10A2
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Lớp 11B1
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Lớp 12C3
                    </a>
                </div>
            </div>
        </div>


        <div name="quizzes" x-show="activeTab === 'quizzes'" id="quizzes" role="tabpanel">
            <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-2xl shadow-lg">
                <h2 class="text-3xl font-extrabold text-gray-800 dark:text-white mb-6">Quiz Reports</h2>

                <div class="overflow-x-auto rounded-xl shadow-md">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-indigo-500 to-purple-600">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-white">Title</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-white">Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-white">Start Date</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-white">End Date</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-white">Max Attempts</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-white">Single Session</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-white">Time Limit</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($this->ongoingQuizzes as $quiz)
                                <tr class="hover:bg-indigo-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-4 py-3 text-gray-800 dark:text-gray-200 font-medium">{{ $quiz->title }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold 
                                                        {{ $quiz->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                                            {{ $quiz->status}}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        {{ $quiz->pivot->start_at ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        {{ $quiz->pivot->end_at ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        {{ $quiz->max_attempts ?? 'Unlimited' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        @if($quiz->is_single_session)
                                            <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                                        @else
                                            <x-heroicon-o-x-circle class="w-5 h-5 text-red-500" />
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        {{ $quiz->time_limit_minutes ?? '-' }} mins
                                    </td>
                                    <td class="px-4 py-3 flex gap-2">
                                        <a href=""
                                            class="flex items-center gap-1 px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold shadow transition transform hover:scale-105">
                                            <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                                            Export
                                        </a>
                                        <a href=""
                                            class="flex items-center gap-1 px-3 py-1 bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl font-semibold shadow transition transform hover:scale-105">
                                            <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Tab Assignments --}}
        <div name="assignments" x-show="activeTab === 'assignments'" id="assignments" role="tabpanel">
            <div class="bg-white rounded-2xl shadow-md p-5">
                <h3 class="text-lg font-bold mb-3">Chi tiết Assignment</h3>
                <table class="w-full table-auto text-sm border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Tiêu đề</th>
                            <th class="px-3 py-2 text-left">Trạng thái</th>
                            <th class="px-3 py-2 text-left">Ngày bắt đầu</th>
                            <th class="px-3 py-2 text-left">Ngày kết thúc</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="px-3 py-2">Assignment 1</td>
                            <td class="px-3 py-2">Đang làm</td>
                            <td class="px-3 py-2">2025-08-18</td>
                            <td class="px-3 py-2">2025-08-24</td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-3 py-2">Assignment 2</td>
                            <td class="px-3 py-2">Đã nộp</td>
                            <td class="px-3 py-2">2025-08-15</td>
                            <td class="px-3 py-2">2025-08-22</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </x-tabs-nav>
</x-filament-panels::page>