{{-- Toàn bộ CSS thuần để tạo kiểu cho trang --}}
<style>
    /* ----- Biến và Thiết lập chung ----- */
    :root {
        --color-blue-600: #2563eb;
        --color-indigo-700: #4338ca;
        --color-white: #ffffff;
        --color-black: #000000;
        --color-text-primary-light: #1f2937;
        --color-text-secondary-light: #6b7280;
        --color-bg-light: #ffffff;
        --color-bg-secondary-light: #f9fafb;
        --color-border-light: #e5e7eb;

        --color-success: #10B981;
        --color-success-hover: #059669;
        --color-success-bg: #f0fdf4;
        --color-success-border: #a7f3d0;
        --color-success-text: #065f46;

        --color-warning: #F59E0B;
        --color-warning-hover: #D97706;
        --color-warning-bg: #fffbeb;
        --color-warning-border: #fde68a;
        --color-warning-text: #b45309;

        --color-danger: #EF4444;
        --color-danger-hover: #DC2626;
        --color-danger-bg: #fef2f2;
        --color-danger-border: #fecaca;
        --color-danger-text: #991b1b;

        --color-neutral: #6B7280;
        --color-neutral-hover: #4B5563;
        --color-neutral-bg: #f8fafc;
        --color-neutral-border: #e2e8f0;
        --color-neutral-text: #334155;
    }

    .dark {
        --color-text-primary-light: #f9fafb;
        --color-text-secondary-light: #9ca3af;
        --color-bg-light: #1f2937;
        --color-bg-secondary-light: #374151;
        --color-border-light: #4b5563;

        --color-success-bg: #064e3b;
        --color-success-border: #047857;
        --color-success-text: #a7f3d0;

        --color-warning-bg: #78350f;
        --color-warning-border: #92400e;
        --color-warning-text: #fde68a;

        --color-danger-bg: #7f1d1d;
        --color-danger-border: #991b1b;
        --color-danger-text: #fecaca;

        --color-neutral-bg: #374151;
        --color-neutral-border: #4b5563;
        --color-neutral-text: #d1d5db;
    }

    .page-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    /* ----- Header Banner ----- */
    .header-banner {
        position: relative;
        overflow: hidden;
        background-image: linear-gradient(to bottom right, var(--color-blue-600), var(--color-indigo-700));
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(37, 99, 235, 0.5);
        color: var(--color-white);
        padding: 1.5rem;
    }

    .header-banner-overlay {
        position: absolute;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.1);
    }

    .header-banner-content {
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 1.5rem;
    }

    .header-info {
        display: flex;
        align-items: center;
    }

    .header-info-icon {
        background-color: rgba(255, 255, 255, 0.2);
        padding: 0.75rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .header-info-icon svg {
        width: 2.25rem;
        height: 2.25rem;
    }

    .header-info-course {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .header-info-title {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .header-status {
        background-color: rgba(0, 0, 0, 0.2);
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        flex-shrink: 0;
    }

    .header-status-label {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .header-status-value {
        font-size: 1.125rem;
        font-weight: 600;
    }

    /* ----- Card ----- */
    .card {
        background-color: var(--color-bg-light);
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--color-border-light);
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        color: var(--color-text-primary-light);
    }

    .card-title svg {
        width: 1.75rem;
        height: 1.75rem;
        margin-right: 0.75rem;
        color: var(--color-blue-600);
    }

    /* ----- Overview Section ----- */
    .overview-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .overview-stats-column {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .percentage-card {
        background-image: linear-gradient(to bottom right, var(--color-blue-600), var(--color-indigo-700));
        border-radius: 0.75rem;
        padding: 1.5rem;
        color: var(--color-white);
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .percentage-card-label {
        font-size: 1.125rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.8);
    }

    .percentage-card-value {
        font-size: 3rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }

    .percentage-card-points {
        font-size: 1.125rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.8);
    }

    .time-spent-card {
        background-color: var(--color-bg-secondary-light);
        border-radius: 0.75rem;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        color: var(--color-text-secondary-light);
    }

    .time-spent-card svg {
        width: 1.5rem;
        height: 1.5rem;
    }

    .time-spent-card span {
        font-size: 1.125rem;
        font-weight: 600;
    }

    .overview-chart-column {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        align-items: center;
    }

    .chart-container {
        position: relative;
        width: 16rem;
        height: 16rem;
        margin: 0 auto;
    }

    .legend-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background-color: var(--color-bg-secondary-light);
        border-radius: 0.5rem;
        border: 1px solid var(--color-border-light);
    }

    .legend-dot {
        width: 1rem;
        height: 1rem;
        border-radius: 9999px;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .legend-label {
        flex-grow: 1;
        font-weight: 600;
        color: var(--color-text-secondary-light);
    }

    .legend-value {
        font-weight: 700;
        color: var(--color-text-primary-light);
    }

    /* ----- Quick Navigation ----- */
    .question-nav-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 8px 0;
    }

    .question-nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        font-weight: bold;
        color: white;
        text-decoration: none;
        transition: transform 0.2s ease, background-color 0.2s ease;
        scroll-behavior: smooth;
    }

    .question-nav-link:hover {
        transform: scale(1.1);
    }

    .status-correct {
        background-color: var(--color-success);
    }

    .status-correct:hover {
        background-color: var(--color-success-hover);
    }

    .status-partially-correct {
        background-color: var(--color-warning);
    }

    .status-partially-correct:hover {
        background-color: var(--color-warning-hover);
    }

    .status-incorrect {
        background-color: var(--color-danger);
    }

    .status-incorrect:hover {
        background-color: var(--color-danger-hover);
    }

    .status-unanswered {
        background-color: var(--color-neutral);
    }

    .status-unanswered:hover {
        background-color: var(--color-neutral-hover);
    }

    /* ----- Question Card ----- */
    .question-card-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .question-card {
        border-radius: 1rem;
        padding: 1.25rem;
        border: 1px solid;
        scroll-margin-top: 1.5rem;
    }

    .question-card.is-correct {
        background-color: var(--color-success-bg);
        border-color: var(--color-success-border);
    }

    .question-card.is-partially-correct {
        background-color: var(--color-warning-bg);
        border-color: var(--color-warning-border);
    }

    .question-card.is-incorrect {
        background-color: var(--color-danger-bg);
        border-color: var(--color-danger-border);
    }

    .question-card.is-unanswered {
        background-color: var(--color-neutral-bg);
        border-color: var(--color-neutral-border);
    }

    .question-header {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .question-title-section {
        flex-grow: 1;
    }

    .question-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .question-number {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--color-text-primary-light);
    }

    .question-status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        border: 1px solid;
    }

    .question-card.is-correct .question-status-badge {
        background-color: #dcfce7;
        color: var(--color-success-text);
        border-color: #bbf7d0;
    }

    .question-card.is-partially-correct .question-status-badge {
        background-color: #fef3c7;
        color: var(--color-warning-text);
        border-color: #fde68a;
    }

    .question-card.is-incorrect .question-status-badge {
        background-color: #fee2e2;
        color: var(--color-danger-text);
        border-color: #fecaca;
    }

    .question-card.is-unanswered .question-status-badge {
        background-color: #e2e8f0;
        color: var(--color-neutral-text);
        border-color: #cbd5e1;
    }

    .dark .question-card.is-correct .question-status-badge {
        background-color: #166534;
        border-color: #22c55e;
        color: #dcfce7;
    }

    .dark .question-card.is-partially-correct .question-status-badge {
        background-color: #92400e;
        border-color: #f59e0b;
        color: #fef3c7;
    }

    .dark .question-card.is-incorrect .question-status-badge {
        background-color: #991b1b;
        border-color: #ef4444;
        color: #fee2e2;
    }

    .dark .question-card.is-unanswered .question-status-badge {
        background-color: #475569;
        border-color: #64748b;
        color: #e2e8f0;
    }

    .question-content {
        line-height: 1.6;
        color: var(--color-text-primary-light);
    }

    .question-points {
        background-color: #dbeafe;
        color: #1e40af;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-align: center;
    }

    .dark .question-points {
        background-color: #1e3a8a;
        color: #bfdbfe;
    }

    .question-image {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        border: 1px solid var(--color-border-light);
        margin-bottom: 1.25rem;
    }

    /* ----- Answer Choices ----- */
    .answer-choices-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .answer-choice {
        border-radius: 0.5rem;
        padding: 1rem;
        display: flex;
        align-items: center;
        border: 1px solid;
    }

    .answer-choice-icon {
        width: 1.5rem;
        height: 1.5rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .answer-choice-text {
        flex-grow: 1;
        font-weight: 500;
    }

    .answer-choice.is-correct {
        background-color: #dcfce7;
        border-color: #4ade80;
        color: #15803d;
    }

    .dark .answer-choice.is-correct {
        background-color: #14532d;
        border-color: #22c55e;
        color: #dcfce7;
    }

    .answer-choice.is-incorrect-selected {
        background-color: #fee2e2;
        border-color: #f87171;
        color: #b91c1c;
    }

    .dark .answer-choice.is-incorrect-selected {
        background-color: #7f1d1d;
        border-color: #ef4444;
        color: #fee2e2;
    }

    .answer-choice.is-neutral {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
        color: #475569;
        opacity: 0.8;
    }

    .dark .answer-choice.is-neutral {
        background-color: #334155;
        border-color: #475569;
        color: #94a3b8;
    }

    /* ----- Explanation Box ----- */
    .explanation-box {
        margin-top: 1.25rem;
        padding: 1rem;
        background-color: #eff6ff;
        border-left: 4px solid #60a5fa;
        border-radius: 0 0.5rem 0.5rem 0;
    }

    .dark .explanation-box {
        background-color: #1e293b;
        border-left-color: #3b82f6;
    }

    .explanation-title {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: #1d4ed8;
        margin-bottom: 0.5rem;
    }

    .dark .explanation-title {
        color: #93c5fd;
    }

    .explanation-title svg {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.5rem;
    }

    .explanation-content {
        line-height: 1.6;
        color: #1e3a8a;
    }

    .dark .explanation-content {
        color: #bfdbfe;
    }

    /* ----- Media Queries for Layout ----- */
    @media (min-width: 640px) {
        .header-banner {
            padding: 2rem;
        }

        .header-banner-content {
            flex-direction: row;
            align-items: flex-start;
        }

        .card {
            padding: 2rem;
        }

        .question-header {
            flex-direction: row;
            align-items: flex-start;
        }

        .question-card {
            padding: 1.5rem;
        }

        .overview-chart-column {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (min-width: 1024px) {
        .overview-grid {
            grid-template-columns: 1fr 2fr;
        }
    }
</style>

<x-filament-panels::page>
    <div class="page-container">
        <div class="header-banner">
            <div class="header-banner-overlay"></div>
            <div class="header-banner-content">
                <div class="header-info">
                    <div class="header-info-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="header-info-course">
                            {{ $this->quiz->courses->first()?->title ?? 'Khóa học không xác định' }}
                        </p>
                        <h1 class="header-info-title">{{ $this->quiz->title }}</h1>
                    </div>
                </div>
                <div class="header-status">
                    <div class="header-status-label">Trạng thái</div>
                    <div class="header-status-value">Đã hoàn thành</div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">
                <x-heroicon-o-chart-pie />
                Tổng quan kết quả
            </h2>
            <div class="overview-grid">
                <div class="overview-stats-column">
                    <div class="percentage-card">
                        <div class="percentage-card-label">Tỷ lệ điểm</div>
                        <div class="percentage-card-value">{{ number_format($this->percentage, 1) }}%</div>
                        <div class="percentage-card-points">
                            {{ $this->attemptModel->points }}/{{ $this->quiz->questions->sum('pivot.points') }} điểm
                        </div>
                    </div>
                    <div class="time-spent-card">
                        <x-heroicon-o-clock />
                        <span>Thời gian: {{ $this->timeSpent }}</span>
                    </div>
                </div>
                <div class="overview-chart-column">
                    <div class="chart-container">
                        <canvas id="performanceChart"></canvas>
                    </div>
                    <div class="legend-list">
                        <div class="legend-item">
                            <div class="legend-dot" style="background-color: var(--color-success);"></div>
                            <div class="legend-label">Đúng hoàn toàn</div>
                            <div class="legend-value">{{ $this->correctAnswers }}</div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background-color: var(--color-warning);"></div>
                            <div class="legend-label">Đúng một phần</div>
                            <div class="legend-value">{{ $this->partiallyCorrectAnswers }}</div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background-color: var(--color-danger);"></div>
                            <div class="legend-label">Sai</div>
                            <div class="legend-value">{{ $this->incorrectAnswers }}</div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background-color: var(--color-neutral);"></div>
                            <div class="legend-label">Chưa trả lời</div>
                            <div class="legend-value">{{ $this->unansweredQuestions ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">
                <x-heroicon-o-document-text />
                Xem lại chi tiết
            </h2>
            <div
                style="border-top: 1px solid var(--color-border-light); border-bottom: 1px solid var(--color-border-light); margin-bottom: 2rem;">
                <h3
                    style="font-size: 0.875rem; font-weight: 600; color: var(--color-text-secondary-light); margin-top: 1rem; margin-bottom: 0.75rem;">
                    Chuyển đến câu hỏi</h3>
                <!-- Quiz Navigation Info -->
                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--color-text-secondary-light); font-size: 0.875rem; margin-bottom: 0.75rem;">
                    <x-heroicon-o-document-text style="width: 1rem; height: 1rem;" />
                    Tổng số câu hỏi: {{ count($this->quiz->questions) }}
                </div>
                <div class="question-nav-container">
                    @foreach($this->paginatedQuestions as $index => $question)
                        @php
                            $actualIndex = ($this->currentPage - 1) * $this->perPage + $index;
                            $status = $this->getAnswerStatus($question->id);
                            $statusClass = [
                                'correct' => 'status-correct',
                                'partially_correct' => 'status-partially-correct',
                                'incorrect' => 'status-incorrect',
                                'unanswered' => 'status-unanswered',
                            ][$status] ?? 'status-unanswered';
                        @endphp
                        <a href="#question-{{ $actualIndex + 1 }}" class="question-nav-link {{ $statusClass }}">
                            {{ $actualIndex + 1 }}
                        </a>
                    @endforeach
                </div>
                
                {{-- Show total questions info --}}
                <div class="text-center mt-4 text-sm text-slate-600 dark:text-slate-400">
                    Tổng số câu hỏi: <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $this->quiz->questions->count() }}</span>
                </div>
            </div>

            {{-- Pagination Info --}}
            @if($this->getTotalPages() > 1)
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 mb-6">
                    @php
                        $paginationInfo = $this->getPaginationInfo();
                    @endphp
                    
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            Hiển thị <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['start_item'] }}</span> 
                            đến <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['end_item'] }}</span> 
                            trong tổng số <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['total_items'] }}</span> câu hỏi
                        </div>
                        
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            Trang <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['current_page'] }}</span> 
                            / <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['total_pages'] }}</span>
                        </div>
                    </div>

                    {{-- Pagination Controls --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        {{-- Previous Button --}}
                        <button 
                            wire:click="previousPage" 
                            @if(!$paginationInfo['has_previous']) disabled @endif
                            class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 
                                   {{ $paginationInfo['has_previous'] 
                                       ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-md hover:shadow-lg' 
                                       : 'bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed' }}"
                        >
                            <x-heroicon-s-chevron-left class="w-4 h-4" />
                            Trang trước
                        </button>

                        {{-- Page Numbers --}}
                        <div class="flex items-center gap-2">
                            @for($i = 1; $i <= $paginationInfo['total_pages']; $i++)
                                <button 
                                    wire:click="goToPage({{ $i }})"
                                    class="w-10 h-10 rounded-lg text-sm font-medium transition-all duration-200
                                           {{ $i == $paginationInfo['current_page'] 
                                               ? 'bg-blue-500 text-white shadow-md' 
                                               : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}"
                                >
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>

                        {{-- Next Button --}}
                        <button 
                            wire:click="nextPage" 
                            @if(!$paginationInfo['has_next']) disabled @endif
                            class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 
                                   {{ $paginationInfo['has_next'] 
                                       ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-md hover:shadow-lg' 
                                       : 'bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed' }}"
                        >
                            Trang sau
                            <x-heroicon-s-chevron-right class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            @endif

            <div class="question-card-list">
                @foreach($this->paginatedQuestions as $index => $question)
                    @php
                        $actualIndex = ($this->currentPage - 1) * $this->perPage + $index;
                    @endphp
                    @php
                        $answerStatus = $this->getAnswerStatus($question->id);
                        $userAnswerIds = $this->getUserAnswers($question->id);
                        $statusClass = [
                            'correct' => 'is-correct',
                            'partially_correct' => 'is-partially-correct',
                            'incorrect' => 'is-incorrect',
                            'unanswered' => 'is-unanswered'
                        ][$answerStatus] ?? 'is-unanswered';
                        $badgeLabel = [
                            'correct' => 'Đúng hoàn toàn',
                            'partially_correct' => 'Đúng một phần',
                            'incorrect' => 'Sai',
                            'unanswered' => 'Chưa trả lời'
                        ][$answerStatus] ?? '';
                    @endphp

                    <div id="question-{{ $actualIndex + 1 }}" class="question-card {{ $statusClass }}">
                        <div class="question-header">
                            <div class="question-title-section">
                                <div class="question-meta">
                                    <h3 class="question-number">Câu {{ $actualIndex + 1 }}</h3>
                                    <span class="question-status-badge">{{ $badgeLabel }}</span>
                                </div>
                                <div class="question-content">{!! $question->title !!}</div>
                            </div>
                            <div class="question-points">{{ $question->pivot->points ?? $question->points }} điểm</div>
                        </div>

                        @if($question->question_image)
                            <img src="{{ $question->question_image }}" alt="Question Image" class="question-image">
                        @endif

                        <div class="answer-choices-list">
                            @foreach($question->answerChoices as $choice)
                                @php
                                    $isUserChoice = in_array($choice->id, $userAnswerIds);
                                    $isCorrectChoice = $choice->is_correct;
                                    $choiceStatusClass = '';
                                    $icon = null;

                                    if ($isCorrectChoice) {
                                        $choiceStatusClass = 'is-correct';
                                        $icon = $isUserChoice ? 'heroicon-s-check-circle' : 'heroicon-o-check-circle';
                                    } elseif ($isUserChoice && !$isCorrectChoice) {
                                        $choiceStatusClass = 'is-incorrect-selected';
                                        $icon = 'heroicon-s-x-circle';
                                    } else {
                                        $choiceStatusClass = 'is-neutral';
                                        $icon = 'heroicon-o-chevron-right';
                                    }
                                @endphp
                                <div class="answer-choice {{ $choiceStatusClass }}">
                                    @if($icon)
                                        <x-dynamic-component :component="$icon" class="answer-choice-icon" />
                                    @endif
                                    <span class="answer-choice-text">{!! $choice->title !!}</span>
                                </div>
                            @endforeach
                        </div>

                        @if($question->explanation)
                            <div class="explanation-box">
                                <h5 class="explanation-title">
                                    <x-heroicon-o-light-bulb />
                                    Giải thích
                                </h5>
                                <div class="explanation-content">{!! $question->explanation !!}</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            {{-- Bottom Pagination --}}
            @if($this->getTotalPages() > 1)
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 mt-6">
                    @php
                        $paginationInfo = $this->getPaginationInfo();
                    @endphp
                    
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        {{-- Previous Button --}}
                        <button 
                            wire:click="previousPage" 
                            @if(!$paginationInfo['has_previous']) disabled @endif
                            class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 
                                   {{ $paginationInfo['has_previous'] 
                                       ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-md hover:shadow-lg' 
                                       : 'bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed' }}"
                        >
                            <x-heroicon-s-chevron-left class="w-4 h-4" />
                            Trang trước
                        </button>

                        {{-- Page Numbers --}}
                        <div class="flex items-center gap-2">
                            @for($i = 1; $i <= $paginationInfo['total_pages']; $i++)
                                <button 
                                    wire:click="goToPage({{ $i }})"
                                    class="w-10 h-10 rounded-lg text-sm font-medium transition-all duration-200
                                           {{ $i == $paginationInfo['current_page'] 
                                               ? 'bg-blue-500 text-white shadow-md' 
                                               : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}"
                                >
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>

                        {{-- Next Button --}}
                        <button 
                            wire:click="nextPage" 
                            @if(!$paginationInfo['has_next']) disabled @endif
                            class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 
                                   {{ $paginationInfo['has_next'] 
                                       ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-md hover:shadow-lg' 
                                       : 'bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed' }}"
                        >
                            Trang sau
                            <x-heroicon-s-chevron-right class="w-4 h-4" />
                        </button>
                    </div>
                    
                    <div class="text-center mt-4 text-sm text-slate-600 dark:text-slate-400">
                        Hiển thị <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['start_item'] }}</span> 
                        đến <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['end_item'] }}</span> 
                        trong tổng số <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $paginationInfo['total_items'] }}</span> câu hỏi
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('performanceChart').getContext('2d');
                const isDarkMode = document.documentElement.classList.contains('dark');

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Đúng hoàn toàn', 'Đúng một phần', 'Sai', 'Chưa trả lời'],
                        datasets: [{
                            data: [
                                    {{ $this->correctAnswers }},
                                    {{ $this->partiallyCorrectAnswers }},
                                    {{ $this->incorrectAnswers }},
                                {{ $this->unansweredQuestions ?? 0 }}
                            ],
                            backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#6B7280'],
                            borderWidth: 4,
                            borderColor: isDarkMode ? '#1f2937' : '#ffffff',
                            hoverBorderWidth: 6,
                            borderRadius: 8,
                            spacing: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDarkMode ? '#1F2937' : '#FFFFFF',
                                titleColor: isDarkMode ? '#F9FAFB' : '#111827',
                                bodyColor: isDarkMode ? '#D1D5DB' : '#374151',
                                borderColor: isDarkMode ? '#374151' : '#E5E7EB',
                                borderWidth: 1,
                                cornerRadius: 8,
                                displayColors: true,
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        const value = context.parsed;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${value} câu (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            });
        </script>
    @endpush
</x-filament-panels::page>
