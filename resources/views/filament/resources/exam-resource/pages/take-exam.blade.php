<x-filament-panels::page>
    @if (!$examStarted)
        {{-- Màn hình bắt đầu bài thi --}}
        <div class="p-6 text-center bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
            <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $record->title }}</h2>
            <p class="mb-4 font-normal text-gray-700 dark:text-gray-400">{{ $record->description }}</p>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Thời gian làm bài: {{ $record->duration_minutes }} phút</p>

          <div class="flex items-center justify-center space-x-8">
    @if ($this->incompleteAttempt)
        <x-filament::button wire:click="continueExam" color="success">
            Tiếp tục bài làm dở
        </x-filament::button>
    @endif

    <x-filament::button wire:click="startExam" wire:confirm="Bắt đầu làm bài mới sẽ xóa bài làm dở trước đó (nếu có). Bạn có chắc không?">
        Bắt đầu bài thi mới
    </x-filament::button>
</div>
        </div>
    @else
        <div
            x-data="{
                timeLeft: @entangle('timeLeft'),
                timer: null,
                init() {
                    if (this.timeLeft > 0) {
                        this.timer = setInterval(() => {
                            this.timeLeft--;
                            if (this.timeLeft <= 0) {
                                clearInterval(this.timer);
                                @this.submitExam();
                            }
                        }, 1000);
                    }
                    this.$watch('timeLeft', (newValue) => {
                        if (newValue <= 0) {
                            clearInterval(this.timer);
                        }
                    });
                },
                formatTime() {
                    if (this.timeLeft === null || this.timeLeft < 0) return '00:00';
                    const minutes = Math.floor(this.timeLeft / 60);
                    const seconds = this.timeLeft % 60;
                    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
            }"
            class="grid grid-cols-1 gap-6"
        >
            <div>
                <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                    {{-- Thời gian --}}
                    <div class="mb-6 text-center">
                        <h4 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Thời gian còn lại</h4>
                        <div x-text="formatTime()" class="text-4xl font-bold text-gray-900 dark:text-white" :class="{'text-danger-500': timeLeft < 60}"></div>
                    </div>

                    {{-- Bảng câu hỏi --}}
                    <div class="border-t pt-6 dark:border-gray-700">
                        <h4 class="mb-4 text-sm font-medium text-gray-500 dark:text-gray-400">Danh sách câu hỏi</h4>
                        <div class="flex pb-2 space-x-2 overflow-x-auto">
                            @foreach ($questions as $index => $question)
                                @php
                                    $isAnswered = false;
                                    $qType = $this->getQuestionType($question);
                                    $qId = $question->id;
                                    if ($qType?->value === 'multiple_choice') {
                                        $isAnswered = !empty($multipleChoiceAnswers[$qId]);
                                    } else {
                                        $isAnswered = !is_null(
                                            ($singleChoiceAnswers[$qId] ?? null) ??
                                            ($trueFalseAnswers[$qId] ?? null) ??
                                            ($shortAnswers[$qId] ?? null) ??
                                            ($essayAnswers[$qId] ?? null)
                                        );
                                    }
                                @endphp
                                <button
                                    type="button"
                                    wire:click="goToQuestion({{ $index }})"
                                    class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-sm font-medium border rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-primary-500"
                                    :class="{
                                        'bg-primary-600 text-white border-primary-600': {{ $currentQuestionIndex == $index ? 'true' : 'false' }},
                                        'bg-green-100 text-green-800 border-green-300 dark:bg-green-900 dark:text-green-300 dark:border-green-700': {{ $isAnswered && $currentQuestionIndex != $index ? 'true' : 'false' }},
                                        'bg-white hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600': {{ !$isAnswered && $currentQuestionIndex != $index ? 'true' : 'false' }}
                                    }"
                                >
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                    @php
                        $currentQuestion = $questions[$currentQuestionIndex];
                        $questionType = $this->getQuestionType($currentQuestion);
                    @endphp

                    {{-- Header câu hỏi (Tiêu đề) --}}
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $currentQuestion->question_text }}
                        </h3>
                        <span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
                            {{ $questionMeta[$currentQuestion->id]['points'] ?? 1 }} điểm
                        </span>
                    </div>

               {{-- Vùng trả lời --}}
<div class="space-y-4">
    @switch($questionType)

        @case(App\Enums\QuestionType::SingleChoice)
        @case(App\Enums\QuestionType::TrueFalse)
            @foreach($currentQuestion->choices as $choice)
                <label class="flex items-center p-3 border rounded-lg cursor-pointer dark:border-gray-700">
                    <input type="radio" name="answer.{{ $currentQuestion->id }}" wire:model.live="singleChoiceAnswers.{{ $currentQuestion->id }}" value="{{ $choice->id }}" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                    {{-- SỬA LẠI: Thêm !important vào class margin --}}
                    <span class="!ml-8 text-sm font-medium text-gray-900 dark:text-white">
                        {!! $choice->choice_text !!}
                    </span>
                </label>
            @endforeach
            @break

        @case(App\Enums\QuestionType::MultipleChoice)
            @foreach($currentQuestion->choices as $choice)
                <label class="flex items-center p-3 border rounded-lg cursor-pointer dark:border-gray-700">
                    <input type="checkbox" wire:model.live="multipleChoiceAnswers.{{ $currentQuestion->id }}" value="{{ $choice->id }}" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                    {{-- SỬA LẠI: Thêm !important vào class margin --}}
                    <span class="!ml-8 text-sm font-medium text-gray-900 dark:text-white">
                        {!! $choice->choice_text !!}
                    </span>
                </label>
            @endforeach
            @break

        @case(App\Enums\QuestionType::ShortAnswer)
            <input type="text" wire:model.live.debounce.500ms="shortAnswers.{{ $currentQuestion->id }}" placeholder="Nhập câu trả lời của bạn" class="block w-full transition duration-75 border-gray-300 rounded-lg shadow-sm fi-input focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-primary-500">
            @break

        @case(App\Enums\QuestionType::Essay)
            <textarea wire:model.live.debounce.500ms="essayAnswers.{{ $currentQuestion->id }}" placeholder="Nhập bài luận của bạn" rows="6" class="block w-full transition duration-75 border-gray-300 rounded-lg shadow-sm fi-input focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-primary-500"></textarea>
            @break
    @endswitch
</div>
                </div>
            </div>

            <div>
                <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                    <div class="flex justify-between mb-4">
                        <x-filament::button wire:click="previousQuestion" :disabled="$currentQuestionIndex === 0">
                            Câu trước
                        </x-filament::button>
                        <x-filament::button wire:click="nextQuestion" :disabled="$currentQuestionIndex === $questions->count() - 1">
                            Câu sau
                        </x-filament::button>
                    </div>
                    <x-filament::button
                        wire:click="submitExam"
                        wire:confirm="Bạn có chắc chắn muốn nộp bài không?"
                        color="success"
                        class="w-full"
                    >
                        Nộp bài
                    </x-filament::button>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
