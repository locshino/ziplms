<x-filament-panels::page>
    @if($quiz)
        <div class="w-full mx-auto p-4 flex flex-col gap-6 sm:p-6 lg:p-8 lg:gap-8">

            <!-- [START] Header Section -->
            <div class="bg-white dark:bg-gray-800/80 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center">
                    <div class="flex-1">
                        <p class="text-lg font-medium text-indigo-600 dark:text-indigo-400">{{ $this->getSubheading() }}</p>

                        <!-- Attempt Selection Dropdown -->
                         @if($allAttempts->count() > 1)
                         <div class="mt-4">
                             <label for="attempt-select" class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Chọn lần làm bài:</label>
                             <select id="attempt-select" wire:change="selectAttempt($event.target.value)" class="p-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 min-w-[200px] text-sm text-gray-700 dark:text-gray-200">
                                 @foreach($allAttempts as $index => $attemptItem)
                                     <option value="{{ $attemptItem->id }}" {{ $attemptItem->id == $selectedAttemptId ? 'selected' : '' }}>
                                         Lần {{ $index + 1 }} - {{ $attemptItem->created_at ? $attemptItem->created_at->format('d/m/Y H:i') : 'N/A' }}
                                         ({{ $attemptItem->points ?? 0 }}/{{ $quiz->questions->sum('pivot.points') ?? 0 }} điểm)
                                     </option>
                                 @endforeach
                             </select>
                         </div>
                         @endif
                    </div>
                    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:gap-6">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm">
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm">
                            <x-heroicon-s-calendar class="w-5 h-5 text-gray-400" />
                            <span><strong class="font-semibold text-gray-900 dark:text-white">Hoàn thành trong:</strong> {{ $duration }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm">
                            <x-heroicon-s-check-circle class="w-5 h-5 text-gray-400" />
                            <span><strong class="font-semibold text-gray-900 dark:text-white">Hoàn thành lúc:</strong> {{ $completedAt }}</span>
                        </div>
                    </div>
                    <a href="{{ route('filament.app.pages.my-quiz') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg font-semibold text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors border border-gray-200 dark:border-gray-600 no-underline">
                        <x-heroicon-s-arrow-left class="w-5 h-5" />
                        <span>Quay lại</span>
                    </a>
                </div>
            </div>
            <!-- [END] Header Section -->
            <!-- [START] Stats Overview Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                <!-- Score Card -->
                <div class="bg-white dark:bg-gray-800/80 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 text-center transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="mx-auto mb-4 w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <x-heroicon-o-star class="w-7 h-7" />
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tổng điểm</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white leading-tight">{{ $score }}/{{ $maxScore }}</div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">({{ $percentage }}%)</div>
                    </div>
                </div>
                <!-- Correct Answers Card -->
                <div class="bg-white dark:bg-gray-800/80 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 text-center transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="mx-auto mb-4 w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/20 flex items-center justify-content-center text-green-600 dark:text-green-400">
                        <x-heroicon-o-check-circle class="w-7 h-7" />
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Câu đúng</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white leading-tight">{{ $correctCount }}</div>
                    </div>
                </div>
                <!-- Wrong Answers Card -->
                <div class="bg-white dark:bg-gray-800/80 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 text-center transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="mx-auto mb-4 w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/20 flex items-center justify-center text-red-600 dark:text-red-400">
                        <x-heroicon-o-x-circle class="w-7 h-7" />
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Câu sai</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white leading-tight">{{ $wrongCount }}</div>
                    </div>
                </div>
                 <!-- Unanswered Card -->
                <div class="bg-white dark:bg-gray-800/80 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 text-center transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="mx-auto mb-4 w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400">
                        <x-heroicon-o-question-mark-circle class="w-7 h-7" />
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Chưa trả lời</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white leading-tight">{{ $unansweredCount }}</div>
                    </div>
                </div>
            </div>
            <!-- [END] Stats Overview Section -->

            <!-- [START] Detailed Answers Section -->
            <div class="bg-white dark:bg-gray-800/80 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Đáp án chi tiết</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Xem lại từng câu hỏi, câu trả lời của bạn và đáp án đúng.</p>

                <!-- Livewire Filter Buttons -->
                <div class="flex justify-center border-b border-gray-200 dark:border-gray-700 mb-8">
                    <div class="flex gap-2 -mb-px">
                        @php
                            $filters = [
                                'all' => ['label' => 'Tất cả', 'icon' => 'heroicon-o-list-bullet'],
                                'correct' => ['label' => 'Đúng', 'icon' => 'heroicon-o-check-circle'],
                                'wrong' => ['label' => 'Sai', 'icon' => 'heroicon-o-x-circle'],
                                'unanswered' => ['label' => 'Chưa trả lời', 'icon' => 'heroicon-o-question-mark-circle'],
                            ];
                        @endphp
                        @foreach ($filters as $key => $f)
                            <button
                                type="button"
                                wire:click="setFilter('{{ $key }}')"
                                class="inline-flex items-center gap-2 px-4 py-3 text-sm font-semibold border-b-2 transition-all duration-200 cursor-pointer {{ $filter === $key ? 'border-indigo-600 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-600 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200' }} bg-transparent"
                            >
                                <x-dynamic-component :component="$f['icon']" class="w-5 h-5" />
                                <span>{{ $f['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Answers List -->
                <div class="flex flex-col gap-6">
                    @forelse($results as $index => $result)
                        @if($filter === 'all' || $filter === $result['status'])
                            <div wire:key="question-{{ $result['question']->id }}" class="bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                                <!-- Question Header -->
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full font-bold">
                                        {{ $index + 1 }}
                                    </div>
                                    <p class="flex-1 text-lg font-semibold text-gray-900 dark:text-white leading-relaxed">{!! $result['question']->question_text !!}</p>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($result['status'] === 'correct') bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400
                                        @elseif($result['status'] === 'wrong') bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-400
                                        @else bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 @endif">
                                        @if($result['status'] === 'correct') <x-heroicon-s-check-circle class="w-4 h-4" /> Đúng @endif
                                        @if($result['status'] === 'wrong') <x-heroicon-s-x-circle class="w-4 h-4" /> Sai @endif
                                        @if($result['status'] === 'unanswered') Chưa trả lời @endif
                                    </span>
                                </div>

                                <!-- Answer Choices -->
                                <div class="pl-14 flex flex-col gap-3">
                                    @foreach($result['question']->answerChoices as $choiceIndex => $choice)
                                        @php
                                            $userChoiceIds = $result['user_answers']->pluck('answer_choice_id')->toArray();
                                            // Debug: uncomment to see user choices
                                            // dd('Question ID: ' . $result['question']->id, 'User Choice IDs: ', $userChoiceIds, 'Current Choice ID: ' . $choice->id);
                                            $isUserChoice = in_array($choice->id, $userChoiceIds);
                                            $isCorrectChoice = $choice->is_correct;
                                            $choiceLabel = chr(65 + $choiceIndex);
                                            $choiceClass = '';
                                            if ($isCorrectChoice && $isUserChoice)
                                                $choiceClass = 'border-green-600 bg-green-50 dark:bg-green-900/15'; // Correct and selected
                                            elseif ($isCorrectChoice && !$isUserChoice)
                                                $choiceClass = 'border-green-400 bg-green-25 dark:bg-green-900/10'; // Correct but not selected
                                            elseif (!$isCorrectChoice && $isUserChoice)
                                                $choiceClass = 'border-red-600 bg-red-50 dark:bg-red-900/15'; // Incorrect but selected
                                            else
                                                $choiceClass = 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/50'; // Not selected
                                        @endphp
                                        <div class="flex items-start gap-3 p-3 rounded-lg border-2 {{ $choiceClass }}">
                                            <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold
                                                @if($isCorrectChoice) bg-green-200 dark:bg-green-600 text-green-800 dark:text-white
                                                @elseif($isUserChoice) bg-red-200 dark:bg-red-600 text-red-800 dark:text-white
                                                @else bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 @endif">
                                                {{ $choiceLabel }}
                                            </div>
                                            <p class="flex-1 font-medium text-gray-800 dark:text-gray-200">{!! $choice->title !!}</p>
                                            <div class="flex items-center gap-2 ml-auto">
                                                @if($isUserChoice)
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium bg-blue-100 dark:bg-blue-600 text-blue-800 dark:text-white">
                                                        <x-heroicon-s-hand-raised class="w-4 h-4"/> Bạn đã chọn
                                                    </span>
                                                @endif
                                                @if($isCorrectChoice)
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium bg-green-100 dark:bg-green-600 text-green-800 dark:text-white">
                                                        <x-heroicon-s-check class="w-4 h-4"/> Đáp án đúng
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-12">
                            <p class="font-semibold text-gray-600 dark:text-gray-400">Không có câu hỏi nào khớp với bộ lọc của bạn.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <!-- [END] Detailed Answers Section -->

            <!-- [START] Action Buttons -->
            <div class="flex justify-center gap-4 mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('filament.app.pages.my-quiz') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 dark:bg-indigo-500 rounded-lg font-semibold text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-all duration-200 hover:-translate-y-0.5 shadow-sm hover:shadow-md">
                    <x-heroicon-s-arrow-left-circle class="w-5 h-5" />
                    Về danh sách Quiz
                </a>
            </div>
            <!-- [END] Action Buttons -->

        </div>
    @else
        <div class="text-center py-12">
            <x-filament::loading-indicator class="w-10 h-10 mx-auto mb-4" />
            <p class="font-semibold text-gray-600 dark:text-gray-400">Đang tải dữ liệu kết quả...</p>
        </div>
    @endif
</x-filament-panels::page>
