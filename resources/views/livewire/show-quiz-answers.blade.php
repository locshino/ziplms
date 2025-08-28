<div>
    {{-- Statistics Widget Section --}}
    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
        {{-- Correct Answers --}}
        <div wire:click="toggleFilter('correct')"
            class="p-4 bg-green-100 border-l-4 border-green-500 rounded-lg shadow-sm cursor-pointer transition-all duration-200 dark:bg-green-900/50 dark:border-green-400 @if ($filterStatus === 'correct') ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-gray-800 @endif">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Đúng</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $correctCount }}</p>
                </div>
            </div>
        </div>

        {{-- Partially Correct Answers --}}
        <div wire:click="toggleFilter('partially_correct')"
            class="p-4 bg-yellow-100 border-l-4 border-yellow-500 rounded-lg shadow-sm cursor-pointer transition-all duration-200 dark:bg-yellow-900/50 dark:border-yellow-400 @if ($filterStatus === 'partially_correct') ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-gray-800 @endif">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Chưa đủ</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $partiallyCorrectCount }}</p>
                </div>
            </div>
        </div>

        {{-- Incorrect Answers --}}
        <div wire:click="toggleFilter('incorrect')"
            class="p-4 bg-red-100 border-l-4 border-red-500 rounded-lg shadow-sm cursor-pointer transition-all duration-200 dark:bg-red-900/50 dark:border-red-400 @if ($filterStatus === 'incorrect') ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-gray-800 @endif">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Sai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $incorrectCount }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Answers List --}}
    <div class="space-y-4">
        @forelse ($processedAnswers as $processedAnswer)
            @php
                // Unifying colors between widgets and answer cards.
                $statusClasses = [
                    'correct' => 'bg-green-100 border-l-4 border-green-500 dark:bg-green-900/50 dark:border-green-400',
                    'partially_correct' =>
                        'bg-yellow-100 border-l-4 border-yellow-500 dark:bg-yellow-900/50 dark:border-yellow-400',
                    'incorrect' => 'bg-red-100 border-l-4 border-red-500 dark:bg-red-900/50 dark:border-red-400',
                ];
                $status = $processedAnswer['status'];
            @endphp
            <div class="{{ $statusClasses[$status] }} rounded-lg shadow-sm">
                <div class="p-4 sm:p-6">
                    {{-- Question Section --}}
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Câu hỏi
                        </h3>
                        <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                            {{ $processedAnswer['question_text'] }}
                        </p>
                    </div>

                    {{-- Divider --}}
                    <div class="my-4 border-t border-gray-200 dark:border-gray-700/50"></div>

                    {{-- Answer Section --}}
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Câu trả lời của học sinh
                        </h3>
                        <div class="mt-2 text-gray-900 dark:text-white">
                            @if (is_array($processedAnswer['answer_text']))
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($processedAnswer['answer_text'] as $answerText)
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-500/20 dark:text-blue-300">
                                            {{ $answerText }}
                                        </span>
                                    @endforeach
                                </div>
                            @elseif (!empty($processedAnswer['answer_text']))
                                <p>{{ $processedAnswer['answer_text'] }}</p>
                            @else
                                <p class="italic text-gray-500 dark:text-gray-400">Không trả lời</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="w-full px-6 py-4 text-center text-gray-500 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                Không có câu trả lời nào phù hợp với bộ lọc.
            </div>
        @endforelse
    </div>

    {{-- Pagination and Per Page Controls --}}
    @if ($processedAnswers->hasPages())
        <div
            class="flex items-center justify-between p-4 mt-4 border-t border-gray-200 rounded-b-xl dark:border-gray-700">
            <div>
                {{ $processedAnswers->links() }}
            </div>
            <div
                class="flex items-center space-x-2 text-sm px-2 bg-white border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                <select id="perPage" wire:model.live="perPage" class="h-8 pr-8 text-sm leading-none dark:bg-gray-700">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
</div>
