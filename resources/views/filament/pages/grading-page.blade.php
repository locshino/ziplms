<x-filament-panels::page>

    {{-- ====================================================================== --}}
    {{-- ======================= PURE CSS STYLESHEET ========================== --}}
    {{-- ====================================================================== --}}
    <style>
        :root {
            /* Bảng màu */
            --color-primary: #3b82f6; /* blue-500 */
            --color-primary-dark: #60a5fa; /* blue-400 */
            --color-primary-light: #eff6ff; /* blue-100 */
            --color-primary-text-light: #1e40af; /* blue-800 */
            --color-primary-dark-bg: #1e3a8a; /* blue-900 */
            --color-primary-text-dark: #bfdbfe; /* blue-200 */
            --color-success: #16a34a; /* green-600 */
            --color-success-hover: #15803d; /* green-700 */
            --color-success-dark: #4ade80; /* green-400 */
            --color-success-light: #dcfce7; /* green-100 */
            --color-success-text-light: #15803d; /* green-700 */
            --color-warning: #f59e0b; /* amber-500 */
            --color-warning-hover: #d97706; /* amber-600 */
            --color-warning-dark: #fbbf24; /* amber-400 */
            --color-warning-light: #fef3c7; /* amber-100 */
            --color-warning-text-light: #b45309; /* amber-700 */
            --color-danger: #dc2626; /* red-600 */
            --color-danger-dark: #f87171; /* red-400 */
            --color-danger-light: #fee2e2; /* red-100 */
            --color-danger-text-light: #b91c1c; /* red-700 */
            --color-info: #3b82f6; /* blue-500 */
            --color-info-dark: #60a5fa; /* blue-400 */
            --color-info-light: #dbeafe; /* blue-100 */
            --color-info-text-light: #1d4ed8; /* blue-700 */
            --color-text-light-primary: #1e293b; /* slate-800 */
            --color-text-light-secondary: #64748b; /* slate-500 */
            --color-text-dark-primary: #f1f5f9; /* slate-100 */
            --color-text-dark-secondary: #94a3b8; /* slate-400 */
            --bg-light-page: #f1f5f9; /* slate-100 */
            --bg-light-card: #ffffff;
            --bg-light-section: #f8fafc; /* slate-50 */
            --bg-dark-page: #0f172a; /* slate-900 */
            --bg-dark-card: #1e293b; /* slate-800 */
            --bg-dark-section: #0f172a; /* slate-900 */
            --border-light: #e2e8f0; /* slate-200 */
            --border-dark: #334155; /* slate-700 */
            --spacing-xs: 0.5rem;
            --spacing-sm: 0.75rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --border-radius-md: 0.75rem; /* rounded-lg */
            --border-radius-lg: 1rem; /* rounded-2xl */
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        .grading-page { min-height: 100vh; background-color: var(--bg-light-page); }
        .dark .grading-page { background-color: var(--bg-dark-page); }
        .grading-container { max-width: 80rem; margin-left: auto; margin-right: auto; padding: 2rem 1rem; }
        .filter-bar { margin-bottom: var(--spacing-xl); background-color: var(--bg-light-card); padding: var(--spacing-lg); border-radius: var(--border-radius-lg); box-shadow: var(--shadow-lg); border: 1px solid var(--border-light); display: flex; flex-direction: column; gap: var(--spacing-lg); }
        .dark .filter-bar { background-color: var(--bg-dark-card); border-color: var(--border-dark); }
        .filter-controls { display: flex; align-items: center; gap: var(--spacing-md); flex-wrap: wrap; }
        .filter-group { display: flex; align-items: center; gap: var(--spacing-sm); flex-grow: 1; }
        .filter-label { font-size: 0.875rem; font-weight: 500; color: var(--color-text-light-primary); }
        .dark .filter-label { color: var(--color-text-dark-primary); }
        .filter-input-wrapper { flex-grow: 1; min-width: 200px; }
        .filter-input { width: 100%; border-radius: var(--border-radius-md); border: 1px solid var(--border-light); background-color: var(--bg-light-card); color: var(--color-text-light-primary); padding: var(--spacing-xs) var(--spacing-sm); }
        .dark .filter-input { border-color: var(--border-dark); background-color: #334155; color: var(--color-text-dark-primary); }
        .filter-tabs { display: flex; gap: var(--spacing-xs); background-color: var(--bg-light-page); padding: var(--spacing-xs); border-radius: var(--border-radius-md); }
        .dark .filter-tabs { background-color: var(--bg-dark-page); }
        .filter-tab-button { flex-grow: 1; text-align: center; padding: var(--spacing-xs) var(--spacing-sm); border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; border: none; background-color: transparent; color: var(--color-text-light-secondary); transition: all 0.2s ease-in-out; }
        .dark .filter-tab-button { color: var(--color-text-dark-secondary); }
        .filter-tab-button:hover { background-color: var(--border-light); }
        .dark .filter-tab-button:hover { background-color: var(--border-dark); }
        .filter-tab-button.active { background-color: var(--color-primary); color: white; box-shadow: var(--shadow-lg); }
        .dark .filter-tab-button.active { background-color: var(--color-primary-dark-bg); }
        .assignment-list { display: grid; grid-template-columns: 1fr; gap: var(--spacing-lg); }
        .assignment-list-item { display: flex; flex-direction: column; background-color: var(--bg-light-card); border: 1px solid var(--border-light); border-radius: var(--border-radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; transition: all 0.3s ease-in-out; }
        .dark .assignment-list-item { background-color: var(--bg-dark-card); border-color: var(--border-dark); }
        .assignment-list-item:hover { box-shadow: var(--shadow-xl); transform: translateY(-0.25rem); }
        .assignment-item-main { padding: var(--spacing-lg); flex-grow: 1; }
        .assignment-item-meta { padding: var(--spacing-lg); background-color: var(--bg-light-section); border-top: 1px solid var(--border-light); display: flex; flex-direction: column; justify-content: space-between; }
        .dark .assignment-item-meta { background-color: var(--bg-dark-section); border-top-color: var(--border-dark); }
        .assignment-course-badge { display: inline-block; background-color: var(--color-primary-light); color: var(--color-primary-text-light); font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.75rem; border-radius: 9999px; margin-bottom: var(--spacing-sm); }
        .dark .assignment-course-badge { background-color: var(--color-primary-dark-bg); color: var(--color-primary-text-dark); }
        .assignment-title-container { display: flex; align-items: center; gap: var(--spacing-md); flex-wrap: wrap; margin-bottom: var(--spacing-sm); }
        .assignment-title { font-size: 1.25rem; font-weight: 700; color: var(--color-text-light-primary); line-height: 1.4; }
        .dark .assignment-title { color: var(--color-text-dark-primary); }
        .assignment-details { display: flex; flex-wrap: wrap; gap: var(--spacing-md); margin-top: var(--spacing-sm); }
        .assignment-detail-item { display: flex; align-items: center; gap: var(--spacing-xs); font-size: 0.875rem; color: var(--color-text-light-secondary); }
        .dark .assignment-detail-item { color: var(--color-text-dark-secondary); }
        .assignment-detail-item .icon { width: 1.25rem; height: 1.25rem; }
        .assignment-detail-item .icon.due-date { color: var(--color-danger); }
        .dark .assignment-detail-item .icon.due-date { color: var(--color-danger-dark); }
        .assignment-detail-item .icon.points { color: var(--color-warning); }
        .dark .assignment-detail-item .icon.points { color: var(--color-warning-dark); }
        .assignment-detail-item span { font-weight: 500; }
        .status-badge { display: inline-flex; align-items: center; gap: var(--spacing-xs); padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; }
        .status-badge.graded-count { background-color: var(--color-info-light); color: var(--color-info-text-light); }
        .dark .status-badge.graded-count { background-color: #1e3a8a; color: var(--color-info-dark); }
        .assignment-actions { margin-top: var(--spacing-lg); padding-top: var(--spacing-lg); border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; gap: var(--spacing-sm); }
        .dark .assignment-actions { border-top-color: var(--border-dark); }
        .assignment-actions>* { font-weight: 600; padding: 0.6rem 1.2rem; border-radius: var(--border-radius-md); transition: all 0.2s ease-in-out; display: inline-flex; align-items: center; justify-content: center; gap: var(--spacing-xs); border: 1px solid transparent; cursor: pointer; text-decoration: none; font-size: 0.875rem; }
        .assignment-actions>*:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
        .action-primary { background-color: var(--color-primary); color: white; }
        .action-primary:hover { background-color: var(--color-primary-dark-bg); }
        .action-secondary { background-color: var(--bg-light-card); color: var(--color-text-light-secondary); border-color: var(--border-light); }
        .dark .action-secondary { background-color: var(--bg-dark-card); color: var(--color-text-dark-secondary); border-color: var(--border-dark); }
        .action-secondary:hover { border-color: var(--color-text-light-secondary); color: var(--color-text-light-primary); }
        .dark .action-secondary:hover { border-color: var(--color-text-dark-secondary); color: var(--color-text-dark-primary); }
        .empty-state { text-align: center; padding: 4rem 1rem; background-color: var(--bg-light-card); border-radius: var(--border-radius-lg); border: 1px solid var(--border-light); }
        .dark .empty-state { background-color: var(--bg-dark-card); border-color: var(--border-dark); }
        .grading-modal-backdrop { position: fixed; inset: 0; z-index: 50; display: flex; align-items: center; justify-content: center; background-color: rgba(15, 23, 42, 0.75); }
        .grading-modal-container { position: relative; width: 100%; max-width: 56rem; background-color: var(--bg-light-card); border-radius: var(--border-radius-lg); box-shadow: var(--shadow-xl); margin: 1rem; max-height: 90vh; display: flex; flex-direction: column; }
        .dark .grading-modal-container { background-color: var(--bg-dark-card); }
        .grading-modal-header { padding: var(--spacing-lg); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: flex-start; flex-shrink: 0; }
        .dark .grading-modal-header { border-color: var(--border-dark); }
        .grading-modal-title { font-size: 1.5rem; font-weight: 700; color: var(--color-text-light-primary); }
        .dark .grading-modal-title { color: var(--color-text-dark-primary); }
        .grading-modal-subtitle { margin-top: 0.25rem; font-size: 0.875rem; color: var(--color-text-light-secondary); }
        .dark .grading-modal-subtitle { color: var(--color-text-dark-secondary); }
        .grading-modal-close-btn { padding: 0.5rem; margin: -0.5rem; color: var(--color-text-light-secondary); border-radius: 9999px; transition: color 0.2s; background: none; border: none; cursor: pointer; }
        .grading-modal-close-btn:hover { color: var(--color-danger); }
        .dark .grading-modal-close-btn:hover { color: var(--color-danger-dark); }
        .grading-modal-body { overflow-y: auto; flex-grow: 1; }
        .submissions-list>div:not(:last-child) { border-bottom: 1px solid var(--border-light); }
        .dark .submissions-list>div:not(:last-child) { border-bottom-color: var(--border-dark); }
        .submission-item { padding: var(--spacing-lg); display: grid; grid-template-columns: 1fr; gap: var(--spacing-lg); }
        @media(min-width: 768px) { .submission-item { grid-template-columns: 1fr 2fr; align-items: flex-start; } }
        .student-info { grid-column: span 1 / span 1; }
        .student-name { font-weight: 600; color: var(--color-text-light-primary); }
        .dark .student-name { color: var(--color-text-dark-primary); }
        .submission-time { font-size: 0.875rem; color: var(--color-text-light-secondary); }
        .dark .submission-time { color: var(--color-text-dark-secondary); }
        
        /* Cập nhật: Khối hiển thị thông tin bài nộp của sinh viên */
        .student-submission-details {
            margin-top: var(--spacing-md);
            padding: var(--spacing-md);
            background-color: var(--bg-light-section);
            border-radius: var(--border-radius-md);
            font-size: 0.875rem;
            color: var(--color-text-light-secondary);
        }
        .dark .student-submission-details {
            background-color: var(--bg-dark-section);
            color: var(--color-text-dark-secondary);
        }
        .student-submission-details p { margin-bottom: var(--spacing-xs); }
        .student-submission-details strong { font-weight: 500; color: var(--color-text-light-primary); }
        .dark .student-submission-details strong { color: var(--color-text-dark-primary); }
        .student-submission-details a { color: var(--color-primary); text-decoration: underline; }
        .dark .student-submission-details a { color: var(--color-primary-dark); }

        .download-link { margin-top: var(--spacing-sm); display: inline-flex; align-items: center; gap: var(--spacing-xs); font-size: 0.875rem; font-weight: 500; color: var(--color-primary); text-decoration: none; background: none; border: none; cursor: pointer; }
        .dark .download-link { color: var(--color-primary-dark); }
        .download-link:hover { text-decoration: underline; }
        .grading-form { grid-column: span 1 / span 1; }
        .grading-form-grid { display: grid; grid-template-columns: 1fr; gap: var(--spacing-md); }
        @media(min-width: 640px) { .grading-form-grid { grid-template-columns: 1fr 2fr auto; align-items: flex-end; } }
        .form-group { margin-bottom: 0; }
        .form-label { display: block; margin-bottom: var(--spacing-xs); font-size: 0.875rem; font-weight: 500; color: var(--color-text-light-primary); }
        .dark .form-label { color: var(--color-text-dark-primary); }
        .form-input, .form-textarea { width: 100%; border-radius: var(--border-radius-md); border: 1px solid var(--border-light); background-color: var(--bg-light-card); color: var(--color-text-light-primary); padding: var(--spacing-sm); }
        .dark .form-input, .dark .form-textarea { background-color: #334155; border-color: var(--border-dark); color: var(--color-text-dark-primary); }
        .save-grade-btn { background-color: var(--color-success); color: white; font-weight: 600; padding: 0.6rem 1.2rem; border-radius: var(--border-radius-md); border: none; cursor: pointer; transition: background-color 0.2s; height: fit-content; }
        .save-grade-btn:hover { background-color: var(--color-success-hover); }
        .save-grade-btn:disabled { opacity: 0.7; cursor: not-allowed; }
        .grading-modal-footer { padding: var(--spacing-md) var(--spacing-lg); border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; flex-shrink: 0; }
        .dark .grading-modal-footer { border-color: var(--border-dark); }
    </style>

    <div class="grading-page">
        <div class="grading-container">

            <!-- Thanh bộ lọc -->
            <div class="filter-bar">
                <div class="filter-controls">
                    <div class="filter-group">
                        <label for="courseFilter" class="filter-label">Khóa học:</label>
                        <div class="filter-input-wrapper">
                            <select id="courseFilter" wire:model.live="courseId" class="filter-input">
                                <option value="">Tất cả các khóa học</option>
                                @foreach($this->courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                     <div class="filter-group">
                        <label for="searchFilter" class="filter-label">Tìm kiếm:</label>
                        <div class="filter-input-wrapper">
                             <input id="searchFilter" wire:model.live.debounce.300ms="search" type="search" placeholder="Tìm tiêu đề bài tập..." class="filter-input">
                        </div>
                    </div>
                </div>
                <div class="filter-tabs">
                    <button wire:click="setFilter('all')" class="filter-tab-button {{ $filter === 'all' ? 'active' : '' }}">Tất cả</button>
                    <button wire:click="setFilter('ungraded')" class="filter-tab-button {{ $filter === 'ungraded' ? 'active' : '' }}">Chưa chấm</button>
                    <button wire:click="setFilter('graded')" class="filter-tab-button {{ $filter === 'graded' ? 'active' : '' }}">Đã chấm</button>
                </div>
            </div>

            <!-- Danh sách bài tập -->
            <div class="assignment-list">
                @forelse ($assignments as $assignment)
                    <div wire:key="{{ $assignment->id }}" class="assignment-list-item">
                        <div class="assignment-item-main">
                            <div class="assignment-title-container">
                                <h3 class="assignment-title">{{ $assignment->title }}</h3>
                                <span class="status-badge graded-count">
                                    {{ $assignment->graded_submissions_count }} / {{ $assignment->submissions_count }} Đã chấm
                                </span>
                            </div>
                            <p class="assignment-course-badge">{{ $assignment->course->title ?? 'Khóa học không xác định' }}</p>
                            <div class="assignment-details">
                                <div class="assignment-detail-item">
                                    <x-heroicon-o-calendar-days class="icon due-date" />
                                    <span>Hạn: {{ $assignment->due_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="assignment-detail-item">
                                    <x-heroicon-o-star class="icon points" />
                                    <span>{{ rtrim(rtrim(number_format($assignment->max_points, 2), '0'), '.') }} điểm</span>
                                </div>
                            </div>
                        </div>

                        <div class="assignment-actions">
                            <button type="button" wire:click="openInstructionsModal('{{ $assignment->id }}')" class="action-secondary">
                                Xem hướng dẫn
                            </button>
                            <button type="button" wire:click="openSubmissionsModal('{{ $assignment->id }}')" class="action-primary">
                                Xem bài nộp
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <h3 class="text-lg font-semibold">Không tìm thấy bài tập</h3>
                        <p class="text-sm">Không có bài tập nào phù hợp với bộ lọc hiện tại.</p>
                    </div>
                @endforelse

                @if ($assignments->hasPages())
                    <div class="pt-4">
                        {{ $assignments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- INSTRUCTIONS MODAL --}}
    @if ($showInstructionsModal && $selectedAssignment)
        {{-- ... Modal Hướng dẫn giữ nguyên ... --}}
    @endif

    {{-- SUBMISSIONS MODAL --}}
    @if ($showSubmissionsModal && $selectedAssignment)
        <div class="grading-modal-backdrop" x-trap.noscroll="true">
            <div @click.away="$wire.closeSubmissionsModal()" class="grading-modal-container">
                <div class="grading-modal-header">
                    <div>
                        <h2 class="grading-modal-title">Danh sách bài nộp</h2>
                        <p class="grading-modal-subtitle">Bài tập: {{ $selectedAssignment->title }} (Tối đa {{ rtrim(rtrim(number_format($selectedAssignment->max_points, 2), '0'), '.') }} điểm)</p>
                    </div>
                     <button type="button" wire:click="closeSubmissionsModal" class="grading-modal-close-btn">
                        <span class="sr-only">Đóng</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="grading-modal-body">
                    <div class="submissions-list">
                        @forelse ($submissions as $submission)
                            <div wire:key="submission-{{ $submission->id }}" class="submission-item">
                                <!-- Cột thông tin sinh viên và bài nộp -->
                                <div class="student-info">
                                    <p class="student-name">{{ $submission->student->name ?? 'Không rõ' }}</p>
                                    <p class="submission-time">Nộp lúc: {{ $submission->submitted_at->format('H:i d/m/Y') }}</p>
                                    
                                    {{-- Cập nhật: Hiển thị thông tin bài nộp của sinh viên --}}
                                    <div class="student-submission-details">
                                        @if($submission->file_path)
                                            <p><strong>Bài nộp:</strong> Tệp đính kèm</p>
                                            <button wire:click="downloadSubmission({{ $submission->id }})" class="download-link">
                                                <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                                                Tải tệp
                                            </button>
                                        @elseif(!empty($submission->feedback['link_url']))
                                            <p><strong>Bài nộp:</strong> <a href="{{ $submission->feedback['link_url'] }}" target="_blank" rel="noopener noreferrer">Mở liên kết</a></p>
                                        @else
                                            <p>Không có tệp hoặc liên kết.</p>
                                        @endif
                                        
                                        @if(!empty($submission->feedback['student_notes']))
                                            <p class="mt-2"><strong>Ghi chú của HS:</strong><br>{{ $submission->feedback['student_notes'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                <!-- Cột form chấm điểm -->
                                <div class="grading-form">
                                    <div class="grading-form-grid">
                                        <div class="form-group">
                                            <label for="grade-{{ $submission->id }}" class="form-label">Điểm</label>
                                            <input type="number" id="grade-{{ $submission->id }}"
                                                   wire:model.defer="grades.{{ $submission->id }}"
                                                   step="0.1"
                                                   min="0"
                                                   max="{{ $selectedAssignment->max_points }}"
                                                   placeholder="VD: 8.5"
                                                   class="form-input">
                                        </div>
                                        <div class="form-group">
                                            <label for="feedback-{{ $submission->id }}" class="form-label">Phản hồi của GV</label>
                                            <input type="text" id="feedback-{{ $submission->id }}"
                                                      wire:model.defer="feedbackNotes.{{ $submission->id }}"
                                                      class="form-input"
                                                      placeholder="Nhập phản hồi..."/>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" wire:click="saveGrade({{ $submission->id }})"
                                                    wire:loading.attr="disabled" wire:target="saveGrade({{ $submission->id }})"
                                                    class="save-grade-btn">
                                                Lưu
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500 dark:text-gray-400">Chưa có sinh viên nào nộp bài cho bài tập này.</div>
                        @endforelse
                    </div>
                </div>

                <div class="grading-modal-footer">
                    <button type="button" @click="$wire.closeSubmissionsModal()" class="action-secondary">Đóng</button>
                </div>
            </div>
        </div>
    @endif

</x-filament-panels::page>
