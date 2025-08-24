<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Kết quả Quiz
                    </h1>
                    <h2 class="text-xl text-gray-600 dark:text-gray-400 mt-1">
                        {{ $this->quiz->title }}
                    </h2>
                    <p class="text-gray-500 dark:text-gray-500">
                        {{ $this->quiz->courses->first()?->title ?? 'Khóa học không xác định' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Score Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Score Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white text-center">
                <div class="text-4xl font-bold mb-2">
                    {{ $this->attemptModel->points }}/{{ $this->quiz->questions->sum('pivot.points') }}
                </div>
                <div class="text-lg">{{ number_format($this->percentage, 1) }}%</div>
                <div class="text-sm opacity-90 mt-1">Điểm số</div>
            </div>

            <!-- Correct Answers -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white text-center">
                <div class="text-4xl font-bold mb-2">{{ $this->correctAnswers }}</div>
                <div class="text-lg">Đúng</div>
                <div class="text-sm opacity-90 mt-1">Câu trả lời</div>
            </div>

            <!-- Incorrect Answers -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white text-center">
                <div class="text-4xl font-bold mb-2">{{ $this->incorrectAnswers }}</div>
                <div class="text-lg">Sai</div>
                <div class="text-sm opacity-90 mt-1">Câu trả lời</div>
            </div>

            <!-- Time Spent -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white text-center">
                <div class="text-4xl font-bold mb-2">{{ $this->timeSpent }}</div>
                <div class="text-lg">Thời gian</div>
                <div class="text-sm opacity-90 mt-1">Đã sử dụng</div>
            </div>
        </div>

        <!-- Performance Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Biểu đồ kết quả</h3>
            <div class="w-full h-64">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        <!-- Detailed Results -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Chi tiết câu trả lời</h3>

            <div class="space-y-6">
                @foreach($this->quiz->questions as $index => $question)
                    @php
                        $isCorrect = $this->isCorrectAnswer($question->id);
                        $isAnswered = $this->isAnswered($question->id);
                        $userAnswerIds = $this->getUserAnswers($question->id);
                        $correctChoice = $question->answerChoices->where('is_correct', true)->first();
                    @endphp

                    <div
                        class="border-l-4 rounded-lg p-6 transition-all duration-200
                        {{ $isCorrect ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : ($isAnswered ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20') }}">

                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Câu {{ $index + 1 }}: {!! $question->title !!}
                            </h4>
                            <div class="flex items-center space-x-2">
                                @if($isCorrect)
                                    <span
                                        class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                        Đúng
                                    </span>
                                @elseif($isAnswered)
                                    <span
                                        class="bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                        Sai
                                    </span>
                                @else
                                    <span
                                        class="bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                        Chưa trả lời
                                    </span>
                                @endif
                                <span
                                    class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $question->pivot->points ?? $question->points }} điểm
                                </span>
                            </div>
                        </div>

                        @if($question->question_image)
                            <div class="mb-4">
                                <img src="{{ $question->question_image }}" alt="Question Image"
                                    class="max-w-full h-auto rounded border">
                            </div>
                        @endif

                        <div class="space-y-2">
                            @foreach($question->answerChoices as $choice)
                                @php
                                    $isUserChoice = in_array($choice->id, $userAnswerIds);
                                    $isCorrectChoice = $choice->is_correct;
                                @endphp

                                <div
                                    class="p-3 rounded border transition-colors
                                    {{ $isCorrectChoice ? 'bg-green-100 dark:bg-green-800/30 border-green-200 dark:border-green-700' : 'bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600' }}
                                    {{ $isUserChoice && !$isCorrectChoice ? 'bg-red-100 dark:bg-red-800/30 border-red-200 dark:border-red-700' : '' }}">

                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700 dark:text-gray-300">{!! $choice->title !!}</span>
                                        <div class="flex items-center space-x-2">
                                            @if($isCorrectChoice)
                                                <span class="text-green-600 dark:text-green-400 font-medium flex items-center">
                                                    <x-heroicon-o-check-circle class="w-4 h-4 mr-1" />
                                                    Đáp án đúng
                                                </span>
                                            @endif
                                            @if($isUserChoice)
                                                <span class="text-blue-600 dark:text-blue-400 font-medium flex items-center">
                                                    <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
                                                    Bạn đã chọn
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($question->explanation)
                            <div
                                class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded">
                                <h5 class="font-medium text-blue-800 dark:text-blue-200 mb-1 flex items-center">
                                    <x-heroicon-o-light-bulb class="w-4 h-4 mr-1" />
                                    Giải thích:
                                </h5>
                                <p class="text-blue-700 dark:text-blue-300">{!! $question->explanation !!}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>



    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Performance Chart
                const ctx = document.getElementById('performanceChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Đúng', 'Sai', 'Chưa trả lời'],
                        datasets: [{
                            data: [
                                {{ $this->correctAnswers }},
                                {{ $this->incorrectAnswers }},
                                {{ $this->unansweredQuestions }}
                            ],
                            backgroundColor: [
                                '#10b981',
                                '#ef4444',
                                '#f59e0b'
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    font: {
                                        size: 14
                                    },
                                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#374151'
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-filament-panels::page>
