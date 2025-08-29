<x-filament-panels::page>
    <style>
        /* Custom radio button styling */
        input[type="radio"]:checked+.radio-indicator {
            background: linear-gradient(to right, #3b82f6, #2563eb) !important;
            border-color: #3b82f6 !important;
        }

        input[type="radio"]:checked+.radio-indicator .radio-dot {
            opacity: 1 !important;
            transform: scale(1) !important;
        }

        /* Custom checkbox styling */
        input[type="checkbox"]:checked+.checkbox-indicator {
            background: linear-gradient(to right, #3b82f6, #2563eb) !important;
            border-color: #3b82f6 !important;
        }

        input[type="checkbox"]:checked+.checkbox-indicator .checkbox-icon {
            opacity: 1 !important;
            transform: scale(1) !important;
        }
    </style>
    <div x-data="quizTakingApp()" x-init="init()" class="min-h-screen bg-gray-100 dark:bg-gray-900">

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $this->quizModel->title }}</h1>
                {!! $this->quizModel->description !!}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div
                        class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center gap-3">

                            <div>
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Tổng số câu</p>
                                <p class="text-2xl font-bold text-blue-800 dark:text-blue-200">
                                    {{ $this->quizModel->questions->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-lg p-4 border border-green-200 dark:border-green-700">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-500 rounded-full p-2">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-600 dark:text-green-400">Thời gian</p>
                                <p class="text-2xl font-bold text-green-800 dark:text-green-200">
                                    @if($this->quizModel->time_limit_minutes)
                                        {{ $this->quizModel->time_limit_minutes }} phút
                                    @else
                                        <span class="text-green-600 dark:text-green-400 font-bold">∞</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-r from-amber-50 to-amber-100 dark:from-amber-900/30 dark:to-amber-800/30 rounded-lg p-4 border border-amber-200 dark:border-amber-700">
                        <div class="flex items-center gap-3">
                            <div class="bg-amber-500 rounded-full p-2">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-amber-600 dark:text-amber-400">Điểm tối đa</p>
                                <p class="text-2xl font-bold text-amber-800 dark:text-amber-200">
                                    {{ $this->quizModel->max_points ?? $this->quizModel->questions->sum('points') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <div class="flex-1 lg:order-1 order-2 space-y-6">
                    @if($this->currentQuestion)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4 lg:p-6">
                            <div
                                class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-3 lg:gap-4 mb-4 lg:mb-6">
                                <h3
                                    class="text-base lg:text-lg font-semibold text-gray-900 dark:text-white leading-relaxed flex items-start gap-3">
                                    <span
                                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-bold px-2 py-1 lg:px-3 lg:py-1 rounded-full min-w-[1.75rem] lg:min-w-[2rem] text-center flex-shrink-0">{{ $this->currentQuestionIndex + 1 }}</span>
                                    <span class="flex-1">{!! $this->currentQuestion->title !!}</span>
                                </h3>
                                <div class="flex items-center justify-between lg:justify-end lg:text-right gap-2">
                                    <span
                                        class="bg-gradient-to-r from-amber-100 to-amber-200 dark:from-amber-900/50 dark:to-amber-800/50 text-amber-800 dark:text-amber-200 text-xs font-bold px-2 py-1 lg:px-3 lg:py-2 rounded-full whitespace-nowrap shadow-sm border border-amber-300 dark:border-amber-700">
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        {{ $this->currentQuestion->pivot->points ?? $this->currentQuestion->points }} điểm
                                    </span>

                                </div>

                            </div>
                            @if($this->currentQuestion->is_multiple_response)
                                @php
                                    $correctAnswersCount = $this->currentQuestion->answerChoices->where('is_correct', true)->count();
                                @endphp
                                <div class="text-xs text-gray-600 dark:text-gray-400 p-2">
                                    {{ $correctAnswersCount }} đáp án đúng
                                </div>
                            @endif
                            @if($this->currentQuestion->question_image)
                                <div class="mb-4">
                                    <img src="{{ $this->currentQuestion->question_image }}" alt="Question Image"
                                        class="max-w-full h-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                </div>
                            @endif

                            <div class="space-y-3 lg:space-y-4">
                                @foreach($this->currentQuestion->answerChoices as $choice)
                                    <label
                                        class="flex items-start p-3 lg:p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer transition-all duration-300 hover:border-blue-400 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20 hover:shadow-md has-[:checked]:border-blue-500 has-[:checked]:bg-gradient-to-r has-[:checked]:from-blue-50 has-[:checked]:to-indigo-50 dark:has-[:checked]:from-blue-900/30 dark:has-[:checked]:to-indigo-900/30 has-[:checked]:shadow-lg transform hover:scale-[1.02] has-[:checked]:scale-[1.02]">
                                        @if($this->currentQuestion->is_multiple_response)
                                            <input type="checkbox" name="answers_{{ $this->currentQuestion->id }}[]"
                                                value="{{ $choice->id }}"
                                                wire:key="checkbox-{{ $this->currentQuestion->id }}-{{ $choice->id }}"
                                                wire:click="updateAnswer('{{ $this->currentQuestion->id }}', '{{ $choice->id }}')"
                                                @if(isset($this->answers[$this->currentQuestion->id]) && is_array($this->answers[$this->currentQuestion->id]) && in_array($choice->id, $this->answers[$this->currentQuestion->id])) checked @endif class="peer sr-only">
                                            <div
                                                class="checkbox-indicator w-5 h-5 lg:w-6 lg:h-6 border-2 border-gray-400 rounded-md flex items-center justify-center flex-shrink-0 transition-all duration-300 peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-blue-600 peer-checked:border-blue-500 peer-checked:shadow-md mt-0.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                                    class="checkbox-icon w-3 h-3 lg:w-4 lg:h-4 text-white opacity-0 scale-50 transition-all duration-300 peer-checked:opacity-100 peer-checked:scale-100">
                                                    <path fill-rule="evenodd"
                                                        d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @else
                                            <input type="radio" name="answers_{{ $this->currentQuestion->id }}"
                                                value="{{ $choice->id }}"
                                                wire:key="radio-{{ $this->currentQuestion->id }}-{{ $choice->id }}"
                                                wire:model.live="answers.{{ $this->currentQuestion->id }}"
                                                wire:click="updateAnswer('{{ $this->currentQuestion->id }}', '{{ $choice->id }}')"
                                                @if(isset($this->answers[$this->currentQuestion->id]) && $this->answers[$this->currentQuestion->id] == $choice->id) checked @endif
                                                class="peer sr-only">
                                            <div
                                                class="radio-indicator w-5 h-5 lg:w-6 lg:h-6 border-2 border-gray-400 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300 peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-blue-600 peer-checked:border-blue-500 peer-checked:shadow-md mt-0.5">
                                                <div
                                                    class="radio-dot w-2 h-2 lg:w-2.5 lg:h-2.5 bg-white rounded-full opacity-0 scale-50 transition-all duration-300">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="ml-3 lg:ml-4 flex-1 min-w-0">
                                            <span
                                                class="text-sm lg:text-base text-gray-700 dark:text-gray-300 peer-checked:text-blue-900 dark:peer-checked:text-white peer-checked:font-semibold transition-all duration-300 leading-relaxed block">{!! $choice->title !!}</span>
                                            @if($choice->image)
                                                <img src="{{ Storage::url($choice->image) }}" alt="Choice image"
                                                    class="mt-2 max-w-full h-auto rounded-lg shadow-sm">
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div
                                class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-0 mt-6 lg:mt-8 pt-4 lg:pt-6 border-t border-gray-200 dark:border-gray-700">
                                <button wire:click="previousQuestion" @if(!$this->hasPreviousQuestion) disabled @endif
                                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed w-full sm:w-auto text-sm lg:text-base font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Câu trước
                                </button>

                                <div class="text-center order-first sm:order-none w-full sm:w-auto">
                                    <span
                                        class="text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-400">{{ $this->questionProgress }}</span>
                                    <div
                                        class="w-full sm:w-32 lg:w-40 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                                        <div class="bg-blue-500 h-1.5 rounded-full transition-all duration-300"
                                            style="width: {{ ($this->currentQuestionIndex + 1) / count($this->questionsWithStatus) * 100 }}%">
                                        </div>
                                    </div>
                                </div>

                                <button wire:click="nextQuestion" @if(!$this->hasNextQuestion) disabled @endif
                                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed w-full sm:w-auto text-sm lg:text-base font-medium">
                                    Câu tiếp
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="w-full lg:w-80 lg:order-2 order-1 space-y-4 lg:space-y-6">

                    <div
                        class="lg:hidden bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between mb-3">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium text-gray-900 dark:text-white">Tiến độ:</span>
                                    <span
                                        class="ml-1 font-semibold text-blue-600 dark:text-blue-400">{{ $this->answeredCount }}</span>
                                    <span class="text-gray-500 dark:text-gray-400">/</span>
                                    <span
                                        class="font-semibold text-gray-700 dark:text-gray-300">{{ $this->totalQuestions }}</span>
                                    <span class="text-gray-500 dark:text-gray-400">câu</span>
                                </div>
                                <div class="text-xs px-2 py-1 rounded-full
                                    @if($this->progressPercentage >= 100)
                                        bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                    @elseif($this->progressPercentage >= 50)
                                        bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                                    @else
                                        bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                    @endif">
                                    {{ $this->progressPercentage }}%
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                {{ $this->customSubmitAction }}

                                @if($this->quizModel->time_limit_minutes && !$this->isUnlimited)
                                    <div id="timer-mobile"
                                        class="px-3 py-2 rounded-xl font-bold transition-all duration-300"
                                        :class="{ 'bg-gradient-to-r from-red-500 to-red-600 text-white animate-pulse': timeWarning, 'bg-gradient-to-r from-blue-500 to-blue-600 text-white': !timeWarning }">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <div class="font-mono text-base" x-text="formatTime(remainingSeconds)">
                                                {{ $this->quizModel->time_limit_minutes }}:00
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div
                        class="lg:hidden bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-3">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Câu hỏi</h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $this->questionProgress }}</span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @foreach($this->questionsWithStatus as $questionData)
                                <button wire:click="goToQuestion({{ $questionData['index'] }})" class="w-8 h-8 rounded-md text-xs font-medium transition-all duration-200 hover:scale-110 active:scale-95
                                        @if($questionData['is_current'])
                                            bg-blue-500 text-white shadow-md
                                        @elseif($questionData['is_answered'])
                                            bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-300 dark:border-green-700 hover:bg-green-200 dark:hover:bg-green-900/50
                                        @else
                                            bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600
                                        @endif"
                                    title="Câu {{ $questionData['index'] + 1 }}{{ $questionData['is_answered'] ? ' (Đã trả lời)' : '' }}">
                                    {{ $questionData['index'] + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="hidden lg:block lg:sticky lg:top-6 space-y-6">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                            <div class="space-y-3">
                                <div
                                    class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <div class="text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Tiến độ:</span>
                                        <span
                                            class="ml-1 font-semibold text-blue-600 dark:text-blue-400">{{ $this->answeredCount }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">/</span>
                                        <span
                                            class="font-semibold text-gray-700 dark:text-gray-300">{{ $this->totalQuestions }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">câu</span>
                                    </div>
                                    <div class="text-xs px-2 py-1 rounded-full font-medium
                                        @if($this->progressPercentage >= 100)
                                            bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                        @elseif($this->progressPercentage >= 50)
                                            bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                                        @else
                                            bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300
                                        @endif">
                                        {{ $this->progressPercentage }}%
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    {{ $this->customSubmitAction }}
                                    @if($this->quizModel->time_limit_minutes && !$this->isUnlimited)
                                        <div id="timer" class="px-3 py-2 rounded-xl font-bold transition-all duration-300"
                                            :class="{ 'bg-gradient-to-r from-red-500 to-red-600 text-white animate-pulse': timeWarning, 'bg-gradient-to-r from-blue-500 to-blue-600 text-white': !timeWarning }">
                                            <div class="flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="font-mono text-lg" x-text="formatTime(remainingSeconds)">
                                                    {{ $this->quizModel->time_limit_minutes }}:00
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Danh sách câu hỏi</h3>
                            <div class="grid grid-cols-5 gap-2">
                                @foreach($this->questionsWithStatus as $questionData)
                                    <button wire:click="goToQuestion({{ $questionData['index'] }})" class="w-10 h-10 rounded-lg text-sm font-medium transition-all duration-200
                                                @if($questionData['is_current'])
                                                    bg-blue-500 text-white shadow-md
                                                @elseif($questionData['is_answered'])
                                                    bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-300 dark:border-green-700
                                                @else
                                                    bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600
                                                @endif"
                                        title="Câu {{ $questionData['index'] + 1 }}{{ $questionData['is_answered'] ? ' (Đã trả lời)' : '' }}">
                                        {{ $questionData['index'] + 1 }}
                                    </button>
                                @endforeach
                            </div>
                            <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1">
                                        <div class="w-3 h-3 bg-blue-500 rounded"></div>
                                        <span>Hiện tại</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <div
                                            class="w-3 h-3 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded">
                                        </div>
                                        <span>Đã trả lời</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <div class="w-3 h-3 bg-gray-100 dark:bg-gray-700 rounded"></div>
                                        <span>Chưa trả lời</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Javascript không thay đổi
            function quizTakingApp() {
                return {
                    remainingSeconds: @js($this->remainingSeconds),
                    isUnlimited: @js($this->isUnlimited),
                    timeWarning: false,
                    submitting: false,
                    quizId: @js($this->quizModel->id),
                    attemptId: @js($this->attempt->id ?? null),
                    timer: null,

                    init() {
                        this.timeWarning = this.remainingSeconds <= 300;
                        this.loadFromStorage();
                        this.startTimer();
                        this.bindAnswerEvents();
                        this.loadCurrentQuestionIndex();
                        this.$watch('$wire.submitting', (value) => {
                            this.submitting = value;
                        });

                        // Listen for question navigation events
                        this.$wire.on('question-changed', () => {
                            this.saveCurrentQuestionIndex();
                        });

                        // Listen for answers loaded event
                        this.$wire.on('answers-loaded', () => {
                            this.updateCheckboxStates();
                        });

                        // Listen for clear storage event
                        this.$wire.on('clear-quiz-storage', () => {
                            this.clearStorage();
                        });

                        // Listen for clear previous quiz storage event
                        this.$wire.on('clear-previous-quiz-storage', (data) => {
                            this.clearPreviousQuizStorage(data.quizId);
                        });
                    },

                    loadFromStorage() {
                        const storageKey = `quiz_${this.quizId}_attempt_${this.attemptId}`;
                        const savedAnswers = localStorage.getItem(storageKey);
                        if (savedAnswers) {
                            try {
                                const answers = JSON.parse(savedAnswers);
                                this.$wire.set('answers', answers, false);
                                this.updateProgressFromStorage(answers);
                            } catch (e) {
                                console.error('Error loading saved answers:', e);
                            }
                        }

                        // Load current question index
                        this.loadCurrentQuestionIndex();
                    },

                    updateProgressFromStorage(answers) {
                        const totalQuestions = {{ $this->quizModel->questions->count() }};
                        const answeredCount = Object.values(answers).filter(a => Array.isArray(a) ? a.length > 0 : a !== null).length;
                        const percentage = totalQuestions > 0 ? Math.round((answeredCount / totalQuestions) * 100) : 0;

                        const progressBar = document.querySelector('.bg-blue-500');
                        const progressText = document.querySelector('.text-gray-600');
                        const progressPercentage = document.querySelector('.font-semibold');

                        if (progressBar) progressBar.style.width = percentage + '%';
                        if (progressText) progressText.textContent = `Tiến độ: ${answeredCount}/${totalQuestions} câu`;
                        if (progressPercentage) progressPercentage.textContent = percentage + '%';
                    },

                    bindAnswerEvents() {
                        this.$watch('$wire.answers', (newAnswers) => {
                            this.saveToStorage(newAnswers);
                            this.$wire.call('autoSave');
                        });
                    },

                    saveToStorage(answers) {
                        const storageKey = `quiz_${this.quizId}_attempt_${this.attemptId}`;
                        localStorage.setItem(storageKey, JSON.stringify(answers));
                        this.updateProgressFromStorage(answers);
                    },

                    saveCurrentQuestionIndex() {
                        if (this.quizId) {
                            const storageKey = `quiz_${this.quizId}_current_question`;
                            localStorage.setItem(storageKey, this.$wire.currentQuestionIndex.toString());
                        }
                    },

                    loadCurrentQuestionIndex() {
                        if (this.quizId) {
                            const storageKey = `quiz_${this.quizId}_current_question`;
                            const savedIndex = localStorage.getItem(storageKey);
                            if (savedIndex !== null) {
                                const index = parseInt(savedIndex);
                                if (!isNaN(index) && index >= 0) {
                                    this.$wire.currentQuestionIndex = index;
                                }
                            }
                        }
                    },

                    updateCheckboxStates() {
                        const answers = this.$wire.answers;

                        // Update all checkboxes based on current answers
                        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                            const questionId = checkbox.name.match(/answers_(\d+)/)?.[1];
                            const choiceId = parseInt(checkbox.value);

                            if (questionId && answers[questionId] && Array.isArray(answers[questionId])) {
                                checkbox.checked = answers[questionId].includes(choiceId);
                            } else {
                                checkbox.checked = false;
                            }
                        });

                        // Update progress after state update
                        this.updateProgressFromStorage(answers);
                    },

                    clearStorage() {
                        const storageKey = `quiz_${this.quizId}_attempt_${this.attemptId}`;
                        localStorage.removeItem(storageKey);
                    },

                    clearPreviousQuizStorage(quizId) {
                        // Clear all localStorage entries for this quiz (all attempts)
                        const keysToRemove = [];
                        for (let i = 0; i < localStorage.length; i++) {
                            const key = localStorage.key(i);
                            if (key && key.startsWith(`quiz_${quizId}_attempt_`)) {
                                keysToRemove.push(key);
                            }
                        }
                        keysToRemove.forEach(key => localStorage.removeItem(key));
                    },

                    startTimer() {
                        if (this.isUnlimited) return;

                        // Clear any existing timer to prevent multiple timers
                        if (this.timer) {
                            clearInterval(this.timer);
                        }

                        // Check if time is already up
                        if (this.remainingSeconds <= 0) {
                            this.autoSubmit();
                            return;
                        }

                        this.timer = setInterval(() => {
                            this.remainingSeconds--;
                            this.timeWarning = this.remainingSeconds <= 300;
                            if (this.remainingSeconds <= 0) {
                                clearInterval(this.timer);
                                this.timer = null;
                                this.autoSubmit();
                            }
                        }, 1000);
                    },

                    formatTime(seconds) {
                        if (seconds === null || seconds < 0) seconds = 0;
                        const hours = Math.floor(seconds / 3600);
                        const minutes = Math.floor((seconds % 3600) / 60);
                        const secs = seconds % 60;
                        if (hours > 0) {
                            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                        }
                        return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                    },

                    confirmSubmit() {
                        if (this.submitting) return;

                        // Call custom action instead of direct method
                        this.clearStorage();
                        this.$wire.mountAction('customSubmit');

                        event.preventDefault();
                    },

                    autoSubmit() {
                        // Show Filament notification for auto-submit
                        this.$wire.call('showAutoSubmitNotification');
                        this.clearStorage();
                        this.$wire.mountAction('customSubmit');
                    }
                }
            }
        </script>
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>
