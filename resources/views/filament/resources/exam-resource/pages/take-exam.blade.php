<x-filament-panels::page>

    {{-- GIAO DIỆN TRƯỚC KHI BẮT ĐẦU --}}
    @if (!$examStarted)
        <div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
            <h2 class="mb-2 text-2xl font-bold">{{ $record->title }}</h2>
            <div class="prose dark:prose-invert max-w-none">
                {!! $record->description !!}
            </div>

            <div class="mt-4 space-y-2">
                <p><strong>Thời gian làm bài:</strong> {{ $record->duration_minutes }} phút</p>
                <p><strong>Số lần làm bài tối đa:</strong> {{ $record->max_attempts }}</p>
                <p><strong>Điểm để đạt:</strong> {{ $record->passing_score }}%</p>
            </div>

            <hr class="my-6">

            <div class="text-center">
                <x-filament::button wire:click="startExam" size="xl">
                    Bắt đầu làm bài
                </x-filament::button>
            </div>
        </div>
    @else
        {{-- GIAO DIỆN TRONG KHI LÀM BÀI --}}
        <div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800" x-data="{
                             timeLeft: {{ $timeLeft ?? 0 }},
                             timer: null,
                             init() {
                                 if (this.timeLeft > 0) {
                                     if (this.timer) clearInterval(this.timer);
                                     this.timer = setInterval(() => { this.timeLeft--; }, 1000);
                                 }
                                 this.$watch('timeLeft', value => {
                                     if (value <= 0) {
                                         clearInterval(this.timer);
                                         @this.call('submitExam');
                                     }
                                 });
                             },
                             displayTime() {
                                 if (this.timeLeft <= 0) return '00:00';
                                 const minutes = Math.floor(this.timeLeft / 60);
                                 const seconds = this.timeLeft % 60;
                                 return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                             }
                         }" x-init="init()">

            <div class="font-mono text-2xl font-bold text-right text-danger-500">
                Thời gian còn lại: <span x-text="displayTime()"></span>
            </div>

            <hr class="my-4">

            @if ($questions->count() > 0)
                @php
                    $currentQuestion = $questions[$currentQuestionIndex];
                @endphp
                <div wire:key="question-{{ $currentQuestion->id }}">
                    <h3 class="mb-2 text-xl font-semibold">
                        Câu hỏi {{ $currentQuestionIndex + 1 }}/{{ $questions->count() }}
                        <span class="text-sm font-normal text-gray-500">({{ $currentQuestion->pivot->points ?? 1 }} điểm)</span>
                    </h3>
                    <div class="mb-4 text-lg prose dark:prose-invert max-w-none">
                        {!! $currentQuestion->question_text !!}
                    </div>

                    {{-- Khu vực trả lời --}}
                    <div class="space-y-4">
                        @if ($this->getQuestionType($currentQuestion) === \App\Enums\QuestionType::SingleChoice)
                            @foreach ($currentQuestion->choices as $choice)
                                <label
                                    class="flex items-center p-3 border rounded-lg cursor-pointer dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="radio" name="question_{{ $currentQuestion->id }}"
                                        wire:model.live="mcqAnswers.{{ $currentQuestion->id }}" value="{{ $choice->id }}"
                                        class="w-5 h-5 text-primary-600 focus:ring-primary-500 dark:bg-gray-900 dark:border-gray-600">
                                    <span class="ml-3">{!! $choice->choice_text !!}</span>
                                </label>
                            @endforeach
                        @else
                            <textarea wire:model.blur="essayAnswers.{{ $currentQuestion->id }}" rows="5"
                                placeholder="Nhập câu trả lời của bạn..."
                                class="block w-full border-gray-300 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:ring-primary-500 focus:border-primary-500"></textarea>
                        @endif
                    </div>
                </div>

                {{-- Các nút điều hướng --}}
                <div class="flex items-center justify-between mt-8">
                    <x-filament::button wire:click="previousQuestion" :disabled="$currentQuestionIndex === 0"
                        icon="heroicon-o-arrow-left">
                        Câu trước
                    </x-filament::button>

                    @if ($currentQuestionIndex < $questions->count() - 1)
                        <x-filament::button wire:click="nextQuestion" icon="heroicon-o-arrow-right" icon-position="after">
                            Câu tiếp
                        </x-filament::button>
                    @else
                        <x-filament::button wire:click="submitExam" wire:confirm="Bạn có chắc chắn muốn nộp bài không?"
                            color="success" icon="heroicon-o-check-circle">
                            Nộp bài
                        </x-filament::button>
                    @endif
                </div>
            @else
                <p class="text-center text-gray-500">Bài thi này không có câu hỏi nào.</p>
            @endif
        </div>
    @endif
</x-filament-panels::page>
