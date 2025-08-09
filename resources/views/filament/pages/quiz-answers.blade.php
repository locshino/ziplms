<x-filament-panels::page>

{{-- ====================================================================== --}}
{{-- ======================= PURE CSS STYLES (REFINED) ==================== --}}
{{-- ====================================================================== --}}
<style>
    :root {
        --primary-color: #4f46e5;
        --primary-color-light: #e0e7ff;
        --primary-color-dark: #818cf8;
        --green-color: #16a34a;
        --green-color-light: #f0fdf4;
        --red-color: #dc2626;
        --red-color-light: #fef2f2;
        --gray-text: #6b7280;
        --dark-gray-text: #9ca3af;
        --card-bg: #ffffff;
        --card-border: #e5e7eb;
        --dark-card-bg: rgb(31 41 55 / 0.8);
        --dark-card-border: #374151;
        --body-bg: #f9fafb;
        --dark-body-bg: #111827;
    }

    /* General Container */
    .quiz-container {
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* Card Base Style */
    .card {
        background-color: var(--card-bg);
        border-radius: 1rem;
        border: 1px solid var(--card-border);
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.07), 0 1px 2px -1px rgb(0 0 0 / 0.07);
        padding: 1.5rem;
    }
    .dark .card {
        background-color: var(--dark-card-bg);
        border-color: var(--dark-card-border);
    }

    /* Header Section */
    .quiz-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .quiz-header .header-content { flex: 1; }
    .quiz-header h1 {
        font-size: 1.875rem; /* text-3xl */
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    .dark .quiz-header h1 { color: #ffffff; }
    .quiz-header .subheading {
        font-size: 1.125rem; /* text-lg */
        font-weight: 500;
        color: var(--primary-color);
    }
    .dark .quiz-header .subheading { color: var(--primary-color-dark); }
    .quiz-header .info-group {
        margin-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .quiz-header .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--gray-text);
        font-size: 0.875rem;
    }
    .dark .quiz-header .info-item { color: var(--dark-gray-text); }
    .quiz-header .info-item strong {
        font-weight: 600;
        color: #111827;
    }
    .dark .quiz-header .info-item strong { color: #ffffff; }
    .quiz-header .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: #f3f4f6;
        border-radius: 0.5rem;
        font-weight: 600;
        color: #1f2937;
        transition: background-color 0.2s;
        text-decoration: none;
        border: 1px solid var(--card-border);
    }
    .quiz-header .back-button:hover { background-color: #e5e7eb; }
    .dark .quiz-header .back-button {
        background-color: #374151;
        color: #d1d5db;
        border-color: #4b5563;
    }
    .dark .quiz-header .back-button:hover { background-color: #4b5563; }

    /* Stats Section */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1rem;
    }
    .stat-card {
        text-align: center;
        padding: 1.5rem 1rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }
    .stat-card .icon-wrapper {
        margin: 0 auto 1rem auto;
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-card .stat-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-text);
        margin-bottom: 0.25rem;
    }
    .dark .stat-card .stat-title { color: var(--dark-gray-text); }
    .stat-card .stat-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.2;
    }
    .dark .stat-card .stat-value { color: #ffffff; }
    .stat-card .stat-extra {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-text);
    }

    /* Icon Colors */
    .icon-wrapper.primary { background-color: var(--primary-color-light); color: var(--primary-color); }
    .dark .icon-wrapper.primary { background-color: rgb(79 70 229 / 0.2); color: var(--primary-color-dark); }
    .icon-wrapper.green { background-color: var(--green-color-light); color: var(--green-color); }
    .dark .icon-wrapper.green { background-color: rgb(22 163 74 / 0.2); color: #4ade80; }
    .icon-wrapper.red { background-color: var(--red-color-light); color: var(--red-color); }
    .dark .icon-wrapper.red { background-color: rgb(220 38 38 / 0.2); color: #f87171; }
    .icon-wrapper.gray { background-color: #f3f4f6; color: var(--gray-text); }
    .dark .icon-wrapper.gray { background-color: #374151; color: var(--dark-gray-text); }

    /* Detailed Answers Section */
    .details-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    .dark .details-title { color: #ffffff; }
    .details-subtitle {
        color: var(--gray-text);
        margin-bottom: 1.5rem;
    }
    .dark .details-subtitle { color: var(--dark-gray-text); }
    .filter-container {
        display: flex;
        justify-content: center;
        border-bottom: 1px solid var(--card-border);
        margin-bottom: 2rem;
    }
    .dark .filter-container { border-color: var(--dark-card-border); }
    .filter-buttons {
        display: flex;
        gap: 0.5rem;
        margin-bottom: -1px;
    }
    .filter-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-bottom: 2px solid transparent;
        color: var(--gray-text);
        transition: all 0.2s;
        background: none;
        border-top: none; border-left: none; border-right: none;
        cursor: pointer;
    }
    .dark .filter-button { color: var(--dark-gray-text); }
    .filter-button:hover {
        border-color: #d1d5db;
        color: #374151;
    }
    .dark .filter-button:hover {
        border-color: #4b5563;
        color: #f9fafb;
    }
    .filter-button.active {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    .dark .filter-button.active {
        border-color: var(--primary-color-dark);
        color: var(--primary-color-dark);
    }

    /* Question Item */
    .question-list { display: flex; flex-direction: column; gap: 1.5rem; }
    .question-item {
        background-color: var(--body-bg);
        border-radius: 0.75rem;
        border: 1px solid var(--card-border);
        padding: 1.5rem;
    }
    .dark .question-item {
        background-color: var(--dark-body-bg);
        border-color: var(--dark-card-border);
    }
    .question-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .question-number {
        flex-shrink: 0;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--primary-color-light);
        color: var(--primary-color);
        border-radius: 9999px;
        font-weight: 700;
    }
    .dark .question-number {
        background-color: rgb(79 70 229 / 0.3);
        color: var(--primary-color-dark);
    }
    .question-text {
        flex: 1;
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        line-height: 1.6;
    }
    .dark .question-text { color: #ffffff; }
    .question-status {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .question-status.correct { background-color: #dcfce7; color: #15803d; }
    .dark .question-status.correct { background-color: rgb(34 197 94 / 0.2); color: #4ade80; }
    .question-status.wrong { background-color: #fee2e2; color: #b91c1c; }
    .dark .question-status.wrong { background-color: rgb(220 38 38 / 0.2); color: #f87171; }
    .question-status.unanswered { background-color: #f3f4f6; color: #374151; }
    .dark .question-status.unanswered { background-color: #4b5563; color: #e5e7eb; }

    /* Answer Choices */
    .answer-choices {
        padding-left: 3.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .answer-option {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 0.5rem;
        border: 2px solid transparent;
        background-color: #ffffff;
        border-color: var(--card-border);
    }
    .dark .answer-option {
        border-color: var(--dark-card-border);
        background-color: rgb(55 65 81 / 0.5);
    }
    .answer-option.is-correct {
        border-color: var(--green-color);
        background-color: var(--green-color-light);
    }
    .dark .answer-option.is-correct {
        border-color: var(--green-color);
        background-color: rgb(22 163 74 / 0.15);
    }
    .answer-option.is-wrong-choice {
        border-color: var(--red-color);
        background-color: var(--red-color-light);
    }
    .dark .answer-option.is-wrong-choice {
        border-color: var(--red-color);
        background-color: rgb(220 38 38 / 0.15);
    }

    .answer-label {
        flex-shrink: 0;
        width: 1.5rem;
        height: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        background-color: #e5e7eb;
        color: #374151;
    }
    .dark .answer-label { background-color: #4b5563; color: #e5e7eb; }
    .answer-option.is-correct .answer-label { background-color: #bbf7d0; color: #166534; }
    .dark .answer-option.is-correct .answer-label { background-color: var(--green-color); color: #ffffff; }
    .answer-option.is-wrong-choice .answer-label { background-color: #fecaca; color: #991b1b; }
    .dark .answer-option.is-wrong-choice .answer-label { background-color: var(--red-color); color: #ffffff; }

    .answer-text { flex: 1; font-weight: 500; color: #1f2937; }
    .dark .answer-text { color: #d1d5db; }

    .answer-tags { display: flex; align-items: center; gap: 0.5rem; margin-left: auto; }
    .answer-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .answer-tag.correct { background-color: #dcfce7; color: #166534; }
    .dark .answer-tag.correct { background-color: var(--green-color); color: #ffffff; }
    .answer-tag.user-choice { background-color: #dbeafe; color: #312e81; }
    .dark .answer-tag.user-choice { background-color: #312e81; color: #e0e7ff; }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        justify-content: center;
        padding-top: 1.5rem;
    }
    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        color: #ffffff;
        text-decoration: none;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .action-button:hover {
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        transform: translateY(-2px);
    }
    .action-button.primary { background-color: var(--primary-color); }
    .action-button.primary:hover { background-color: #4338ca; }
    .action-button.secondary { background-color: #4b5563; }
    .action-button.secondary:hover { background-color: #374151; }

    /* Attempt Selector */
    .attempt-selector select {
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        background: white;
        min-width: 200px;
        font-size: 0.875rem;
        color: #374151;
    }
    .dark .attempt-selector select {
        background: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }
    .attempt-selector label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #374151;
    }
    .dark .attempt-selector label {
        color: #f9fafb;
    }

    /* Fallback Message */
    .fallback-message { text-align: center; padding: 3rem 0; }
    .fallback-message p { font-weight: 600; color: var(--gray-text); }
    .dark .fallback-message p { color: var(--dark-gray-text); }

    /* Responsive Breakpoints */
    @media (min-width: 640px) { /* sm */
        .quiz-container { padding: 1.5rem; }
        .quiz-header .info-group { flex-direction: row; gap: 1.5rem; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .action-buttons { flex-direction: row; }
    }
    @media (min-width: 1024px) { /* lg */
        .quiz-container { padding: 2rem; gap: 2rem; }
        .quiz-header { flex-direction: row; align-items: center; }
        .stats-grid { grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
    }
</style>

    @if($quiz)
        <div class="quiz-container">

            <!-- [START] Header Section -->
            <div class="card">
                <div class="quiz-header">
                    <div class="header-content">
                        <p class="subheading">{{ $this->getSubheading() }}</p>

                        <!-- Attempt Selection Dropdown -->
                         @if($allAttempts->count() > 1)
                         <div class="attempt-selector" style="margin-top: 1rem;">
                             <label for="attempt-select">Chọn lần làm bài:</label>
                             <select id="attempt-select" wire:change="selectAttempt($event.target.value)">
                                 @foreach($allAttempts as $index => $attemptItem)
                                     <option value="{{ $attemptItem->id }}" {{ $attemptItem->id == $selectedAttemptId ? 'selected' : '' }}>
                                         Lần {{ $index + 1 }} - {{ $attemptItem->created_at ? $attemptItem->created_at->format('d/m/Y H:i') : 'N/A' }}
                                         ({{ $attemptItem->score ?? 0 }}/{{ $quiz->max_points ?? 0 }} điểm)
                                     </option>
                                 @endforeach
                             </select>
                         </div>
                         @endif
                    </div>
                    <div class="info-group">
                        <div class="info-item">
                        </div>
                        <div class="info-item">
                            <x-heroicon-s-calendar class="w-5 h-5 text-gray-400" />
                            <span><strong>Hoàn thành trong:</strong> {{ $duration }}</span>
                        </div>
                        <div class="info-item">
                            <x-heroicon-s-check-circle class="w-5 h-5 text-gray-400" />
                            <span><strong>Hoàn thành lúc:</strong> {{ $completedAt }}</span>
                        </div>
                    </div>
                    <a href="{{ route('filament.app.pages.my-quiz') }}" class="back-button">
                        <x-heroicon-s-arrow-left class="w-5 h-5" />
                        <span>Quay lại</span>
                    </a>
                </div>
            </div>
            <!-- [END] Header Section -->

            <!-- [START] Stats Overview Section -->
            <div class="stats-grid">
                <!-- Score Card -->
                <div class="card stat-card">
                    <div class="icon-wrapper primary"><x-heroicon-o-star class="w-7 h-7" /></div>
                    <div>
                        <div class="stat-title">Tổng điểm</div>
                        <div class="stat-value">{{ $score }}/{{ $maxScore }}</div>
                        <div class="stat-extra">({{ $percentage }}%)</div>
                    </div>
                </div>
                <!-- Correct Answers Card -->
                <div class="card stat-card">
                    <div class="icon-wrapper green"><x-heroicon-o-check-circle class="w-7 h-7" /></div>
                    <div>
                        <div class="stat-title">Câu đúng</div>
                        <div class="stat-value">{{ $correctCount }}</div>
                    </div>
                </div>
                <!-- Wrong Answers Card -->
                <div class="card stat-card">
                    <div class="icon-wrapper red"><x-heroicon-o-x-circle class="w-7 h-7" /></div>
                    <div>
                        <div class="stat-title">Câu sai</div>
                        <div class="stat-value">{{ $wrongCount }}</div>
                    </div>
                </div>
                 <!-- Unanswered Card -->
                <div class="card stat-card">
                    <div class="icon-wrapper gray"><x-heroicon-o-question-mark-circle class="w-7 h-7" /></div>
                    <div>
                        <div class="stat-title">Chưa trả lời</div>
                        <div class="stat-value">{{ $unansweredCount }}</div>
                    </div>
                </div>
            </div>
            <!-- [END] Stats Overview Section -->


            <!-- [START] Detailed Answers Section -->
            <div class="card">
                <h2 class="details-title">Đáp án chi tiết</h2>
                <p class="details-subtitle">Xem lại từng câu hỏi, câu trả lời của bạn và đáp án đúng.</p>

                <!-- Livewire Filter Buttons -->
                <div class="filter-container">
                    <div class="filter-buttons">
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
                                class="filter-button {{ $filter === $key ? 'active' : '' }}"
                            >
                                <x-dynamic-component :component="$f['icon']" class="w-5 h-5" />
                                <span>{{ $f['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Answers List -->
                <div class="question-list">
                    @forelse($results as $index => $result)
                        @if($filter === 'all' || $filter === $result['status'])
                            <div wire:key="question-{{ $result['question']->id }}" class="question-item">
                                <!-- Question Header -->
                                <div class="question-header">
                                    <div class="question-number">{{ $index + 1 }}</div>
                                    <p class="question-text">{{ $result['question']->question_text }}</p>
                                    <span class="question-status {{ $result['status'] }}">
                                        @if($result['status'] === 'correct') <x-heroicon-s-check-circle class="w-4 h-4" /> Đúng @endif
                                        @if($result['status'] === 'wrong') <x-heroicon-s-x-circle class="w-4 h-4" /> Sai @endif
                                        @if($result['status'] === 'unanswered') Chưa trả lời @endif
                                    </span>
                                </div>

                                <!-- Answer Choices -->
                                <div class="answer-choices">
                                    @foreach($result['question']->answerChoices as $choiceIndex => $choice)
                                        @php
                                            $isUserChoice = $result['user_answer'] && $result['user_answer']->answer_choice_id == $choice->id;
                                            $isCorrectChoice = $choice->is_correct;
                                            $choiceLabel = chr(65 + $choiceIndex);
                                            $choiceClass = '';
                                            if ($isCorrectChoice)
                                                $choiceClass = 'is-correct';
                                            elseif ($isUserChoice)
                                                $choiceClass = 'is-wrong-choice';
                                        @endphp
                                        <div class="answer-option {{ $choiceClass }}">
                                            <div class="answer-label">{{ $choiceLabel }}</div>
                                            <p class="answer-text">{{ $choice->title }}</p>
                                            <div class="answer-tags">
                                                @if($isCorrectChoice)
                                                    <span class="answer-tag correct"><x-heroicon-s-check class="w-4 h-4"/> Đáp án đúng</span>
                                                @endif
                                                @if($isUserChoice && !$isCorrectChoice)
                                                    <span class="answer-tag user-choice"><x-heroicon-s-user class="w-4 h-4"/> Lựa chọn của bạn</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="fallback-message">
                            <p>Không có câu hỏi nào khớp với bộ lọc của bạn.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <!-- [END] Detailed Answers Section -->

            <!-- [START] Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('filament.app.pages.my-quiz') }}" class="action-button primary">
                    <x-heroicon-s-arrow-left-circle class="w-5 h-5" />
                    Về danh sách Quiz
                </a>

            </div>
            <!-- [END] Action Buttons -->

        </div>
    @else
        {{-- A fallback message if the quiz data isn't loaded yet --}}
        <div class="fallback-message" style="padding: 5rem 0;">
            <x-filament::loading-indicator class="w-10 h-10 mx-auto mb-4" />
            <p>Đang tải dữ liệu kết quả...</p>
        </div>
    @endif
</x-filament-panels::page>
