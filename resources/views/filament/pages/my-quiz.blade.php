<x-filament-panels::page>

    <div class="min-h-screen bg-slate-100 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 py-8">

            <div
                class="mb-8 bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 flex flex-col gap-6">
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="flex items-center gap-3 flex-grow">
                        <label for="courseFilter" class="text-sm font-medium text-slate-800 dark:text-slate-100">Khóa
                            học:</label>
                        <div class="flex-grow min-w-[200px]">
                            <select id="courseFilter" wire:model.live="selectedCourseId"
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-3 py-2">
                                <option value="">Tất cả các khóa học</option>
                                @foreach($this->getUserCourses() as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-grow">
                        <label for="searchFilter" class="text-sm font-medium text-slate-800 dark:text-slate-100">Tìm
                            kiếm:</label>
                        <div class="flex-grow min-w-[200px]">
                            <input id="searchFilter" wire:model.live.debounce.300ms="searchTerm" type="search"
                                placeholder="Tìm tiêu đề quiz..."
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-3 py-2">
                        </div>
                    </div>
                </div>

                {{-- Filter Buttons --}}
                <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                    <div
                        class="p-1.5 flex items-center bg-slate-100 dark:bg-slate-900/50 rounded-full border border-slate-200 dark:border-slate-700">
                        @php
                            $filterNavs = [
                                ['key' => 'all', 'label' => 'Tất cả'],
                                ['key' => 'unsubmitted', 'label' => 'Chưa nộp'],
                                ['key' => 'overdue', 'label' => 'Quá hạn'],
                                ['key' => 'submitted', 'label' => 'Đã nộp'],
                                ['key' => 'retakeable', 'label' => 'Làm lại'],
                            ];
                        @endphp

                        @foreach ($filterNavs as $nav)
                                            <button type="button" wire:click="updateFilter('{{ $nav['key'] }}')" class="flex-1 text-center py-2 text-sm font-semibold rounded-full transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-100 dark:focus:ring-offset-slate-900 focus:ring-blue-500
                                                    {{ $selectedFilter === $nav['key']
                            ? 'bg-white dark:bg-slate-700 text-blue-500 shadow-sm'
                            : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'
                                                    }}">
                                                {{ $nav['label'] }}
                                            </button>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 gap-6">
                @forelse($this->getFilteredQuizzes() as $quiz)
                    @php
                        $quizStatus = $this->getQuizStatus($quiz);
                        $bestScore = $this->getStudentQuizBestScore($quiz);
                        $completedAttempts = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)->where('student_id', Auth::id())->whereIn('status', ['completed', 'submitted'])->get();
                        $isCompleted = $completedAttempts->count() > 0;
                        $courseQuiz = $quiz->courses->first() ? $quiz->courses->first()->pivot : null;
                        $isOverdue = $courseQuiz && $courseQuiz->end_at && $courseQuiz->end_at->isPast() && !$isCompleted;
                    @endphp
                    <div wire:key="{{ $quiz->id }}" class="flex flex-col lg:flex-row lg:items-stretch bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="flex-grow p-6 lg:border-r lg:border-slate-200 lg:dark:border-slate-700">
                            <div
                                class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                                {{ $quiz->courses->first()->title ?? 'Khóa học không xác định' }}
                            </div>

                            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4 leading-tight">
                                {{ $quiz->title }}</h3>
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                    <x-heroicon-o-question-mark-circle class="w-5 h-5 text-blue-500" />
                                    <span class="font-medium">{{ $quiz->questions->count() }} câu hỏi</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                    <x-heroicon-o-clock class="w-5 h-5 text-green-500" />
                                    <span class="font-medium">{{ $quiz->time_limit_minutes ?? 'Không giới hạn' }}
                                        phút</span>
                                </div>
                                @if($courseQuiz && ($courseQuiz->start_at || $courseQuiz->end_at))
                                    <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                        <x-heroicon-o-calendar-days class="w-5 h-5 text-red-500" />
                                        <span class="font-medium">
                                            @if($courseQuiz->start_at && $courseQuiz->end_at)
                                                Hạn: {{ $courseQuiz->end_at->format('d/m/Y H:i') }}
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

                        {{-- Right Side: Status & Actions --}}
                        <div class="p-6 lg:w-80 bg-slate-100 dark:bg-slate-900 border-t lg:border-t-0 lg:border-l border-slate-200 dark:border-slate-700 flex flex-col justify-between">
                            <div class="space-y-4">
                                @if($isCompleted)
                                    <div
                                        class="inline-flex items-center gap-2 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-sm font-semibold">
                                        <x-heroicon-o-check-circle class="w-4 h-4" />
                                        Đã nộp
                                    </div>
                                @elseif($isOverdue)
                                    <div
                                        class="inline-flex items-center gap-2 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-3 py-1 rounded-full text-sm font-semibold">
                                        <x-heroicon-o-exclamation-triangle class="w-4 h-4" />
                                        Quá hạn
                                    </div>
                                @else
                                    <div
                                        class="inline-flex items-center gap-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 px-3 py-1 rounded-full text-sm font-semibold">
                                        <x-heroicon-o-clock class="w-4 h-4" />
                                        Chưa nộp
                                    </div>
                                @endif

                                @if($isCompleted && $bestScore !== null)
                                    <div class="text-center">
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Điểm cao nhất</p>
                                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                            {{ number_format($bestScore, 1) }}%
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 space-y-2">
                                @if ($quizStatus['status'] === 'in_progress')
                                    <a href="{{ route('filament.app.pages.quiz-taking', ['quiz' => $quiz->id]) }}"
                                        class="w-full text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2"
                                        style="background-color: #f97316 !important; border-color: #f97316 !important;"
                                        onmouseover="this.style.backgroundColor='#ea580c'" 
                                        onmouseout="this.style.backgroundColor='#f97316'">
                                        <x-heroicon-o-play class="w-4 h-4" />
                                        Tiếp tục
                                    </a>
                                @elseif($quizStatus['canTake'])
                                    {{ ($this->takeQuizAction)(['quiz' => $quiz->id])->extraAttributes(['class' => 'w-full text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2', 'style' => 'background-color: #16a34a !important; border-color: #16a34a !important;', 'onmouseover' => 'this.style.backgroundColor="#15803d"', 'onmouseout' => 'this.style.backgroundColor="#16a34a"']) }}
                                @endif

                                @if ($completedAttempts->count() > 0)
                                    {{ ($this->viewHistoryAction)(['quiz' => $quiz->id])->extraAttributes(['class' => 'w-full text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2', 'style' => 'background-color: #4f46e5 !important; border-color: #4f46e5 !important;', 'onmouseover' => 'this.style.backgroundColor="#4338ca"', 'onmouseout' => 'this.style.backgroundColor="#4f46e5"']) }}
                                @endif

                                @if (!$quizStatus['canTake'] && !$quizStatus['canViewResults'] && $quizStatus['status'] !== 'in_progress')
                                    <div
                                        class="w-full text-gray-600 dark:text-gray-300 px-4 py-2 rounded-lg text-center text-sm font-medium cursor-not-allowed"
                                        style="background-color: #d1d5db !important;">
                                        <x-heroicon-o-lock-closed class="w-4 h-4 inline mr-1" />
                                        {{ $quizStatus['label'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 px-6 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg">
                        <div
                            class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-6">
                            <x-heroicon-o-document-text class="w-10 h-10 text-slate-400" />
                        </div>
                        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-200 mb-2">Không có quiz nào</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-center max-w-md text-sm">
                            Hiện tại không có quiz nào phù hợp với bộ lọc của bạn. Hãy thử điều chỉnh bộ lọc hoặc quay lại
                            sau.
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($this->getTotalPages() > 1)
                <div class="mt-8 bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700">
                    @php
                        $paginationInfo = $this->getPaginationInfo();
                    @endphp
                    
                    {{-- Pagination Info --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            Hiển thị <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['start_item'] }}</span> 
                            đến <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['end_item'] }}</span> 
                            trong tổng số <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['total_items'] }}</span> quiz
                        </div>
                        
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            Trang <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['current_page'] }}</span> 
                            / <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['total_pages'] }}</span>
                        </div>
                    </div>

                    {{-- Pagination Controls --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        {{-- Previous Button --}}
                        <button 
                            wire:click="previousPage" 
                            @if(!$paginationInfo['has_previous']) disabled @endif
                            class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 
                                   {{ $paginationInfo['has_previous'] 
                                      ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-md hover:shadow-lg' 
                                      : 'bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed' }}"
                        >
                            <x-heroicon-o-chevron-left class="w-4 h-4" />
                            Trang trước
                        </button>

                        {{-- Page Numbers --}}
                        <div class="flex items-center gap-2">
                            @php
                                $currentPage = $paginationInfo['current_page'];
                                $totalPages = $paginationInfo['total_pages'];
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($totalPages, $currentPage + 2);
                            @endphp

                            @if($startPage > 1)
                                <button wire:click="goToPage(1)" 
                                        class="w-10 h-10 text-sm font-medium rounded-lg transition-all duration-200 
                                               bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 
                                               text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-600">
                                    1
                                </button>
                                @if($startPage > 2)
                                    <span class="text-slate-400 dark:text-slate-500">...</span>
                                @endif
                            @endif

                            @for($page = $startPage; $page <= $endPage; $page++)
                                <button wire:click="goToPage({{ $page }})" 
                                        class="w-10 h-10 text-sm font-medium rounded-lg transition-all duration-200 
                                               {{ $page === $currentPage 
                                                  ? 'bg-blue-500 text-white shadow-md' 
                                                  : 'bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-600' }}">
                                    {{ $page }}
                                </button>
                            @endfor

                            @if($endPage < $totalPages)
                                @if($endPage < $totalPages - 1)
                                    <span class="text-slate-400 dark:text-slate-500">...</span>
                                @endif
                                <button wire:click="goToPage({{ $totalPages }})" 
                                        class="w-10 h-10 text-sm font-medium rounded-lg transition-all duration-200 
                                               bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 
                                               text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-600">
                                    {{ $totalPages }}
                                </button>
                            @endif
                        </div>

                        {{-- Next Button --}}
                        <button 
                            wire:click="nextPage" 
                            @if(!$paginationInfo['has_next']) disabled @endif
                            class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 
                                   {{ $paginationInfo['has_next'] 
                                      ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-md hover:shadow-lg' 
                                      : 'bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed' }}"
                        >
                            Trang sau
                            <x-heroicon-o-chevron-right class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-filament-panels::page>
