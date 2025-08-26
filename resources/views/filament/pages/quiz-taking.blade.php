<x-filament-panels::page>
    <style>
        /* Custom radio button styling */
        input[type="radio"]:checked + .radio-indicator {
            background: linear-gradient(to right, #3b82f6, #2563eb) !important;
            border-color: #3b82f6 !important;
        }

        input[type="radio"]:checked + .radio-indicator .radio-dot {
            opacity: 1 !important;
            transform: scale(1) !important;
        }

        /* Custom checkbox styling */
        input[type="checkbox"]:checked + .checkbox-indicator {
            background: linear-gradient(to right, #3b82f6, #2563eb) !important;
            border-color: #3b82f6 !important;
        }

        input[type="checkbox"]:checked + .checkbox-indicator .checkbox-icon {
            opacity: 1 !important;
            transform: scale(1) !important;
        }
    </style>
    <div x-data="quizTakingApp()" x-init="init()" class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Quiz Header -->

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $this->quizModel->title }}</h1>
                {!! $this->quizModel->description !!}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-500 rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Tổng số câu</p>
                            <p class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $this->quizModel->questions->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-lg p-4 border border-green-200 dark:border-green-700">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-500 rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
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

                <div class="bg-gradient-to-r from-amber-50 to-amber-100 dark:from-amber-900/30 dark:to-amber-800/30 rounded-lg p-4 border border-amber-200 dark:border-amber-700">
                    <div class="flex items-center gap-3">
                        <div class="bg-amber-500 rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-amber-600 dark:text-amber-400">Điểm tối đa</p>
                            <p class="text-2xl font-bold text-amber-800 dark:text-amber-200">{{ $this->quizModel->max_points ?? $this->quizModel->questions->sum('points') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timer -->
        @if($this->quizModel->time_limit_minutes && !$this->isUnlimited)
            <div id="timer" class="fixed top-4 right-4 px-6 py-3 rounded-xl font-bold z-50 shadow-xl backdrop-blur-sm transition-all duration-300" :class="{ 'bg-gradient-to-r from-red-500 to-red-600 text-white border border-red-400 animate-pulse': timeWarning, 'bg-gradient-to-r from-blue-500 to-blue-600 text-white border border-blue-400': !timeWarning }">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-center">
                        <div class="text-lg font-mono" x-text="formatTime(remainingSeconds)">{{ $this->quizModel->time_limit_minutes }}:00</div>
                        <div class="text-xs opacity-80">/ {{ $this->quizModel->time_limit_minutes }} phút</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Main Content: Questions -->
            <div class="lg:col-span-2 space-y-6">
                @foreach($this->quizModel->questions as $index => $question)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex justify-between items-start gap-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white leading-relaxed flex items-start gap-3">
                                <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-bold px-3 py-1 rounded-full min-w-[2rem] text-center">{{ $index + 1 }}</span>
                                <span>{!! $question->title !!}</span>
                            </h3>
                            <div class="text-right">
                                <span class="bg-gradient-to-r from-amber-100 to-amber-200 dark:from-amber-900/50 dark:to-amber-800/50 text-amber-800 dark:text-amber-200 text-xs font-bold px-3 py-2 rounded-full whitespace-nowrap shadow-sm border border-amber-300 dark:border-amber-700">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ $question->pivot->points ?? $question->points }} điểm
                                </span>
                                @if($question->is_multiple_response)
                                    @php
                                        $correctAnswersCount = $question->answerChoices->where('is_correct', true)->count();
                                    @endphp
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $correctAnswersCount }} đáp án đúng
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($question->question_image)
                            <div class="mb-4">
                                <img src="{{ $question->question_image }}" alt="Question Image" class="max-w-full h-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            </div>
                        @endif



                        <div class="space-y-4">
                            @foreach($question->answerChoices as $choice)
                                <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer transition-all duration-300 hover:border-blue-400 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20 hover:shadow-md has-[:checked]:border-blue-500 has-[:checked]:bg-gradient-to-r has-[:checked]:from-blue-50 has-[:checked]:to-indigo-50 dark:has-[:checked]:from-blue-900/30 dark:has-[:checked]:to-indigo-900/30 has-[:checked]:shadow-lg transform hover:scale-[1.02] has-[:checked]:scale-[1.02]">
                                    @if($question->is_multiple_response)
                                        <input type="checkbox" name="answers_{{ $question->id }}[]" value="{{ $choice->id }}"
                                            wire:key="checkbox-{{ $question->id }}-{{ $choice->id }}"
                                            wire:click="updateAnswer('{{ $question->id }}', '{{ $choice->id }}')"
                                            @if(isset($this->answers[$question->id]) && is_array($this->answers[$question->id]) && in_array($choice->id, $this->answers[$question->id])) checked @endif
                                            class="peer sr-only"
                                            onchange="this.closest('label').querySelector('.checkbox-indicator').style.backgroundColor = this.checked ? '#3b82f6' : 'transparent'; this.closest('label').querySelector('.checkbox-indicator').style.borderColor = this.checked ? '#3b82f6' : '#9ca3af'; this.closest('label').querySelector('.checkbox-icon').style.opacity = this.checked ? '1' : '0';">
                                        <div class="checkbox-indicator w-6 h-6 border-2 border-gray-400 rounded-md flex items-center justify-center flex-shrink-0 transition-all duration-300 peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-blue-600 peer-checked:border-blue-500 peer-checked:shadow-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="checkbox-icon w-4 h-4 text-white opacity-0 scale-50 transition-all duration-300 peer-checked:opacity-100 peer-checked:scale-100">
                                                <path fill-rule="evenodd"
                                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @else
                                        <input type="radio" name="answers_{{ $question->id }}" value="{{ $choice->id }}"
                                            wire:key="radio-{{ $question->id }}-{{ $choice->id }}"
                                            wire:model.live="answers.{{ $question->id }}"
                                            wire:click="updateAnswer('{{ $question->id }}', '{{ $choice->id }}')"
                                            @if(isset($this->answers[$question->id]) && $this->answers[$question->id] == $choice->id)
                                            checked @endif
                                            class="peer sr-only"
                                            onchange="this.closest('label').querySelector('.radio-indicator').style.backgroundColor = this.checked ? '#3b82f6' : 'transparent'; this.closest('label').querySelector('.radio-indicator').style.borderColor = this.checked ? '#3b82f6' : '#9ca3af'; this.closest('label').querySelector('.radio-dot').style.opacity = this.checked ? '1' : '0';">
                                        <div class="radio-indicator w-6 h-6 border-2 border-gray-400 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300 peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-blue-600 peer-checked:border-blue-500 peer-checked:shadow-md">
                                            <div class="radio-dot w-2.5 h-2.5 bg-white rounded-full opacity-0 scale-50 transition-all duration-300"></div>
                                        </div>
                                    @endif
                                    <span class="ml-4 text-gray-700 dark:text-gray-300 peer-checked:text-blue-900 dark:peer-checked:text-white peer-checked:font-semibold transition-all duration-300">{!! $choice->title !!}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sidebar: Info, Timer, and Actions -->
            <div class="space-y-6">
                <!-- Quiz Header Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="space-y-4">

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            @if(!$this->isUnlimited)
                                <div class="text-center p-4 rounded-xl shadow-sm" :class="{ 'bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-700': timeWarning, 'bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700': !timeWarning }">
                                    <div class="flex items-center justify-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="text-xs font-semibold uppercase tracking-wide">Thời gian còn lại</div>
                                    </div>
                                    <div class="text-2xl font-mono font-bold" x-text="formatTime(remainingSeconds)"></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        Tổng thời gian: {{ $this->quizModel->time_limit_minutes }} phút
                                    </div>
                                </div>
                            @else
                                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/20 text-blue-700 dark:text-blue-300 rounded-xl border border-blue-200 dark:border-blue-700 shadow-sm">
                                    <div class="flex items-center justify-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="text-xs font-semibold uppercase tracking-wide">Không giới hạn</div>
                                    </div>
                                    <div class="text-2xl font-bold">∞</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6 space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Tiến độ:
                                {{ $this->answeredCount }}/{{ $this->quizModel->questions->count() }} câu</span>
                            <span class="font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded-lg">{{ $this->progressPercentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 shadow-inner">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500 ease-out shadow-sm" style="width: {{ $this->progressPercentage }}%"></div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button
                                @click="confirmSubmit()"
                                :disabled="submitting"
                                class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 hover:shadow-lg disabled:hover:scale-100 disabled:hover:shadow-none flex items-center justify-center gap-3 group">
                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span x-show="!submitting">Nộp bài</span>
                                <span x-show="submitting" class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Đang nộp...
                                </span>
                            </button>

                            <div class="mt-3 text-center">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Bạn không thể thay đổi sau khi nộp bài
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


             </div>
         </div>

         <script>
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
                         this.$watch('$wire.submitting', (value) => {
                             this.submitting = value;
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

     {{-- Custom Actions Demo --}}
     <div class="mt-6 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
         <div class="flex gap-4">
             {{ $this->customBackAction }}
             {{ $this->customSubmitAction }}
         </div>
     </div>

     {{-- Required for Filament Actions modals --}}
     <x-filament-actions::modals />
</x-filament-panels::page>
