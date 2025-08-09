<x-filament-panels::page>

    {{-- ====================================================================== --}}
    {{-- ======================= PURE CSS STYLESHEET (v3) ===================== --}}
    {{-- ====================================================================== --}}
    <style>
        :root {
            /* Color Palette */
            --color-primary: #3b82f6;
            /* blue-600 */
            --color-primary-light: #eff6ff;
            /* blue-100 */
            --color-primary-dark-bg: rgba(59, 130, 246, 0.15);
            --color-primary-text-light: #1e40af;
            /* blue-800 */
            --color-primary-text-dark: #bfdbfe;
            /* blue-200 */

            --color-danger: #ef4444;
            /* red-500 */
            --color-success: #22c55e;
            /* green-500 */

            --color-text-light-primary: #111827;
            /* gray-900 */
            --color-text-light-secondary: #4b5563;
            /* gray-600 */
            --color-text-light-tertiary: #6b7280;
            /* gray-500 */

            --color-text-dark-primary: #ffffff;
            --color-text-dark-secondary: #9ca3af;
            /* gray-400 */
            --color-text-dark-tertiary: #6b7280;
            /* gray-500 */

            --bg-light-page: #f3f4f6;
            /* gray-100 */
            --bg-light-card: #ffffff;
            --bg-light-hover: #f9fafb;
            /* gray-50 */

            --bg-dark-page: #111827;
            /* gray-900 */
            --bg-dark-card: #1f2937;
            /* gray-800 */
            --bg-dark-hover: #374151;
            /* gray-700 */

            --border-light: #e5e7eb;
            /* gray-200 */
            --border-dark: #374151;
            /* gray-700 */

            /* Sizing & Spacing */
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --border-radius: 0.75rem;
            /* rounded-xl */
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.07);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        /* Keyframe Animations */
        @keyframes pulse {
            50% {
                opacity: .5;
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Main Layout */
        .quiz-taking-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-lg);
            align-items: flex-start;
        }

        .quiz-main-content {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-lg);
        }

        .quiz-sidebar {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-lg);
        }

        .quiz-card {
            background-color: var(--bg-light-card);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: var(--spacing-lg);
            border: 1px solid var(--border-light);
        }

        .dark .quiz-card {
            background-color: var(--bg-dark-card);
            border-color: var(--border-dark);
        }

        /* Header Section */
        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: var(--spacing-md);
        }

        .quiz-header-info .title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-text-light-primary);
        }

        .dark .quiz-header-info .title {
            color: var(--color-text-dark-primary);
        }

        .quiz-header-info .course {
            margin-top: 0.25rem;
            color: var(--color-text-light-secondary);
        }

        .dark .quiz-header-info .course {
            color: var(--color-text-dark-secondary);
        }

        .quiz-header-info .description {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--color-text-light-tertiary);
        }

        .dark .quiz-header-info .description {
            color: var(--color-text-dark-tertiary);
        }

        /* Timer */
        .quiz-timer {
            text-align: right;
            flex-shrink: 0;
        }

        .quiz-timer .time-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-primary);
            font-family: monospace;
        }

        .quiz-timer .time-display.time-warning {
            color: var(--color-danger);
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .quiz-timer .time-unlimited {
            font-size: 1rem;
            font-weight: 500;
            color: var(--color-text-light-secondary);
        }

        .dark .quiz-timer .time-unlimited {
            color: var(--color-text-dark-secondary);
        }

        /* Progress Bar */
        .quiz-progress {
            margin-top: var(--spacing-lg);
        }

        .quiz-progress-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.875rem;
            color: var(--color-text-light-secondary);
            margin-bottom: 0.5rem;
        }

        .dark .quiz-progress-info {
            color: var(--color-text-dark-secondary);
        }

        .quiz-progress-bar-track {
            width: 100%;
            background-color: #e5e7eb;
            border-radius: 9999px;
            height: 0.75rem;
        }

        .dark .quiz-progress-bar-track {
            background-color: #374151;
        }

        .quiz-progress-bar-fill {
            background-color: var(--color-primary);
            height: 0.75rem;
            border-radius: 9999px;
            transition: width 0.3s ease-in-out;
        }

        /* Question Section */
        .question-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: var(--spacing-md);
            gap: var(--spacing-md);
        }

        .question-title {
            font-size: 1.125rem;
            font-weight: 600;
            line-height: 1.6;
            color: var(--color-text-light-primary);
        }

        .dark .question-title {
            color: var(--color-text-dark-primary);
        }

        .question-points {
            background-color: var(--color-primary-light);
            color: var(--color-primary-text-light);
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.125rem 0.625rem;
            border-radius: 9999px;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .dark .question-points {
            background-color: #1e3a8a;
            color: var(--color-primary-text-dark);
        }

        .question-image {
            margin-bottom: var(--spacing-md);
        }

        .question-image img {
            max-width: 100%;
            height: auto;
            border-radius: var(--border-radius);
            border: 1px solid var(--border-light);
        }

        .dark .question-image img {
            border-color: var(--border-dark);
        }

        /* [NEW] Helper text for multiple choice questions */
        .question-helper-text {
            font-size: 0.875rem;
            font-style: italic;
            color: var(--color-text-light-secondary);
            margin-bottom: var(--spacing-sm);
            padding-left: var(--spacing-sm);
        }

        .dark .question-helper-text {
            color: var(--color-text-dark-secondary);
        }

        /* Answer Choices */
        .answer-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .answer-option {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border: 2px solid var(--border-light);
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .dark .answer-option {
            border-color: var(--border-dark);
        }

        .answer-option:hover {
            border-color: var(--color-primary);
            background-color: var(--bg-light-hover);
        }

        .dark .answer-option:hover {
            border-color: var(--color-primary);
            background-color: var(--bg-dark-hover);
        }

        .answer-option input[type="radio"],
        .answer-option input[type="checkbox"] {
            display: none;
            /* Hide the default input */
        }

        .answer-option .answer-text {
            margin-left: 0.75rem;
            color: var(--color-text-light-secondary);
        }

        .dark .answer-option .answer-text {
            color: var(--color-text-dark-secondary);
        }

        /* [NEW] Custom radio/checkbox icon */
        .answer-icon {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #9ca3af;
            /* gray-400 */
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out;
        }

        .answer-icon.radio {
            border-radius: 9999px;
        }

        .answer-icon.checkbox {
            border-radius: 0.25rem;
        }

        .answer-icon svg {
            width: 0.875rem;
            height: 0.875rem;
            color: white;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.2s ease-in-out;
        }

        /* Selected Answer State */
        .answer-option:has(input:checked) {
            border-color: var(--color-primary);
            background-color: var(--color-primary-light);
        }

        .dark .answer-option:has(input:checked) {
            background-color: var(--color-primary-dark-bg);
        }

        .answer-option:has(input:checked) .answer-text {
            color: var(--color-primary-text-light);
            font-weight: 500;
        }

        .dark .answer-option:has(input:checked) .answer-text {
            color: var(--color-text-dark-primary);
        }

        .answer-option:has(input:checked) .answer-icon {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .answer-option:has(input:checked) .answer-icon svg {
            opacity: 1;
            transform: scale(1);
        }

        /* Submit Section */
        .submit-section {
            text-align: center;
        }

        .submit-section .notice {
            font-size: 0.875rem;
            color: var(--color-text-light-secondary);
            margin-bottom: var(--spacing-md);
        }

        .dark .submit-section .notice {
            color: var(--color-text-dark-secondary);
        }

        .submit-section .actions {
            display: flex;
            justify-content: center;
            gap: var(--spacing-md);
        }

        .submit-section .spinner {
            animation: spin 1s linear infinite;
            margin-right: 0.5rem;
            margin-left: -0.25rem;
            width: 1rem;
            height: 1rem;
            color: white;
        }

        /* Responsive Layout */
        @media (min-width: 1024px) {
            .quiz-taking-layout {
                grid-template-columns: 2fr 1fr;
            }

            .quiz-sidebar {
                position: sticky;
                top: 2rem;
                /* Adjust this value based on your header height */
            }
        }
    </style>

    <div x-data="quizTakingApp()" x-init="init()">
        <div class="quiz-taking-layout">
            <!-- Main Content: Questions -->
            <div class="quiz-main-content">
                @foreach($this->quizModel->questions as $index => $question)
                    <div class="quiz-card">
                        <div class="question-header">
                            <h3 class="question-title">Câu {{ $index + 1 }}: {{ $question->title }}</h3>
                            <span class="question-points">{{ $question->points }} điểm</span>
                        </div>

                        @if($question->question_image)
                            <div class="question-image">
                                <img src="{{ $question->question_image }}" alt="Question Image">
                            </div>
                        @endif

                        @if($question->is_multiple_response)
                            <p class="question-helper-text">Chọn một hoặc nhiều đáp án</p>
                        @endif

                        <div class="answer-list">
                            @foreach($question->answerChoices as $choice)
                                <label class="answer-option">
                                    @if($question->is_multiple_response)
                                        <input type="checkbox" name="answers_{{ $question->id }}[]" value="{{ $choice->id }}"
                                            wire:key="checkbox-{{ $question->id }}-{{ $choice->id }}"
                                            wire:click="updateAnswer('{{ $question->id }}', '{{ $choice->id }}')"
                                            @if(isset($this->answers[$question->id]) && is_array($this->answers[$question->id]) && in_array($choice->id, $this->answers[$question->id])) checked @endif>
                                        <span class="answer-icon checkbox">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @else
                                        <input type="radio" name="answers_{{ $question->id }}" value="{{ $choice->id }}"
                                            wire:key="radio-{{ $question->id }}-{{ $choice->id }}"
                                            wire:model.live="answers.{{ $question->id }}"
                                            wire:click="updateAnswer('{{ $question->id }}', '{{ $choice->id }}')"
                                            @if(isset($this->answers[$question->id]) && $this->answers[$question->id] == $choice->id)
                                            checked @endif>
                                        <span class="answer-icon radio">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @endif
                                    <span class="answer-text">{{ $choice->title }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sidebar: Info, Timer, and Actions -->
            <div class="quiz-sidebar">
                <!-- Quiz Header Card -->
                <div class="quiz-card">
                    <div class="quiz-header">
                        <div class="quiz-header-info">
                            <h1 class="title">{{ $this->quizModel->title }}</h1>
                            <p class="course">{{ $this->quizModel->course->title }}</p>
                            @if($this->quizModel->description)
                                <p class="description">{{ $this->quizModel->description }}</p>
                            @endif
                        </div>
                        <div class="quiz-timer">
                            @if(!$this->isUnlimited)
                                <div class="time-display" :class="{ 'time-warning': timeWarning }">
                                    <span x-text="formatTime(remainingSeconds)"></span>
                                </div>
                            @else
                                <div class="time-unlimited">Không giới hạn</div>
                            @endif
                        </div>
                    </div>
                    <div class="quiz-progress">
                        <div class="quiz-progress-info">
                            <span class="progress-text">Tiến độ:
                                {{ $this->answeredCount }}/{{ $this->quizModel->questions->count() }} câu</span>
                            <span class="progress-percentage">{{ $this->progressPercentage }}%</span>
                        </div>
                        <div class="quiz-progress-bar-track">
                            <div class="quiz-progress-bar-fill" style="width: {{ $this->progressPercentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Submit Card -->
                <div class="quiz-card submit-section">
                    <div class="actions">
                        <x-filament::button color="gray" icon="heroicon-o-arrow-left"
                            href="{{ route('filament.app.pages.my-quiz') }}">

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

                        const progressBar = document.querySelector('.quiz-progress-bar-fill');
                        const progressText = document.querySelector('.progress-text');
                        const progressPercentage = document.querySelector('.progress-percentage');

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