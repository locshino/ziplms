<div class="p-6">
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Lịch sử làm bài: {{ $quiz->title }}
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Hiển thị tất cả các lần làm bài đã hoàn thành
        </p>
    </div>

    @if($attempts->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Lần thứ
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Điểm số
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Phần trăm
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Thời gian làm bài
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Ngày hoàn thành
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($attempts as $index => $attempt)
                        @php
                            $maxScore = $quiz->questions->sum('pivot.points');
                            $percentage = $maxScore > 0 ? ($attempt->points / $maxScore) * 100 : 0;
                            // Ensure percentage doesn't exceed 100%
                            $percentage = min($percentage, 100);
                            $duration = $attempt->start_at && $attempt->end_at
                                ? $attempt->start_at->diffInMinutes($attempt->end_at)
                                : 0;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $attempts->count() - $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($attempt->points, 2) }}/{{ $maxScore }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($percentage >= 80) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($percentage >= 60) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @endif">
                                    {{ number_format($percentage, 2) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $duration }} phút
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $attempt->end_at ? $attempt->end_at->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('filament.app.pages.quiz-results', ['attempt' => $attempt->id]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Xem kết quả
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            <p>Tổng số lần làm bài: <strong>{{ $attempts->count() }}</strong></p>
            @php
                $bestAttempt = $attempts->sortByDesc('points')->first();
                $maxScore = $quiz->questions->sum('pivot.points');
                $bestPercentage = $maxScore > 0 ? ($bestAttempt->points / $maxScore) * 100 : 0;
                // Ensure percentage doesn't exceed 100%
                $bestPercentage = min($bestPercentage, 100);
            @endphp
            <p>Điểm cao nhất: <strong>{{ number_format($bestAttempt->points, 2) }}/{{ $maxScore }}
                    ({{ number_format($bestPercentage, 2) }}%)</strong></p>
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
