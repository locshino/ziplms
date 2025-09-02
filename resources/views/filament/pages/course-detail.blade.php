<x-filament-panels::page>

    @if ($this->course)
        @if ($this->course->description)
            <div class="prose dark:prose-invert mt-1">
                {!! $this->course->description !!}
            </div>
        @endif
        <!-- Dashboard Tổng quan -->
        <div class="grid md:grid-cols-3 sm:grid-cols-1 gap-4">
            <div
                class="fi-section relative rounded-2xl p-5 h-52 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="fi-section-header-heading">Quiz</h3>
                        <span class="text-gray-900 dark:text-white font-bold text-2xl">
                            {{ $this->course->quizzes->count() }}
                        </span>
                    </div>
                    <div class="w-14 h-14 flex items-center justify-center fi-color-primary-100 dark:fi-color-primary-800 rounded-full shadow-sm">
                        <x-heroicon-o-academic-cap class="w-7 h-7 fi-color-primary-600 dark:fi-color-primary-400" />
                    </div>
                </div>

                @php
                    $totalQuizzes = $this->course->quizzes->count();
                    $completedQuizPercentage = $totalQuizzes > 0 ? ($this->hasAttempted->count() / $totalQuizzes) * 100 : 0;
                @endphp
                <div class="absolute top-4 right-4 w-16 h-16">
                    <svg viewBox="0 0 36 36" class="w-full h-full">
                        <circle class="text-gray-200 dark:text-gray-700" stroke-width="4" stroke="currentColor" fill="none" cx="18" cy="18"
                            r="16" />
                        <circle class="text-green-400" stroke-width="4"
                            stroke-dasharray="{{ $completedQuizPercentage }} {{ 100 - $completedQuizPercentage }}"
                            stroke-linecap="round" fill="none" cx="18" cy="18" r="16" />
                    </svg>
                </div>

                <div class="flex flex-col gap-2 mt-5 text-sm font-medium">
                    <div class="flex justify-between text-green-600">
                        <span>Đã hoàn thành:</span>
                        <span>{{ $this->hasAttempted->count() }}</span>
                    </div>
                    <div class="flex justify-between text-blue-600">
                        <span>Cần làm:</span>
                        <span>{{ $this->ongoingQuizzes->count() }}</span>
                    </div>
                    <div class="flex justify-between text-orange-600">
                        <span>Đã bỏ lỡ:</span>
                        <span>{{ $this->missedQuizzes->count() }}</span>
                    </div>
                </div>
            </div>

            <div
                class="fi-section relative rounded-2xl p-5 h-52 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="fi-section-header-heading">Assignment</h3>
                        <span class="text-gray-900 dark:text-white font-bold text-2xl">
                            {{ $this->course->assignments->count() }}
                        </span>
                    </div>
                    <div class="w-14 h-14 flex items-center justify-center fi-color-purple-100 dark:fi-color-purple-800 rounded-full shadow-sm">
                        <x-heroicon-o-clipboard-document-check class="w-7 h-7 fi-color-purple-600 dark:fi-color-purple-400" />
                    </div>
                </div>

                @php
                    $totalAssignments = $this->course->assignments->count();
                    $submittedPercentage = $totalAssignments > 0 ? ($this->hasSubmitted->count() / $totalAssignments) * 100 : 0;
                @endphp
                <div class="absolute top-4 right-4 w-16 h-16">
                    <svg viewBox="0 0 36 36" class="w-full h-full">
                        <circle class="text-gray-200 dark:text-gray-700" stroke-width="4" stroke="currentColor" fill="none" cx="18" cy="18"
                            r="16" />
                        <circle class="text-purple-400" stroke-width="4"
                            stroke-dasharray="{{ $submittedPercentage }} {{ 100 - $submittedPercentage }}"
                            stroke-linecap="round" fill="none" cx="18" cy="18" r="16" />
                    </svg>
                </div>

                <div class="flex flex-col gap-2 mt-5 text-sm font-medium">
                    <div class="flex justify-between text-green-600">
                        <span>Đã nộp bài:</span>
                        <span>{{ $this->hasSubmitted->count() }}</span>
                    </div>
                    <div class="flex justify-between text-blue-600">
                        <span>Cần nộp:</span>
                        <span>{{ $this->ongoingAssignments->count() }}</span>
                    </div>
                    <div class="flex justify-between text-orange-600">
                        <span>Đã bỏ lỡ:</span>
                        <span>{{ $this->missedAssignments->count() }}</span>
                    </div>
                </div>
            </div>

            <div
                class="fi-section relative rounded-2xl p-5 h-52 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg">

                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="fi-section-header-heading">Documents</h3>
                        <span class="text-gray-900 dark:text-white font-bold text-2xl">
                            {{ $this->documents->count() }}
                        </span>
                    </div>
                    <div class="w-14 h-14 flex items-center justify-center fi-color-yellow-100 dark:fi-color-yellow-800 rounded-full shadow-sm">
                        <x-heroicon-o-document-text class="w-7 h-7 fi-color-yellow-600 dark:fi-color-yellow-400" />
                    </div>
                </div>

                <div class="absolute top-4 right-4 w-16 h-16">
                    <svg viewBox="0 0 36 36" class="w-full h-full">
                        <circle class="text-gray-200 dark:text-gray-700" stroke-width="4" stroke="currentColor" fill="none" cx="18" cy="18"
                            r="16" />
                        <circle class="text-yellow-400" stroke-width="4" stroke-dasharray="0 100" stroke-dashoffset="25"
                            stroke-linecap="round" fill="none" cx="18" cy="18" r="16" />
                    </svg>
                </div>

                <div class="flex flex-col gap-2 mt-5 text-sm font-medium">
                    <div class="flex justify-between text-green-600">
                        <span>Đã xem:</span>
                        <span>--</span>
                    </div>
                    <div class="flex justify-between text-blue-600">
                        <span>Chưa xem:</span>
                        <span>--</span>
                    </div>
                </div>
            </div>
        </div>



        <x-tabs-nav :navs="[['key' => 'quizzes', 'label' => 'Quizzes'], ['key' => 'assignments', 'label' => 'Assignments'], ['key' => 'document', 'label' => 'Document']]" :initial="'quizzes'">

            <div>
                <!-- Quizzes -->
                <div x-show="activeTab === 'quizzes'" id="quizzes" role="tabpanel" class="mt-6">

                    <div
                        class="mb-6 flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-3">
                        <!-- 2 Nút Bộ Lọc & Sắp Xếp bên trái -->
                        <div class="flex space-x-3">

                            <!-- Nút Bộ Lọc Tag -->
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <!-- Button -->
                                <button @click="open = !open"
                                    class="fi-btn fi-btn-primary">
                                    Bộ lọc Tag
                                    <svg class="ml-2 h-4 w-4 transform transition-transform duration-200"
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
                                    class="fi-dropdown-panel absolute left-0 mt-2 w-56 rounded-lg shadow-lg z-20">

                                    <div class="py-1 max-h-60 overflow-y-auto">
                                        <!-- Tất cả tag -->
                                        <a href="#" wire:click.prevent="filterQuizzesByTag(null)"
                                            class="fi-dropdown-list-item">
                                            Tất cả tag
                                        </a>

                                        <!-- Lặp qua danh sách tag -->
                                        @foreach($this->quizTags as $tag)
                                            <a href="#" wire:click.prevent="filterQuizzesByTag('{{ $tag }}')"
                                                class="fi-dropdown-list-item">
                                                {{ $tag }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>






                            <!-- Nút Sắp Xếp -->
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <!-- Button -->
                                <button @click="open = !open"
                                    class="fi-btn fi-btn-secondary">
                                    Sắp xếp
                                    <svg class="ml-2 h-4 w-4 transform transition-transform duration-200"
                                        :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown Sắp Xếp -->
                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-1"
                                    class="fi-dropdown-panel absolute left-0 mt-2 w-56 rounded-lg shadow-lg z-20">
                                    <div class="py-1">
                                        <a href="#" wire:click.prevent="sortQuizzes('newest')"
                                            class="fi-dropdown-list-item">
                                            Mới nhất
                                        </a>
                                        <a href="#" wire:click.prevent="sortQuizzes('oldest')"
                                            class="fi-dropdown-list-item">
                                            Cũ nhất
                                        </a>
                                        <a href="#" wire:click.prevent="sortQuizzes('end_at')"
                                            class="fi-dropdown-list-item">
                                            Ngày kết thúc gần nhất
                                        </a>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <!-- Thanh tìm kiếm + nút Load lại -->
                        <div class="w-full md:w-1/2 lg:w-1/3 flex items-center space-x-2">
                            <!-- Input tìm kiếm -->
                            <div class="relative flex-1">
                                <input type="text" wire:model="search" placeholder="Tìm kiếm..."
                                    class="fi-input w-full" />
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 cursor-pointer"
                                    wire:click="searchQuizzes">
                                    <x-heroicon-s-magnifying-glass class="w-5 h-5 fi-color-primary-500" />
                                </div>
                            </div>

                            <!-- Nút Load lại -->
                            <button onclick="window.location.reload()"
                                class="fi-btn fi-btn-gray">
                                <x-heroicon-s-arrow-path class="w-6 h-6 animate-spin-slow" />
                            </button>
                        </div>


                    </div>


                    {{-- Quiz chưa làm --}}
                    <h2 class="text-lg font-bold text-gray-700 dark:text-gray-200 mb-3">Có thể làm</h2>
                    <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-6 mb-8">
                        @foreach ($this->ongoingQuizzes as $i => $quiz)
                            @php
                                $colors = [
                                    'from-pink-500 to-rose-500',
                                    'from-blue-500 to-indigo-500',
                                    'from-green-500 to-emerald-500',
                                    'from-purple-500 to-fuchsia-500',
                                    'from-orange-500 to-amber-500',
                                ];
                                $bg = $colors[$i % count($colors)];

                                $nowx = now();
                                $endAtx = $quiz->pivot && $quiz->pivot->end_at
                                    ? \Carbon\Carbon::parse($quiz->pivot->end_at)
                                    : now();
                                $diffx = $nowx->diff($endAtx);
                                $daysLeft = $diffx->days;
                                $hoursLeft = $diffx->h;      

                            @endphp

                            <div
                                class="group rounded-2xl shadow-xl overflow-hidden bg-white dark:bg-gray-800 transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl">
                                <!-- Nửa trên -->
                                <div class="h-32 flex items-center justify-center bg-gradient-to-r {{ $bg }} relative">
                                    <x-heroicon-o-academic-cap
                                        class="w-12 h-12 text-white opacity-90 transform transition duration-300 group-hover:scale-125 group-hover:rotate-12" />
                                </div>

                                <!-- Nửa dưới -->
                                <div class="p-5">
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-white mb-1 group-hover:text-indigo-500 transition">
                                        {{ $quiz->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">
                                        {!! \Illuminate\Support\Str::limit(strip_tags($quiz->description), 90) !!}
                                    </p>
                                    <span class="inline-flex items-center text-xs font-medium text-gray-500 dark:text-gray-400">
                                        <x-heroicon-s-clock class="w-4 h-4 mr-1 text-gray-400" />
                                        {{ $quiz->time_limit_minutes }} phút
                                    </span>
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        @foreach($quiz->tags as $tag)
                                            <span class="fi-badge fi-badge-primary">
                                                <x-heroicon-o-tag class="w-3 h-3 mr-1" />
                                                <span>{{ $tag->name }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                    <div class="mt-3">

                                        <div class="mt-3 flex flex-wrap gap-2 items-center">
                                            <div
                                                class="flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-green-400 to-blue-500 text-white font-semibold shadow-md animate-pulse">
                                                <x-heroicon-o-calendar class="w-4 h-4 mr-1" />
                                                {{ $daysLeft }} ngày
                                            </div>

                                            <div
                                                class="flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-semibold shadow-md animate-pulse">
                                                <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                                {{ $hoursLeft }} giờ
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Gạch phân cách --}}
                    <div class="col-span-full my-6 flex items-center">
                        <div class="flex items-center space-x-2">
                            <x-heroicon-o-academic-cap class="w-6 h-6 text-green-600" />
                            <span
                                class="fi-badge fi-badge-success">
                                Đã kết thúc/hoàn tất
                            </span>
                        </div>


                        <div
                            class="flex-1 h-1 ml-4 rounded-full bg-gradient-to-r from-green-400 via-gray-300 to-gray-400 opacity-80 dark:from-green-600 dark:via-gray-700 dark:to-gray-800">
                        </div>
                    </div>



                    {{-- Quiz đã làm --}}
                    <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-6">
                        @foreach ($this->completedQuizzes as $i => $quiz)
                            @php
                                $colors = [
                                    'from-pink-500 to-rose-500',
                                    'from-blue-500 to-indigo-500',
                                    'from-green-500 to-emerald-500',
                                    'from-purple-500 to-fuchsia-500',
                                    'from-orange-500 to-amber-500',
                                ];

                                $bg = $colors[$i % count($colors)];
                                $now = now();
                                $endAt = $quiz->pivot->end_at ?? $now;
                                $diff = $now->diff($endAt);
                                $daysLeft = $diff->days;
                                $hoursLeft = $diff->h;

                                // Lấy lần làm bài gần nhất của sinh viên hiện tại
                                $latestAttempt = $quiz->attempts()
                                    ->where('student_id', auth()->id())
                                    ->latest('created_at')
                                    ->first();

                                $statusLabel = '';
                                $statusColor = 'text-gray-500'; // Màu mặc định
                                $statusIcon = 'heroicon-o-question-mark-circle'; // Icon mặc định

                                // Logic xác định trạng thái để hiển thị
                                if (
                                    $latestAttempt && in_array($latestAttempt->status, [
                                        \App\Enums\Status\QuizAttemptStatus::COMPLETED,
                                        \App\Enums\Status\QuizAttemptStatus::GRADED
                                    ])
                                ) {
                                    $statusLabel = 'Đã hoàn thành';
                                    $statusColor = 'text-green-500';
                                    $statusIcon = 'heroicon-o-check-circle';
                                } elseif ($endAt && $now->gt($endAt) && !$latestAttempt) {
                                    $statusLabel = 'Đã kết thúc';
                                    $statusColor = 'text-red-500';
                                    $statusIcon = 'heroicon-o-x-circle';
                                } elseif ($latestAttempt) {
                                    // Các trường hợp khác như ABANDONED, IN_PROGRESS
                                    $statusLabel = 'Đã làm';
                                    $statusColor = 'text-yellow-500';
                                    $statusIcon = 'heroicon-o-pencil';
                                } else {
                                    $statusLabel = 'Đã đóng'; // Trường hợp quiz đóng mà không có lần làm bài
                                    $statusColor = 'text-gray-400';
                                    $statusIcon = 'heroicon-o-lock-closed';
                                }
                            @endphp

                            <div
                                class="group rounded-2xl shadow-xl overflow-hidden bg-gray-100 dark:bg-gray-700 opacity-80 transform transition duration-300 hover:-translate-y-1 hover:shadow-lg cursor-not-allowed opacity-70 pointer-events">
                                <!-- Nửa trên -->
                                <div class="h-32 flex items-center justify-center bg-gradient-to-r {{ $bg }} relative">
                                    <x-heroicon-o-academic-cap
                                        class="w-12 h-12 text-white opacity-90 transform transition duration-300 group-hover:scale-125 group-hover:rotate-12" />
                                </div>

                                <!-- Nửa dưới -->
                                <div class="p-5">
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-white mb-1 group-hover:text-green-500 transition">
                                        {{ $quiz->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">
                                        {!! \Illuminate\Support\Str::limit(strip_tags($quiz->description), 90) !!}
                                    </p>
                                    @if ($statusLabel)
                                        <span
                                            class="inline-flex items-center text-xs font-medium {{ $statusColor }} dark:text-gray-400 space-x-1">
                                            <x-dynamic-component :component="$statusIcon" class="w-4 h-4" />
                                            <span>{{ $statusLabel }}</span>
                                        </span>
                                    @endif
                                    <div class="flex flex-wrap gap-2"> <!-- Thêm flex-wrap và gap -->
                                        @foreach($quiz->tags as $tag)
                                            <span class="fi-badge fi-badge-primary">
                                                <x-heroicon-o-tag class="w-3 h-3 mr-1" />
                                                <span>{{ $tag->name }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2 items-center">
                                        <div
                                            class="flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-green-400 to-blue-500 text-white font-semibold shadow-md animate-pulse">
                                            <x-heroicon-o-calendar class="w-4 h-4 mr-1" />
                                            {{ $daysLeft }} ngày
                                        </div>

                                        <div
                                            class="flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-semibold shadow-md animate-pulse">
                                            <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                            {{ $hoursLeft }} giờ
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div>

                <!-- Assignments -->
                <div x-show="activeTab === 'assignments'"
                    class="mb-6 flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-3">
                    <!-- 2 Nút Bộ Lọc & Sắp Xếp bên trái -->
                    <div class="flex space-x-3">

                        <!-- Nút Bộ Lọc Tag -->
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button @click="open = !open"
                                class="fi-btn fi-btn-success">
                                Bộ lọc Tag
                                <svg class="ml-2 h-5 w-5 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Tag -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="fi-dropdown-panel absolute left-0 mt-2 w-56 rounded-lg shadow-lg z-20">

                                <div class="py-1 max-h-60 overflow-y-auto">
                                    <!-- Tất cả tag -->
                                    <a href="#" wire:click.prevent="filterAssignmentsByTag(null)"
                                        class="fi-dropdown-list-item">
                                        Tất cả tag
                                    </a>

                                    <!-- Lặp qua danh sách tag -->
                                    @foreach($this->courseTags as $tag)
                                        <a href="#" wire:click.prevent="filterAssignmentsByTag('{{ $tag }}')"
                                            class="fi-dropdown-list-item">
                                            {{ $tag }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>





                        <!-- Nút Sắp Xếp -->
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button @click="open = !open"
                                class="fi-btn fi-btn-info">
                                Sắp xếp
                                <svg class="ml-2 h-5 w-5 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Sắp Xếp -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="fi-dropdown-panel absolute left-0 mt-2 w-56 rounded-lg shadow-lg z-20">
                                <div class="py-1">
                                    <a href="#" wire:click.prevent="sortAssignments('newest')"
                                        class="fi-dropdown-list-item">Mới
                                        nhất</a>
                                    <a href="#" wire:click.prevent="sortAssignments('oldest')"
                                        class="fi-dropdown-list-item">Cũ
                                        nhất</a>
                                    <a href="#" wire:click.prevent="sortAssignments('end_at')"
                                        class="fi-dropdown-list-item">Ngày
                                        kết thúc gần nhất</a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Thanh tìm kiếm + nút Load lại -->
                    <div class="w-full md:w-1/2 lg:w-1/3 flex items-center space-x-2">
                        <!-- Input tìm kiếm -->
                        <div class="relative flex-1">
                            <input type="text" wire:model="search" placeholder="Tìm kiếm..."
                                class="fi-input w-full" />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 cursor-pointer"
                                wire:click="searchAssignments">
                                <x-heroicon-s-magnifying-glass class="w-5 h-5 fi-color-primary-500" />
                            </div>
                        </div>

                        <!-- Nút Load lại -->
                        <button onclick="window.location.reload()"
                            class="fi-btn fi-btn-gray">
                            <x-heroicon-s-arrow-path class="w-6 h-6 animate-spin-slow" />
                        </button>
                    </div>


                </div>
                <div x-show="activeTab === 'assignments'" id="assignments" role="tabpanel"
                    class="grid md:grid-cols-3 sm:grid-cols-2 gap-6 mt-6">

                    @foreach ($this->ongoingAssignments as $i => $assignment)
                        @php
                            $now = now();
                            $endAtx = $assignment->pivot && $assignment->pivot->end_at ? \Carbon\Carbon::parse($assignment->pivot->end_at) : $now;
                            $diff = $now->diff($endAtx);
                            $daysxLeft = $diff->days;
                            $hoursxLeft = $diff->h;
                            $colorsx = [
                                'from-pink-500 to-rose-500',
                                'from-blue-500 to-indigo-500',
                                'from-green-500 to-emerald-500',
                                'from-purple-500 to-fuchsia-500',
                                'from-orange-500 to-amber-500',
                            ];
                            $bgx = $colorsx[$i % count($colorsx)];

                        @endphp
                        <div
                            class="group rounded-2xl shadow-xl overflow-hidden bg-white dark:bg-gray-800
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl">

                            <!-- Nửa trên: gradient + icon -->
                            <div class="h-32 flex items-center justify-center bg-gradient-to-r {{ $bgx }} relative">
                                <x-heroicon-o-pencil-square
                                    class="w-12 h-12 text-white opacity-90 transform transition duration-300
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       group-hover:scale-125 group-hover:rotate-12" />
                            </div>

                            <!-- Nửa dưới: thông tin assignment -->
                            <div class="p-5">
                                <h3
                                    class="text-xl font-bold text-gray-900 dark:text-white mb-1
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       group-hover:text-indigo-500 transition">
                                    {{ $assignment->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($assignment->description), 90) !!}
                                </p>

                                <!-- Tags -->
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @foreach($assignment->tags as $tag)
                                        <span class="fi-badge fi-badge-primary">
                                            <x-heroicon-o-tag class="w-3 h-3 mr-1" />
                                            <span>{{ $tag->name }}</span>
                                        </span>
                                    @endforeach
                                </div>

                                <!-- Thời gian còn lại -->
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <div
                                        class="flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-green-400 to-blue-500
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            text-white font-semibold shadow-md animate-pulse">
                                        <x-heroicon-o-calendar class="w-4 h-4 mr-1" />
                                        {{ $daysxLeft }} ngày
                                    </div>

                                    <div
                                        class="flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            text-white font-semibold shadow-md animate-pulse">
                                        <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                        {{ $hoursxLeft }} giờ
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-span-full my-6 flex items-center">

                        <div class="flex items-center space-x-2">
                            <x-heroicon-o-academic-cap class="w-6 h-6 text-green-600" />
                            <span
                                class="fi-badge fi-badge-success">
                                Đã kết thúc/hoàn tất
                            </span>
                        </div>


                        <div
                            class="flex-1 h-1 ml-4 rounded-full bg-gradient-to-r from-green-400 via-gray-300 to-gray-400 opacity-80 dark:from-green-600 dark:via-gray-700 dark:to-gray-800">
                        </div>
                    </div>





                    {{-- Closed Assignments --}}
                    @foreach ($this->closedAssignments as $i => $closedassignment)
                        @php
                            $colorsx = [
                                'from-pink-500 to-rose-500',
                                'from-blue-500 to-indigo-500',
                                'from-green-500 to-emerald-500',
                                'from-purple-500 to-fuchsia-500',
                                'from-orange-500 to-amber-500',
                            ];
                            $bgx = $colorsx[$i % count($colorsx)];
                        @endphp

                        <div
                            class="group rounded-2xl shadow-xl overflow-hidden bg-gray-100 dark:bg-gray-700
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    transform transition duration-300 cursor-not-allowed opacity-70">
                            <div class="h-32 flex items-center justify-center bg-gradient-to-r {{ $bgx }} relative">
                                <x-heroicon-o-lock-closed
                                    class="w-12 h-12 text-white opacity-90 transform transition duration-300" />
                            </div>

                            <div class="p-5">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">
                                    {{ $closedassignment->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($closedassignment->description), 90) !!}
                                </p>

                                <div class="flex flex-wrap gap-2 mt-3">
                                    @foreach($closedassignment->tags as $tag)
                                        <span class="fi-badge fi-badge-gray">
                                            <x-heroicon-o-tag class="w-3 h-3 mr-1" />
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <div class="flex flex-wrap gap-2 mt-3 text-sm text-gray-500 dark:text-gray-400">
                                    Đã đóng
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
                <!-- Documents -->
                <div x-show="activeTab === 'document'" id="document" role="tabpanel"
                    class="grid md:grid-cols-3 sm:grid-cols-2 gap-6 mt-6">
                    @forelse ($this->documents as $doc)
                        <div
                            class="group rounded-2xl shadow-xl overflow-hidden bg-white dark:bg-gray-800 transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl">
                            <!-- Nửa trên: icon/document -->
                            <div
                                class="h-32 flex items-center justify-center bg-gradient-to-r from-sky-500 to-cyan-500 relative">
                                <x-heroicon-o-document-text
                                    class="w-12 h-12 text-white opacity-90 transform transition duration-300 group-hover:scale-125 group-hover:rotate-6" />

                            </div>

                            <!-- Nửa dưới -->
                            <div class="p-5 flex flex-col justify-between h-40">
                                <div>
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-white mb-1 group-hover:text-sky-500 transition">
                                        {{ $doc->name }}
                                    </h3>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ $doc->getUrl() }}" download
                                        class="fi-btn fi-btn-info">
                                        <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
                                        <span>Tải về</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="fi-section col-span-full flex flex-col items-center justify-center rounded-xl p-12 text-center">
                            <div
                                class="flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-800 mb-6">
                                <svg class="w-10 h-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7h4l2 3h12v11H3V7z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                Chưa có tài liệu nào
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Giáo viên chưa đăng tài liệu cho lớp học này.
                            </p>
                        </div>

                    @endforelse
                </div>
            </div>
        </x-tabs-nav>
    @else
        <div>Loading course data...</div>
    @endif
</x-filament-panels::page>