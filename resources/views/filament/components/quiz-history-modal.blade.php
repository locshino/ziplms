<div class="p-6 bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-900 dark:to-blue-900 min-h-[500px]">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full p-3 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Lịch sử làm bài
                </h3>
                <p class="text-lg text-blue-600 dark:text-blue-400 font-medium">{{ $quiz->title }}</p>
            </div>
        </div>

    </div>

    @if($attempts->count() > 0)
        <!-- Statistics Cards -->
        @php
            $bestAttempt = $attempts->sortByDesc('points')->first();
            $maxScore = $quiz->questions->sum('pivot.points');
            $bestPercentage = $maxScore > 0 ? ($bestAttempt->points / $maxScore) * 100 : 0;
            $bestPercentage = min($bestPercentage, 100);
            $avgScore = $attempts->avg('points');
            $avgPercentage = $maxScore > 0 ? ($avgScore / $maxScore) * 100 : 0;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div
                class="bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-xl p-4 border border-emerald-200 dark:border-emerald-700 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-500 rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Điểm cao nhất</p>
                        <p class="text-xl font-bold text-emerald-800 dark:text-emerald-200">
                            {{ number_format($bestPercentage, 1) }}%</p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400">
                            {{ number_format($bestAttempt->points, 1) }}/{{ $maxScore }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl p-4 border border-blue-200 dark:border-blue-700 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-500 rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Điểm trung bình</p>
                        <p class="text-xl font-bold text-blue-800 dark:text-blue-200">
                            {{ number_format($avgPercentage, 1) }}%</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400">
                            {{ number_format($avgScore, 1) }}/{{ $maxScore }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-xl p-4 border border-purple-200 dark:border-purple-700 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="bg-purple-500 rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Tổng lần làm</p>
                        <p class="text-xl font-bold text-purple-800 dark:text-purple-200">{{ $attempts->count() }}</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400">lần</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attempts List -->
        <div class="space-y-4">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd" />
                </svg>
                Chi tiết từng lần làm bài
            </h4>

            @foreach($attempts as $index => $attempt)
                @php
                    $maxScore = $quiz->questions->sum('pivot.points');
                    $percentage = $maxScore > 0 ? ($attempt->points / $maxScore) * 100 : 0;
                    $percentage = min($percentage, 100);
                    $duration = $attempt->start_at && $attempt->end_at
                        ? $attempt->start_at->diffInMinutes($attempt->end_at)
                        : 0;
                    $isBest = $attempt->id === $bestAttempt->id;
                @endphp

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border {{ $isBest ? 'border-emerald-300 dark:border-emerald-600 ring-2 ring-emerald-200 dark:ring-emerald-700' : 'border-gray-200 dark:border-gray-700' }} p-6 hover:shadow-md transition-all duration-200">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <!-- Left Section -->
                        <div class="flex items-center gap-4">
                            <div
                                class="{{ $isBest ? 'bg-gradient-to-r from-emerald-500 to-emerald-600' : 'bg-gradient-to-r from-blue-500 to-blue-600' }} rounded-full w-12 h-12 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                #{{ $attempts->count() - $index }}
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h5 class="font-semibold text-gray-900 dark:text-white">Lần thử
                                        {{ $attempts->count() - $index }}</h5>
                                    @if($isBest)
                                        <span
                                            class="bg-gradient-to-r from-emerald-100 to-emerald-200 dark:from-emerald-900/50 dark:to-emerald-800/50 text-emerald-800 dark:text-emerald-200 text-xs font-bold px-2 py-1 rounded-full border border-emerald-300 dark:border-emerald-600">
                                            <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            Điểm cao nhất
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Điểm:</span>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ number_format($attempt->points, 1) }}/{{ $maxScore }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Tỷ lệ:</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                            @if($percentage >= 80) bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                                            @elseif($percentage >= 60) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                                            @else bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200
                                                            @endif">
                                            {{ number_format($percentage, 1) }}%
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Thời gian:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ round($duration) }}
                                            phút</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-400">Ngày:</span>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $attempt->end_at ? $attempt->end_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('filament.app.pages.quiz-results', ['attempt' => $attempt->id]) }}"
                                class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium px-4 py-2 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Xem kết quả
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Chưa có lịch sử làm bài</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bạn chưa hoàn thành bài quiz này lần nào.</p>
        </div>
    @endif
</div>
