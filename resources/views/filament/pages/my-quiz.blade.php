<x-filament-panels::page>

    <div class="min-h-screen bg-slate-100 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 py-8">

            <!-- Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Tổng số Quiz</p>
                    <p class="mt-1 text-3xl font-bold tracking-tight text-blue-500 dark:text-blue-400">
                        {{ $this->getQuizzes()->count() }}
                    </p>
                </div>
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Quiz đã hoàn thành</p>
                    <p class="mt-1 text-3xl font-bold tracking-tight text-green-600 dark:text-green-400">
                        {{ $this->getCompletedQuizzesCount() }}
                    </p>
                </div>
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Kết quả học tập</p>
                    <p class="mt-1 text-3xl font-bold tracking-tight 
                        @if($this->getAveragePercentage() >= 90) text-green-500 dark:text-green-400
                        @elseif($this->getAveragePercentage() >= 80) text-blue-500 dark:text-blue-400
                        @elseif($this->getAveragePercentage() >= 70) text-amber-500 dark:text-amber-400
                        @elseif($this->getAveragePercentage() >= 60) text-orange-500 dark:text-orange-400
                        @else text-red-500 dark:text-red-400
                        @endif">
                        @if($this->getAveragePercentage() >= 90)
                            Xuất sắc
                        @elseif($this->getAveragePercentage() >= 80)
                            Giỏi
                        @elseif($this->getAveragePercentage() >= 70)
                            Khá
                        @elseif($this->getAveragePercentage() >= 60)
                            Trung bình
                        @else
                            Cần cải thiện
                        @endif
                    </p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                        Điểm cao nhất: {{ $this->getHighestScore() }}%
                    </p>
                </div>
            </div>

            <!-- Filter Bar -->
            <div
                class="mb-8 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="flex items-center gap-3">
                        <label class="text-sm font-medium text-slate-800 dark:text-slate-100">Lọc theo khóa học:</label>
                        <div class="flex-grow min-w-[200px]">
                            <select wire:model.live="selectedCourseId"
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-3 py-2">
                                <option value="">Tất cả khóa học</option>
                                @foreach ($this->getUserCourses() as $course)
                                    <option value="{{ $course->id }}">{{ $course->title ?? $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="text-sm font-medium text-slate-800 dark:text-slate-100">Lọc theo trạng
                            thái:</label>
                        <div class="flex-grow min-w-[200px]">
                            <select wire:model.live="selectedStatus"
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-3 py-2">
                                <option value="">Tất cả trạng thái</option>
                                @if ($this->listQuizStatus)
                                    @foreach ($this->listQuizStatus as $status)
                                        <option value="{{ $status['value'] }}">{{ $status['label'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    @if ($selectedCourseId || $selectedStatus)
                        <button wire:click="$set('selectedCourseId', null); $set('selectedStatus', null)"
                            class="px-3 py-2 text-sm bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg border-none cursor-pointer transition-colors hover:bg-slate-200 dark:hover:bg-slate-600">
                            Xóa tất cả bộ lọc
                        </button>
                    @endif
                </div>
            </div>

            <!-- Quiz List -->
            <div class="grid grid-cols-1 gap-6">
                @forelse($this->getQuizzes() as $quiz)
                    <div
                        class="flex flex-col lg:flex-row lg:items-stretch bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 ease-in-out hover:shadow-xl hover:-translate-y-1">
                        <!-- Main Info Section -->
                        <div class="p-6 flex-grow lg:border-r lg:border-slate-200 lg:dark:border-slate-700">
                            <p
                                class="inline-block bg-blue-50 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                                @php
                                    $course = $quiz->courses->count() ? $quiz->courses->first() : null;
                                @endphp
                                Khóa học:
                                @if($course)
                                    {{ $course->title ?? $course->name }}
                                @else
                                    <span class="text-red-500">Không xác định</span>
                                @endif
                            </p>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4 leading-tight">
                                {{ $quiz->title }}
                            </h3>
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                                    <x-heroicon-o-question-mark-circle class="w-5 h-5 text-blue-500 dark:text-blue-400" />
                                    <span class="font-medium">{{ $quiz->questions->count() }} câu hỏi</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                                    <x-heroicon-o-clock class="w-5 h-5 text-green-600 dark:text-green-400" />
                                    <span class="font-medium">{{ $quiz->time_limit_minutes ?? 'Không giới hạn' }}
                                        phút</span>
                                </div>
                                
                                @php
                                    $userCourse = $quiz->courses->first(); // Get first course user is enrolled in
                                    $courseQuiz = $userCourse ? $userCourse->pivot : null;
                                @endphp
                                
                                @if($courseQuiz && ($courseQuiz->start_at || $courseQuiz->end_at))
                                    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                                        <x-heroicon-o-calendar class="w-5 h-5 text-purple-500 dark:text-purple-400" />
                                        <span class="font-medium">
                                            @if($courseQuiz->start_at && $courseQuiz->end_at)
                                                {{ $courseQuiz->start_at->format('d/m/Y H:i') }} - {{ $courseQuiz->end_at->format('d/m/Y H:i') }}
                                            @elseif($courseQuiz->start_at)
                                                Từ {{ $courseQuiz->start_at->format('d/m/Y H:i') }}
                                            @elseif($courseQuiz->end_at)
                                                Đến {{ $courseQuiz->end_at->format('d/m/Y H:i') }}
                                            @endif
                                        </span>
                                    </div>
                                @endif

                            </div>
                        </div>

                        <!-- Meta & Actions Section -->
                        <div
                            class="p-6 bg-slate-50 dark:bg-slate-900 border-t lg:border-t-0 border-slate-200 dark:border-slate-700 flex-shrink-0 lg:w-80">
                            @php
                                $quizStatus = $this->getQuizStatus($quiz);
                                $bestScore = $this->getStudentQuizBestScore($quiz);
                                $completedAttempts = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
                                    ->where('student_id', Auth::id())
                                    ->whereIn('status', ['completed', 'submitted'])
                                    ->get();
                            @endphp

                            @if ($completedAttempts->count() > 0)
                                <div class="mb-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <x-heroicon-o-check-circle class="w-4 h-4 text-emerald-500 flex-shrink-0" />
                                        <span class="text-sm font-medium text-slate-800 dark:text-slate-100">Đã hoàn
                                            thành {{ $completedAttempts->count() }} lần</span>
                                    </div>
                                    @if (!is_null($bestScore))
                                        <div class="flex items-center gap-2">
                                            <x-heroicon-o-star class="w-4 h-4 text-amber-500 flex-shrink-0" />
                                            <span class="text-sm font-medium text-slate-800 dark:text-slate-100">Điểm
                                                cao nhất: {{ number_format($bestScore, 1) }}%</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="flex flex-col lg:flex-row lg:justify-end gap-2">
                                @if ($quizStatus['status'] === 'in_progress')
                                    <a href="{{ route('filament.app.pages.quiz-taking', ['quiz' => $quiz->id]) }}"
                                        class="w-full lg:w-auto font-semibold px-4 py-3 rounded-lg transition-all duration-200 ease-in-out flex items-center justify-center gap-2 border border-transparent cursor-pointer no-underline hover:-translate-y-0.5 hover:shadow-lg bg-amber-500 text-white hover:bg-amber-600">
                                        <x-heroicon-o-play class="w-5 h-5" />
                                        <span>{{ $quizStatus['label'] }}</span>
                                    </a>
                                @elseif($quizStatus['canTake'])
                                    {{ ($this->takeQuizAction)(['quiz' => $quiz->id])->extraAttributes(['class' => 'w-full lg:w-auto font-semibold px-4 py-3 rounded-lg transition-all duration-200 ease-in-out flex items-center justify-center gap-2 border border-transparent cursor-pointer no-underline hover:-translate-y-0.5 hover:shadow-lg bg-green-600 text-white hover:bg-green-700']) }}
                                @endif

                                {{-- Ẩn nút "Xem đáp án" vì đã có chức năng xem lịch sử làm bài --}}
                                {{-- @if ($quizStatus['canViewResults'])
                                    {{ ($this->viewResultsAction)(['quiz' => $quiz->id])->extraAttributes(['class' => 'w-full lg:w-auto font-semibold px-4 py-3 rounded-lg transition-all duration-200 ease-in-out flex items-center justify-center gap-2 border border-transparent cursor-pointer no-underline hover:-translate-y-0.5 hover:shadow-lg bg-green-600 text-white hover:bg-green-700']) }}
                                @endif --}}

                                @if ($completedAttempts->count() > 1)
                                    {{ ($this->viewHistoryAction)(['quiz' => $quiz->id])->extraAttributes(['class' => 'w-full lg:w-auto font-semibold px-4 py-3 rounded-lg transition-all duration-200 ease-in-out flex items-center justify-center gap-2 border cursor-pointer no-underline hover:-translate-y-0.5 hover:shadow-lg bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-slate-500 dark:hover:border-slate-400 hover:text-slate-800 dark:hover:text-slate-100']) }}
                                @endif

                                @if (!$quizStatus['canTake'] && !$quizStatus['canViewResults'] && $quizStatus['status'] !== 'in_progress')
                                    <button disabled
                                        class="w-full lg:w-auto font-semibold px-4 py-3 rounded-lg transition-all duration-200 ease-in-out flex items-center justify-center gap-2 border border-transparent cursor-not-allowed bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500">
                                        <x-heroicon-o-lock-closed class="w-5 h-5" />
                                        <span>{{ $quizStatus['label'] }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div
                            class="mx-auto mb-6 w-24 h-24 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-2">Chưa có quiz nào</h3>
                        <p class="text-slate-500 dark:text-slate-400">Bạn chưa được gán vào khóa học nào có quiz hoặc
                            không có quiz nào khớp với bộ lọc.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
