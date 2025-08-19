<x-filament-panels::page>
    <div x-data="quizTakingApp()" x-init="init()" class="min-h-screen bg-gray-100 dark:bg-gray-900 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Main Content: Questions -->
            <div class="lg:col-span-2 space-y-6">
                @foreach($this->quizModel->questions as $index => $question)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex justify-between items-start gap-4 mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white leading-relaxed">Câu {{ $index + 1 }}: {{ $question->title }}</h3>
                            <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-medium px-2.5 py-1 rounded-full whitespace-nowrap">{{ $question->points }} điểm</span>
                        </div>

                        @if($question->question_image)
                            <div class="mb-4">
                                <img src="{{ $question->question_image }}" alt="Question Image" class="max-w-full h-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            </div>
                        @endif

                        @if($question->is_multiple_response)
                            <p class="text-sm italic text-gray-600 dark:text-gray-400 mb-2 pl-2">Chọn một hoặc nhiều đáp án</p>
                        @endif

                        <div class="space-y-3">
                            @foreach($question->answerChoices as $choice)
                                <label class="flex items-center p-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-500 hover:bg-gray-50 dark:hover:bg-gray-700 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20">
                                    @if($question->is_multiple_response)
                                        <input type="checkbox" name="answers_{{ $question->id }}[]" value="{{ $choice->id }}"
                                            wire:key="checkbox-{{ $question->id }}-{{ $choice->id }}"
                                            wire:click="updateAnswer('{{ $question->id }}', '{{ $choice->id }}')"
                                            @if(isset($this->answers[$question->id]) && is_array($this->answers[$question->id]) && in_array($choice->id, $this->answers[$question->id])) checked @endif
                                            class="sr-only">
                                        <div class="w-5 h-5 border-2 border-gray-400 rounded flex items-center justify-center flex-shrink-0 transition-all duration-200 peer-checked:bg-blue-500 peer-checked:border-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-white opacity-0 scale-50 transition-all duration-200 peer-checked:opacity-100 peer-checked:scale-100">
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
                                            class="sr-only">
                                        <div class="w-5 h-5 border-2 border-gray-400 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-200 peer-checked:bg-blue-500 peer-checked:border-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-white opacity-0 scale-50 transition-all duration-200 peer-checked:opacity-100 peer-checked:scale-100">
                                                <path fill-rule="evenodd"
                                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="ml-3 text-gray-600 dark:text-gray-400 peer-checked:text-blue-800 dark:peer-checked:text-white peer-checked:font-medium">{{ $choice->title }}</span>
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
                        <div class="space-y-2">
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $this->quizModel->title }}</h1>
                            <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ $this->quizModel->course->title }}</p>
                            @if($this->quizModel->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $this->quizModel->description }}</p>
                            @endif
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            @if(!$this->isUnlimited)
                                <div class="text-center p-3 rounded-lg" :class="{ 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300': timeWarning, 'bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300': !timeWarning }">
                                    <div class="text-xs font-medium mb-1">Thời gian còn lại</div>
                                    <div class="text-lg font-mono font-bold" x-text="formatTime(remainingSeconds)"></div>
                                </div>
                            @else
                                <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-lg">
                                    <div class="text-xs font-medium mb-1">Thời gian</div>
                                    <div class="text-lg font-bold">Không giới hạn</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Tiến độ:
                                {{ $this->answeredCount }}/{{ $this->quizModel->questions->count() }} câu</span>
                            <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $this->progressPercentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: {{ $this->progressPercentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Submit Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="space-y-4">
                        <x-filament::button color="gray" icon="heroicon-o-arrow-left"
                             href="{{ route('filament.app.pages.my-quiz') }}"
                             class="w-full">
                             Quay lại
                         </x-filament::button>
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
                     attemptId: @js($this->attemptModel->id ?? null),

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
                         const timer = setInterval(() => {
                             this.remainingSeconds--;
                             this.timeWarning = this.remainingSeconds <= 300;
                             if (this.remainingSeconds <= 0) {
                                 clearInterval(timer);
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
                         if (confirm('Bạn có chắc chắn muốn nộp bài? Bạn không thể thay đổi sau khi nộp.')) {
                             this.clearStorage();
                             // wire:click handles the rest
                         } else {
                             event.preventDefault();
                         }
                     },

                     autoSubmit() {
                         alert('Hết thời gian! Bài quiz sẽ được nộp tự động.');
                         this.clearStorage();
                         this.$wire.call('submitQuiz');
                     }
                 }
             }
         </script>
     </div>
</x-filament-panels::page>