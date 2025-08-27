<x-filament-panels::page>
    {{-- CSS được tối ưu hóa cho giao diện mới --}}
    <style>
        :root {
            --color-primary: #3b82f6;
            /* blue-500 */
            --color-primary-hover: #2563eb;
            /* blue-600 */
            --color-primary-text: #ffffff;
            --color-secondary-text: #6b7280;
            /* gray-500 */
            --color-border: #e5e7eb;
            /* gray-200 */
            --color-bg-light: #ffffff;
            --color-bg-muted: #f9fafb;
            /* gray-50 */
        }

        .dark {
            --color-secondary-text: #9ca3af;
            /* gray-400 */
            --color-border: #374151;
            /* gray-700 */
            --color-bg-light: #1f2937;
            /* gray-800 */
            --color-bg-muted: #111827;
            /* gray-900 */
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #a0aec0;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #4a5568;
        }

        /* Animation cho slide câu hỏi */
        @keyframes slide-in {
            from {
                transform: translateX(20px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.3s ease-out forwards;
        }
    </style>

    {{-- AlpineJS Component chính --}}
    <div x-data="{
            remainingSeconds: @js($remainingSeconds),
            isUnlimited: @js($isUnlimited),
            timeWarning: @js($timeWarning),
            submitting: @js($submitting),
            timer: null,
            init() {
                if (!this.isUnlimited) {
                    this.startTimer();
                }
                $watch('$wire.submitting', value => this.submitting = value);
                $watch('$wire.remainingSeconds', value => this.remainingSeconds = value);
            },
            startTimer() {
                if (this.isUnlimited || this.remainingSeconds <= 0) return;
                if (this.timer) clearInterval(this.timer);
                this.timer = setInterval(() => {
                    this.remainingSeconds--;
                    this.timeWarning = this.remainingSeconds <= 300;
                    if (this.remainingSeconds <= 0) {
                        clearInterval(this.timer);
                        this.timer = null;
                        this.$wire.autoSubmit();
                    }
                }, 1000);
            },
            formatTime(seconds) {
                if (seconds === null || seconds < 0) seconds = 0;
                const h = Math.floor(seconds / 3600).toString().padStart(2, '0');
                const m = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
                const s = (seconds % 60).toString().padStart(2, '0');
                return h > 0 ? `${h}:${m}:${s}` : `${m}:${s}`;
            }
        }"
        @question-changed.window="document.getElementById('question-card').classList.remove('animate-slide-in'); void document.getElementById('question-card').offsetWidth; document.getElementById('question-card').classList.add('animate-slide-in');">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">

            <div class="lg:col-span-8 space-y-6">
                @if($this->currentQuestion)
                    <div id="question-card"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 animate-slide-in">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-grow">
                                    <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 mb-1">Câu
                                        {{ $this->currentQuestionIndex + 1 }} / {{ $this->totalQuestions }}</p>
                                    <div
                                        class="text-lg font-semibold text-gray-900 dark:text-white leading-relaxed prose prose-blue dark:prose-invert max-w-none">
                                        {!! $this->currentQuestion->title !!}
                                    </div>
                                </div>
                                <span
                                    class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 text-sm font-bold px-3 py-1 rounded-full whitespace-nowrap">
                                    {{ $this->currentQuestion->pivot->points ?? 1 }} điểm
                                </span>
                            </div>

                            @if($this->currentQuestion->question_image)
                                <div class="mt-4">
                                    <img src="{{ Storage::url($this->currentQuestion->question_image) }}" alt="Question Image"
                                        class="max-w-full h-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                </div>
                            @endif
                        </div>

                        <div class="p-6">
                            @if($this->currentQuestion->is_multiple_response)
                                <p class="text-sm italic text-gray-500 dark:text-gray-400 mb-4">Chọn một hoặc nhiều đáp án đúng.
                                </p>
                            @endif
                            <div class="space-y-3">
                                @foreach($this->currentQuestion->answerChoices as $choice)
                                    <label
                                        class="flex items-start p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-500 dark:hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:ring-2 has-[:checked]:ring-blue-300 dark:has-[:checked]:border-blue-500">
                                        @if($this->currentQuestion->is_multiple_response)
                                            <input type="checkbox" value="{{ $choice->id }}"
                                                wire:click="updateAnswer('{{ $this->currentQuestion->id }}', '{{ $choice->id }}')"
                                                @if(isset($this->answers[$this->currentQuestion->id]) && is_array($this->answers[$this->currentQuestion->id]) && in_array($choice->id, $this->answers[$this->currentQuestion->id])) checked @endif
                                                class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 mt-0.5 flex-shrink-0">
                                        @else
                                            <input type="radio" name="answers_{{ $this->currentQuestion->id }}"
                                                value="{{ $choice->id }}"
                                                wire:click="updateAnswer('{{ $this->currentQuestion->id }}', '{{ $choice->id }}')"
                                                @if(isset($this->answers[$this->currentQuestion->id]) && $this->answers[$this->currentQuestion->id] == $choice->id) checked @endif
                                                class="h-5 w-5 border-gray-300 text-blue-600 focus:ring-blue-500 mt-0.5 flex-shrink-0">
                                        @endif
                                        <span
                                            class="ml-4 text-gray-700 dark:text-gray-300 prose prose-blue dark:prose-invert max-w-none">{!! $choice->title !!}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
                        <p class="text-lg text-gray-600 dark:text-gray-400">Quiz này không có câu hỏi nào.</p>
                    </div>
                @endif

                <div
                    class="flex justify-between items-center bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4">
                    <button wire:click="previousQuestion" @disabled(!$this->hasPreviousQuestion)
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-gray-700 dark:text-gray-300 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600">
                        <x-heroicon-s-chevron-left class="w-5 h-5" />
                        Câu trước
                    </button>
                    <span
                        class="text-sm font-semibold text-gray-600 dark:text-gray-400">{{ $this->questionProgress }}</span>
                    <button wire:click="nextQuestion" @disabled(!$this->hasNextQuestion)
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-white bg-blue-600 hover:bg-blue-700">
                        Câu tiếp
                        <x-heroicon-s-chevron-right class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-20">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-5">
                    <div class="text-center p-4 rounded-lg transition-colors duration-300 mb-5"
                        :class="{ 'bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-500/30': timeWarning, 'bg-gray-100 dark:bg-gray-700/50': !timeWarning }">
                        <div class="text-sm font-medium"
                            :class="{'text-red-800 dark:text-red-300': timeWarning, 'text-gray-600 dark:text-gray-400': !timeWarning}">
                            Thời gian còn lại</div>
                        <div class="text-3xl font-mono font-bold mt-1"
                            :class="{'text-red-600 dark:text-red-300': timeWarning, 'text-gray-900 dark:text-white': !timeWarning}">
                            <span x-text="isUnlimited ? '∞' : formatTime(remainingSeconds)"></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-sm mb-1">
                        <span class="font-medium text-gray-600 dark:text-gray-400">Tiến độ</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $this->answeredCount }} /
                            {{ $this->totalQuestions }} câu</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                            style="width: {{ $this->progressPercentage }}%"></div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                    <h3
                        class="text-md font-semibold text-gray-900 dark:text-white p-4 border-b border-gray-200 dark:border-gray-700">
                        Danh sách câu hỏi</h3>
                    <div class="p-4 max-h-64 overflow-y-auto custom-scrollbar">
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($this->questionsWithStatus as $q)
                                <button wire:click="goToQuestion({{ $q['index'] }})"
                                    class="h-10 w-10 flex items-center justify-center font-bold text-sm rounded-md transition-all duration-200 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-blue-500
                                        {{ $q['is_current'] ? 'bg-blue-600 text-white ring-2 ring-offset-2 ring-blue-500 dark:ring-offset-gray-800' : '' }}
                                        {{ !$q['is_current'] && $q['is_answered'] ? 'bg-green-500 text-white' : '' }}
                                        {{ !$q['is_current'] && !$q['is_answered'] ? 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300' : '' }}">
                                    {{ $q['index'] + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                    {{ ($this->customSubmitAction)() }}
                    {{ ($this->customBackAction)() }}
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
