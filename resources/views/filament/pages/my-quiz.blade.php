<x-filament-panels::page>

    {{-- ====================================================================== --}}
    {{-- ======================= PURE CSS STYLESHEET ========================== --}}
    {{-- ====================================================================== --}}
    <style>
        :root {
            /* Color Palette */
            --color-primary: #3b82f6;
            /* blue-500 */
            --color-primary-dark: #60a5fa;
            /* blue-400 */
            --color-primary-light: #eff6ff;
            /* blue-100 */
            --color-primary-text-light: #1e40af;
            /* blue-800 */
            --color-primary-dark-bg: #1e3a8a;
            /* blue-900 */
            --color-primary-text-dark: #bfdbfe;
            /* blue-200 */

            --color-success: #16a34a;
            /* green-600 */
            --color-success-hover: #15803d;
            /* green-700 */
            --color-success-dark: #4ade80;
            /* green-400 */

            --color-warning: #f59e0b;
            /* amber-500 */
            --color-warning-hover: #d97706;
            /* amber-600 */
            --color-warning-dark: #fbbf24;
            /* amber-400 */

            --color-text-light-primary: #1e293b;
            /* slate-800 */
            --color-text-light-secondary: #64748b;
            /* slate-500 */
            --color-text-dark-primary: #f1f5f9;
            /* slate-100 */
            --color-text-dark-secondary: #94a3b8;
            /* slate-400 */

            --bg-light-page: #f1f5f9;
            /* slate-100 */
            --bg-light-card: #ffffff;
            --bg-light-section: #f8fafc;
            /* slate-50 */
            --bg-dark-page: #0f172a;
            /* slate-900 */
            --bg-dark-card: #1e293b;
            /* slate-800 */
            --bg-dark-section: #0f172a;
            /* slate-900 */

            --border-light: #e2e8f0;
            /* slate-200 */
            --border-dark: #334155;
            /* slate-700 */

            /* Spacing & Sizing */
            --spacing-xs: 0.5rem;
            --spacing-sm: 0.75rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;

            /* Borders & Shadows */
            --border-radius-md: 0.75rem;
            /* rounded-lg */
            --border-radius-lg: 1rem;
            /* rounded-2xl */
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        /* Base Page Layout */
        .quiz-list-page {
            min-height: 100vh;
            background-color: var(--bg-light-page);
        }

        .dark .quiz-list-page {
            background-color: var(--bg-dark-page);
        }

        .quiz-list-container {
            max-width: 80rem;
            /* max-w-7xl */
            margin-left: auto;
            margin-right: auto;
            padding: 2rem 1rem;
        }

        /* Stats Section */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
        }

        .stat-card {
            background-color: var(--bg-light-card);
            padding: var(--spacing-lg);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
        }

        .dark .stat-card {
            background-color: var(--bg-dark-card);
            border-color: var(--border-dark);
        }

        .stat-card .stat-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-light-secondary);
        }

        .dark .stat-card .stat-label {
            color: var(--color-text-dark-secondary);
        }

        .stat-card .stat-value {
            margin-top: 0.25rem;
            font-size: 1.875rem;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .stat-card .stat-value.total {
            color: var(--color-primary);
        }

        .dark .stat-card .stat-value.total {
            color: var(--color-primary-dark);
        }

        .stat-card .stat-value.completed {
            color: var(--color-success);
        }

        .dark .stat-card .stat-value.completed {
            color: var(--color-success-dark);
        }

        .stat-card .stat-value.highest {
            color: var(--color-warning);
        }

        .dark .stat-card .stat-value.highest {
            color: var(--color-warning-dark);
        }

        /* Filter Bar */
        .filter-bar {
            margin-bottom: var(--spacing-xl);
            background-color: var(--bg-light-card);
            padding: var(--spacing-md);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
        }

        .dark .filter-bar {
            background-color: var(--bg-dark-card);
            border-color: var(--border-dark);
        }

        .filter-controls {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .filter-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-light-primary);
        }

        .dark .filter-label {
            color: var(--color-text-dark-primary);
        }

        .filter-select-wrapper {
            flex-grow: 1;
            min-width: 200px;
        }

        .filter-select {
            width: 100%;
            border-radius: var(--border-radius-md);
            border: 1px solid var(--border-light);
            background-color: var(--bg-light-card);
            color: var(--color-text-light-primary);
            padding: var(--spacing-xs) var(--spacing-sm);
        }

        .dark .filter-select {
            border-color: var(--border-dark);
            background-color: #334155;
            color: var(--color-text-dark-primary);
        }

        .filter-clear-button {
            padding: var(--spacing-xs) var(--spacing-sm);
            font-size: 0.875rem;
            background-color: #f1f5f9;
            color: #475569;
            border-radius: var(--border-radius-md);
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .dark .filter-clear-button {
            background-color: #334155;
            color: #cbd5e1;
        }

        .filter-clear-button:hover {
            background-color: #e2e8f0;
        }

        .dark .filter-clear-button:hover {
            background-color: #475569;
        }

        /* Quiz List */
        .quiz-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-lg);
        }

        /* Quiz List Item */
        .quiz-list-item {
            display: flex;
            flex-direction: column;
            background-color: var(--bg-light-card);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s ease-in-out;
        }

        .dark .quiz-list-item {
            background-color: var(--bg-dark-card);
            border-color: var(--border-dark);
        }

        .quiz-list-item:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-0.25rem);
        }

        .quiz-item-main {
            padding: var(--spacing-lg);
            flex-grow: 1;
        }

        .quiz-item-meta {
            padding: var(--spacing-lg);
            background-color: var(--bg-light-section);
            border-top: 1px solid var(--border-light);
        }

        .dark .quiz-item-meta {
            background-color: var(--bg-dark-section);
            border-top-color: var(--border-dark);
        }

        .quiz-course-badge {
            display: inline-block;
            background-color: var(--color-primary-light);
            color: var(--color-primary-text-light);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            margin-bottom: var(--spacing-sm);
        }

        .dark .quiz-course-badge {
            background-color: var(--color-primary-dark-bg);
            color: var(--color-primary-text-dark);
        }

        .quiz-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-text-light-primary);
            margin-bottom: var(--spacing-md);
            line-height: 1.4;
        }

        .dark .quiz-title {
            color: var(--color-text-dark-primary);
        }

        .quiz-details {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-md);
        }

        .quiz-detail-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            font-size: 0.875rem;
            color: var(--color-text-light-secondary);
        }

        .dark .quiz-detail-item {
            color: var(--color-text-dark-secondary);
        }

        .quiz-detail-item .icon {
            width: 1.25rem;
            height: 1.25rem;
        }

        .quiz-detail-item .icon.questions {
            color: var(--color-primary);
        }

        .dark .quiz-detail-item .icon.questions {
            color: var(--color-primary-dark);
        }

        .quiz-detail-item .icon.time {
            color: var(--color-success);
        }

        .dark .quiz-detail-item .icon.time {
            color: var(--color-success-dark);
        }

        .quiz-detail-item .icon.points {
            color: var(--color-warning);
        }

        .dark .quiz-detail-item .icon.points {
            color: var(--color-warning-dark);
        }

        .quiz-detail-item span {
            font-weight: 500;
        }

        /* Quiz Status Info */
        .quiz-status-info {
            margin-bottom: var(--spacing-md);
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            margin-bottom: var(--spacing-xs);
        }

        .status-item:last-child {
            margin-bottom: 0;
        }

        .status-icon {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        .status-icon.completed {
            color: #10b981;
        }

        .status-icon.score {
            color: #f59e0b;
        }

        .status-text {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-light-primary);
        }

        .dark .status-text {
            color: var(--color-text-dark-primary);
        }

        /* --- [MODIFIED] Actions Container --- */
        .quiz-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* When only one button exists, align it to the right on mobile */
        .quiz-actions:has(> *:only-child) {
            align-items: flex-end;
        }

        /* --- [MODIFIED] Action Buttons --- */
        .quiz-actions>* {
            width: 100%;
            /* Default to full width for multiple buttons on mobile */
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius-md);
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-xs);
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
        }

        .quiz-actions>*:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* When there's only one button, it should not be full width */
        .quiz-actions>*:only-child {
            width: auto;
        }

        .quiz-actions .action-icon {
            width: 1.25rem;
            height: 1.25rem;
        }

        /* Button Style Variations */
        .quiz-actions .action-primary {
            background-color: var(--color-success);
            color: white;
        }

        .quiz-actions .action-primary:hover {
            background-color: var(--color-success-hover);
        }

        .quiz-actions .action-secondary {
            background-color: var(--bg-light-card);
            color: var(--color-text-light-secondary);
            border-color: var(--border-light);
        }

        .dark .quiz-actions .action-secondary {
            background-color: var(--bg-dark-card);
            color: var(--color-text-dark-secondary);
            border-color: var(--border-dark);
        }

        .quiz-actions .action-secondary:hover {
            border-color: var(--color-text-light-secondary);
            color: var(--color-text-light-primary);
        }

        .dark .quiz-actions .action-secondary:hover {
            border-color: var(--color-text-dark-secondary);
            color: var(--color-text-dark-primary);
        }

        .quiz-actions .action-continue {
            background-color: var(--color-warning);
            color: white;
        }

        .quiz-actions .action-continue:hover {
            background-color: var(--color-warning-hover);
        }

        .quiz-actions .action-disabled {
            background-color: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .dark .quiz-actions .action-disabled {
            background-color: #334155;
            color: #64748b;
        }

        .dark .quiz-actions .action-disabled:hover {
            transform: none;
            box-shadow: none;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 0;
        }

        .empty-state-icon-wrapper {
            margin: 0 auto 1.5rem auto;
            width: 6rem;
            height: 6rem;
            background-color: #e2e8f0;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dark .empty-state-icon-wrapper {
            background-color: #1e293b;
        }

        .empty-state-icon {
            width: 3rem;
            height: 3rem;
            color: #94a3b8;
        }

        .dark .empty-state-icon {
            color: #475569;
        }

        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--color-text-light-primary);
            margin-bottom: 0.5rem;
        }

        .dark .empty-state-title {
            color: var(--color-text-dark-primary);
        }

        .empty-state-text {
            color: var(--color-text-light-secondary);
        }

        .dark .empty-state-text {
            color: var(--color-text-dark-secondary);
        }

        /* Responsive Design */
        @media (min-width: 768px) {

            /* md */
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {

            /* lg */
            .quiz-list-item {
                flex-direction: row;
                align-items: stretch;
            }

            .quiz-item-main {
                border-right: 1px solid var(--border-light);
            }

            .dark .quiz-item-main {
                border-right-color: var(--border-dark);
            }

            .quiz-item-meta {
                border-top: none;
                flex-shrink: 0;
                width: 320px;
            }

            .quiz-actions {
                flex-direction: row;
                justify-content: flex-end;
            }

            .quiz-actions>* {
                width: auto;
                /* Buttons take natural width on desktop */
                flex-grow: 1;
                /* But grow to fill space if multiple */
            }

            .quiz-actions>*:only-child {
                flex-grow: 0;
                /* A single button does not grow */
            }
        }
    </style>

    <div class="quiz-list-page">
        <div class="quiz-list-container">

            <!-- Stats Section -->
            <div class="stats-grid">
                <div class="stat-card">
                    <p class="stat-label">Tổng số Quiz</p>
                    <p class="stat-value total">{{ $this->getQuizzes()->count() }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Quiz đã hoàn thành</p>
                    <p class="stat-value completed">{{ $this->getCompletedQuizzesCount() }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Điểm cao nhất</p>
                    <p class="stat-value highest">{{ $this->getHighestScore() }}%</p>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-controls">
                    <div class="filter-group">
                        <label class="filter-label">Lọc theo khóa học:</label>
                        <div class="filter-select-wrapper">
                            <select wire:model.live="selectedCourseId" class="filter-select">
                                <option value="">Tất cả khóa học</option>
                                @foreach($this->getUserCourses() as $course)
                                    <option value="{{ $course->id }}">{{ $course->title ?? $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Lọc theo trạng thái:</label>
                        <div class="filter-select-wrapper">
                            <select wire:model.live="selectedStatus" class="filter-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="completed">Đã hoàn thành</option>
                                <option value="in_progress">Đang làm bài</option>
                                <option value="available">Có thể làm bài</option>
                            </select>
                        </div>
                    </div>

                    @if($selectedCourseId || $selectedStatus)
                        <button wire:click="$set('selectedCourseId', null); $set('selectedStatus', null)"
                            class="filter-clear-button">
                            Xóa tất cả bộ lọc
                        </button>
                    @endif
                </div>
            </div>

            <!-- Quiz List -->
            <div class="quiz-list">
                @forelse($this->getQuizzes() as $quiz)
                    <div class="quiz-list-item">
                        <!-- Main Info Section -->
                        <div class="quiz-item-main">
                            <p class="quiz-course-badge">Khóa học: {{ $quiz->course->title ?? $quiz->course->name }}</p>
                            <h3 class="quiz-title">{{ $quiz->title }}</h3>
                            <div class="quiz-details">
                                <div class="quiz-detail-item">
                                    <x-heroicon-o-question-mark-circle class="icon questions" />
                                    <span>{{ $quiz->questions->count() }} câu hỏi</span>
                                </div>
                                <div class="quiz-detail-item">
                                    <x-heroicon-o-clock class="icon time" />
                                    <span>{{ $quiz->time_limit_minutes ?? 'Không giới hạn' }} phút</span>
                                </div>
                                <div class="quiz-detail-item">
                                    <x-heroicon-o-star class="icon points" />
                                    <span>{{ $quiz->max_points ?? 'N/A' }} điểm tối đa</span>
                                </div>
                            </div>
                        </div>

                        <!-- Meta & Actions Section -->
                        <div class="quiz-item-meta">
                            @php
                                $quizStatus = $this->getQuizStatus($quiz);
                                $bestScore = $this->getStudentQuizBestScore($quiz);
                                $completedAttempts = $this->quizService->getStudentAttempts($quiz->id, Auth::id())->whereIn('status', ['completed', 'submitted']);
                            @endphp

                            @if($completedAttempts->isNotEmpty())
                                <div class="quiz-status-info">
                                    <div class="status-item">
                                        <x-heroicon-o-check-circle class="status-icon completed" />
                                        <span class="status-text">Đã hoàn thành {{ $completedAttempts->count() }} lần</span>
                                    </div>
                                    @if($bestScore !== null)
                                        <div class="status-item">
                                            <x-heroicon-o-star class="status-icon score" />
                                            <span class="status-text">Điểm cao nhất: {{ number_format($bestScore, 1) }}%</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="quiz-actions">
                                @if($quizStatus['status'] === 'in_progress')
                                    <a href="{{ route('filament.app.pages.quiz-taking', ['quiz' => $quiz->id]) }}"
                                        class="action-continue">
                                        <x-heroicon-o-play class="action-icon" />
                                        <span>{{ $quizStatus['label'] }}</span>
                                    </a>
                                @elseif($quizStatus['canTake'])
                                    {{ ($this->takeQuizAction)(['quiz' => $quiz->id])->extraAttributes(['class' => 'action-primary']) }}
                                @endif

                                @if($quizStatus['canViewResults'])
                                    {{ ($this->viewResultsAction)(['quiz' => $quiz->id])->extraAttributes(['class' => 'action-primary']) }}
                                @endif

                                @if($completedAttempts->count() > 1)
                                    {{ ($this->viewHistoryAction)(['quiz' => $quiz->id])->extraAttributes(['class' => 'action-secondary']) }}
                                @endif

                                @if(!$quizStatus['canTake'] && !$quizStatus['canViewResults'] && $quizStatus['status'] !== 'in_progress')
                                    <button disabled class="action-disabled">
                                        <x-heroicon-o-lock-closed class="action-icon" />
                                        <span>{{ $quizStatus['label'] }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon-wrapper">
                            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="empty-state-title">Chưa có quiz nào</h3>
                        <p class="empty-state-text">Bạn chưa được gán vào khóa học nào có quiz hoặc không có quiz nào khớp
                            với bộ lọc.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>