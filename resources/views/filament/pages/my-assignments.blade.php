<x-filament-panels::page>

    {{-- ====================================================================== --}}
    {{-- ======================= PURE CSS STYLESHEET ========================== --}}
    {{-- ====================================================================== --}}
    <style>
        :root {
            /* Bảng màu */
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
            --color-success-light: #dcfce7;
            /* green-100 */
            --color-success-text-light: #15803d;
            /* green-700 */

            --color-warning: #f59e0b;
            /* amber-500 */
            --color-warning-hover: #d97706;
            /* amber-600 */
            --color-warning-dark: #fbbf24;
            /* amber-400 */
            --color-warning-light: #fef3c7;
            /* amber-100 */
            --color-warning-text-light: #b45309;
            /* amber-700 */

            --color-danger: #dc2626;
            /* red-600 */
            --color-danger-dark: #f87171;
            /* red-400 */
            --color-danger-light: #fee2e2;
            /* red-100 */
            --color-danger-text-light: #b91c1c;
            /* red-700 */

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

            /* Khoảng cách & Kích thước */
            --spacing-xs: 0.5rem;
            --spacing-sm: 0.75rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;

            /* Viền & Đổ bóng */
            --border-radius-md: 0.75rem;
            /* rounded-lg */
            --border-radius-lg: 1rem;
            /* rounded-2xl */
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        /* Layout trang cơ bản */
        .assignment-list-page {
            min-height: 100vh;
            background-color: var(--bg-light-page);
        }

        .dark .assignment-list-page {
            background-color: var(--bg-dark-page);
        }

        .assignment-list-container {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding: 2rem 1rem;
        }

        /* Thanh bộ lọc */
        .filter-bar {
            margin-bottom: var(--spacing-xl);
            background-color: var(--bg-light-card);
            padding: var(--spacing-lg);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            gap: var(--spacing-lg);
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
            flex-grow: 1;
        }

        .filter-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-light-primary);
        }

        .dark .filter-label {
            color: var(--color-text-dark-primary);
        }

        .filter-input-wrapper {
            flex-grow: 1;
            min-width: 200px;
        }

        .filter-input {
            width: 100%;
            border-radius: var(--border-radius-md);
            border: 1px solid var(--border-light);
            background-color: var(--bg-light-card);
            color: var(--color-text-light-primary);
            padding: var(--spacing-xs) var(--spacing-sm);
        }

        .dark .filter-input {
            border-color: var(--border-dark);
            background-color: #334155;
            color: var(--color-text-dark-primary);
        }

        /* Tab bộ lọc trạng thái */
        .filter-tabs {
            display: flex;
            gap: var(--spacing-xs);
            background-color: var(--bg-light-page);
            padding: var(--spacing-xs);
            border-radius: var(--border-radius-md);
        }
        .dark .filter-tabs {
            background-color: var(--bg-dark-page);
        }

        .filter-tab-button {
            flex-grow: 1;
            text-align: center;
            padding: var(--spacing-xs) var(--spacing-sm);
            border-radius: 0.5rem; /* rounded-md */
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            background-color: transparent;
            color: var(--color-text-light-secondary);
            transition: all 0.2s ease-in-out;
        }
        .dark .filter-tab-button {
            color: var(--color-text-dark-secondary);
        }

        .filter-tab-button:hover {
            background-color: var(--border-light);
        }
        .dark .filter-tab-button:hover {
            background-color: var(--border-dark);
        }
        
        .filter-tab-button.active {
            background-color: var(--color-primary);
            color: white;
            box-shadow: var(--shadow-lg);
        }
        .dark .filter-tab-button.active {
            background-color: var(--color-primary-dark-bg);
        }

        /* Danh sách bài tập */
        .assignment-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-lg);
        }

        /* Mục trong danh sách bài tập */
        .assignment-list-item {
            display: flex;
            flex-direction: column;
            background-color: var(--bg-light-card);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s ease-in-out;
        }

        .dark .assignment-list-item {
            background-color: var(--bg-dark-card);
            border-color: var(--border-dark);
        }

        .assignment-list-item:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-0.25rem);
        }

        .assignment-item-main {
            padding: var(--spacing-lg);
            flex-grow: 1;
        }

        .assignment-item-meta {
            padding: var(--spacing-lg);
            background-color: var(--bg-light-section);
            border-top: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .dark .assignment-item-meta {
            background-color: var(--bg-dark-section);
            border-top-color: var(--border-dark);
        }

        .assignment-course-badge {
            display: inline-block;
            background-color: var(--color-primary-light);
            color: var(--color-primary-text-light);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            margin-bottom: var(--spacing-sm);
        }

        .dark .assignment-course-badge {
            background-color: var(--color-primary-dark-bg);
            color: var(--color-primary-text-dark);
        }

        .assignment-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-text-light-primary);
            margin-bottom: var(--spacing-md);
            line-height: 1.4;
        }

        .dark .assignment-title {
            color: var(--color-text-dark-primary);
        }

        .assignment-details {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-md);
        }

        .assignment-detail-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            font-size: 0.875rem;
            color: var(--color-text-light-secondary);
        }

        .dark .assignment-detail-item {
            color: var(--color-text-dark-secondary);
        }

        .assignment-detail-item .icon {
            width: 1.25rem;
            height: 1.25rem;
        }

        .assignment-detail-item .icon.due-date { color: var(--color-danger); }
        .dark .assignment-detail-item .icon.due-date { color: var(--color-danger-dark); }
        .assignment-detail-item .icon.points { color: var(--color-warning); }
        .dark .assignment-detail-item .icon.points { color: var(--color-warning-dark); }

        .assignment-detail-item span {
            font-weight: 500;
        }

        /* Thông tin trạng thái bài tập */
        .assignment-status-info {
            margin-bottom: var(--spacing-md);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-badge .icon { width: 1rem; height: 1rem; }

        .status-badge.submitted {
            background-color: var(--color-success-light);
            color: var(--color-success-text-light);
        }
        .dark .status-badge.submitted {
            background-color: #14532d; /* green-900 */
            color: var(--color-success-dark);
        }
        
        .status-badge.overdue {
            background-color: var(--color-danger-light);
            color: var(--color-danger-text-light);
        }
        .dark .status-badge.overdue {
            background-color: #7f1d1d; /* red-900 */
            color: var(--color-danger-dark);
        }

        .status-badge.not-submitted {
            background-color: var(--color-warning-light);
            color: var(--color-warning-text-light);
        }
        .dark .status-badge.not-submitted {
            background-color: #78350f; /* amber-900 */
            color: var(--color-warning-dark);
        }

        /* Vùng hành động */
        .assignment-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .assignment-actions>* {
            width: 100%;
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

        .assignment-actions>*:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .assignment-actions .action-icon {
            width: 1.25rem;
            height: 1.25rem;
        }

        /* Các kiểu nút */
        .assignment-actions .action-primary {
            background-color: var(--color-primary);
            color: white;
        }
        .assignment-actions .action-primary:hover {
            background-color: var(--color-primary-dark-bg);
        }
        .assignment-actions .action-secondary {
            background-color: var(--bg-light-card);
            color: var(--color-text-light-secondary);
            border-color: var(--border-light);
        }
        .dark .assignment-actions .action-secondary {
            background-color: var(--bg-dark-card);
            color: var(--color-text-dark-secondary);
            border-color: var(--border-dark);
        }
        .assignment-actions .action-secondary:hover {
            border-color: var(--color-text-light-secondary);
            color: var(--color-text-light-primary);
        }
        .dark .assignment-actions .action-secondary:hover {
            border-color: var(--color-text-dark-secondary);
            color: var(--color-text-dark-primary);
        }
        .assignment-actions .action-disabled {
            background-color: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
            pointer-events: none;
        }
        .dark .assignment-actions .action-disabled {
            background-color: #334155;
            color: #64748b;
        }
        .dark .assignment-actions .action-disabled:hover {
            transform: none;
            box-shadow: none;
        }

        /* Trạng thái trống */
        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            background-color: var(--bg-light-card);
            border-radius: var(--border-radius-lg);
            border: 1px solid var(--border-light);
        }
        .dark .empty-state {
            background-color: var(--bg-dark-card);
            border-color: var(--border-dark);
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
        
        /* Pagination */
        .pagination-container nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Responsive Design */
        @media (min-width: 1024px) {
            .assignment-list-item {
                flex-direction: row;
                align-items: stretch;
            }
            .assignment-item-main {
                border-right: 1px solid var(--border-light);
            }
            .dark .assignment-item-main {
                border-right-color: var(--border-dark);
            }
            .assignment-item-meta {
                border-top: none;
                flex-shrink: 0;
                width: 320px;
            }
            .assignment-actions {
                flex-direction: row;
                justify-content: flex-end;
            }
            .assignment-actions>* {
                width: auto;
            }
        }

        /* ====================================================================== */
        /* ======================= SUBMISSION MODAL STYLES ====================== */
        /* ====================================================================== */

        .submission-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(15, 23, 42, 0.75); /* slate-900/75 */
        }

        .submission-modal-container {
            position: relative;
            width: 100%;
            max-width: 56rem; /* max-w-5xl */
            background-color: var(--bg-light-card);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            transform: scale(1);
            transition: all 0.3s ease;
            margin: 1rem;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }
        .dark .submission-modal-container {
            background-color: var(--bg-dark-card);
        }

        .submission-modal-header {
            padding: var(--spacing-lg);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-shrink: 0;
        }
        .dark .submission-modal-header {
            border-color: var(--border-dark);
        }

        .submission-modal-title {
            font-size: 1.5rem; /* text-2xl */
            font-weight: 700;
            color: var(--color-text-light-primary);
        }
        .dark .submission-modal-title {
            color: var(--color-text-dark-primary);
        }

        .submission-modal-subtitle {
            margin-top: 0.25rem;
            font-size: 0.875rem; /* text-sm */
            color: var(--color-text-light-secondary);
        }
        .dark .submission-modal-subtitle {
            color: var(--color-text-dark-secondary);
        }

        .submission-modal-close-btn {
            padding: 0.5rem;
            margin: -0.5rem;
            color: var(--color-text-light-secondary);
            border-radius: 9999px;
            transition: color 0.2s;
            background: none;
            border: none;
            cursor: pointer;
        }
        .submission-modal-close-btn:hover {
            color: var(--color-danger);
        }
        .dark .submission-modal-close-btn:hover {
            color: var(--color-danger-dark);
        }

        .submission-modal-body {
            padding: var(--spacing-lg);
            overflow-y: auto;
            flex-grow: 1;
        }

        .submission-modal-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-xl);
        }

        @media(min-width: 768px) {
            .submission-modal-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .submission-form-area {
            grid-column: span 1 / span 1;
        }
        @media(min-width: 768px) {
            .submission-form-area {
                grid-column: span 2 / span 2;
            }
        }

        .submission-info-area {
            grid-column: span 1 / span 1;
        }

        .form-group {
            margin-bottom: var(--spacing-lg);
        }

        .form-label {
            display: block;
            margin-bottom: var(--spacing-sm);
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-light-primary);
        }
        .dark .form-label {
            color: var(--color-text-dark-primary);
        }

        /* Custom Radio Buttons */
        .radio-group {
            display: flex;
            gap: var(--spacing-lg);
        }
        .radio-label {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            cursor: pointer;
            font-size: 0.875rem;
            color: var(--color-text-light-primary);
        }
        .dark .radio-label {
            color: var(--color-text-dark-primary);
        }
        .radio-label input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .radio-custom {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid var(--border-light);
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .dark .radio-custom {
            border-color: var(--border-dark);
        }
        .radio-custom::after {
            content: '';
            width: 0.75rem;
            height: 0.75rem;
            background-color: var(--color-primary);
            border-radius: 9999px;
            transform: scale(0);
            transition: transform 0.2s;
        }
        .radio-label input[type="radio"]:checked + .radio-custom {
            border-color: var(--color-primary);
        }
        .radio-label input[type="radio"]:checked + .radio-custom::after {
            transform: scale(1);
        }

        /* Custom File Input */
        .file-input-wrapper input[type="file"] {
            display: none;
        }
        .file-input-label {
            display: inline-block;
            padding: var(--spacing-xs) var(--spacing-md);
            background-color: var(--bg-light-section);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-md);
            cursor: pointer;
            font-weight: 500;
            color: var(--color-text-light-primary);
            transition: background-color 0.2s;
        }
        .dark .file-input-label {
            background-color: #334155;
            border-color: var(--border-dark);
            color: var(--color-text-dark-primary);
        }
        .file-input-label:hover {
            background-color: var(--border-light);
        }
        .dark .file-input-label:hover {
            background-color: #475569;
        }
        .file-name {
            margin-left: var(--spacing-md);
            font-size: 0.875rem;
            color: var(--color-text-light-secondary);
        }
        .dark .file-name {
            color: var(--color-text-dark-secondary);
        }


        /* Textarea and Input */
        .form-input, .form-textarea {
            width: 100%;
            border-radius: var(--border-radius-md);
            border: 1px solid var(--border-light);
            background-color: var(--bg-light-card);
            color: var(--color-text-light-primary);
            padding: var(--spacing-sm);
        }
        .dark .form-input, .dark .form-textarea {
            background-color: #334155;
            border-color: var(--border-dark);
            color: var(--color-text-dark-primary);
        }
        .form-input:focus, .form-textarea:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 2px var(--color-primary-light);
        }
        .dark .form-input:focus, .dark .form-textarea:focus {
            box-shadow: 0 0 0 2px var(--color-primary-dark-bg);
        }

        /* Info Box */
        .info-box {
            background-color: var(--bg-light-section);
            padding: var(--spacing-lg);
            border-radius: var(--border-radius-lg);
            border: 1px solid var(--border-light);
        }
        .dark .info-box {
            background-color: var(--bg-dark-section);
            border-color: var(--border-dark);
        }
        .info-box-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--color-text-light-primary);
            padding-bottom: var(--spacing-md);
            border-bottom: 1px solid var(--border-light);
            margin-bottom: var(--spacing-md);
        }
        .dark .info-box-title {
            color: var(--color-text-dark-primary);
            border-color: var(--border-dark);
        }
        .info-list {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-sm);
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.875rem;
        }
        .info-item dt {
            color: var(--color-text-light-secondary);
        }
        .dark .info-item dt {
            color: var(--color-text-dark-secondary);
        }
        .info-item dd {
            font-weight: 500;
            color: var(--color-text-light-primary);
        }
        .dark .info-item dd {
            color: var(--color-text-dark-primary);
        }

        /* Modal Footer */
        .submission-modal-footer {
            padding: var(--spacing-md) var(--spacing-lg);
            border-top: 1px solid var(--border-light);
            display: flex;
            justify-content: flex-end;
            gap: var(--spacing-sm);
            flex-shrink: 0;
        }
        .dark .submission-modal-footer {
            border-color: var(--border-dark);
        }

        .modal-button {
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: var(--border-radius-md);
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-xs);
            border: 1px solid transparent;
            cursor: pointer;
            font-size: 0.875rem;
        }
        .modal-button-secondary {
            background-color: var(--bg-light-section);
            color: var(--color-text-light-secondary);
            border-color: var(--border-light);
        }
        .dark .modal-button-secondary {
            background-color: #334155;
            color: var(--color-text-dark-secondary);
            border-color: var(--border-dark);
        }
        .modal-button-secondary:hover {
            background-color: var(--border-light);
        }
        .dark .modal-button-secondary:hover {
            background-color: #475569;
        }
        .modal-button-primary {
            background-color: var(--color-success);
            color: white;
        }
        .modal-button-primary:hover {
            background-color: var(--color-success-hover);
        }
        .modal-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>

    <div class="assignment-list-page">
        <div class="assignment-list-container">

            <!-- Thanh bộ lọc -->
            <div class="filter-bar">
                <div class="filter-controls">
                    <div class="filter-group">
                        <label for="courseFilter" class="filter-label">Khóa học:</label>
                        <div class="filter-input-wrapper">
                            <select
                                id="courseFilter"
                                wire:model.live="courseId"
                                class="filter-input"
                            >
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
                             <input
                                id="searchFilter"
                                wire:model.live.debounce.300ms="search"
                                type="search"
                                placeholder="Tìm tiêu đề bài tập..."
                                class="filter-input"
                            >
                        </div>
                    </div>
                </div>
                <div class="filter-tabs">
                     <button wire:click="setFilter('all')" class="filter-tab-button {{ $filter === 'all' ? 'active' : '' }}">Tất cả</button>
                     <button wire:click="setFilter('not_submitted')" class="filter-tab-button {{ $filter === 'not_submitted' ? 'active' : '' }}">Chưa nộp</button>
                     <button wire:click="setFilter('overdue')" class="filter-tab-button {{ $filter === 'overdue' ? 'active' : '' }}">Quá hạn</button>
                     <button wire:click="setFilter('submitted')" class="filter-tab-button {{ $filter === 'submitted' ? 'active' : '' }}">Đã nộp</button>
                </div>
            </div>

            <!-- Danh sách bài tập -->
            <div class="assignment-list">
                @forelse ($this->assignments as $assignment)
                    @php
                        $submission = $assignment->submissions->where('student_id', auth()->id())->first();
                        $isSubmitted = (bool)$submission;
                        $isOverdue = !$isSubmitted && $assignment->due_at->isPast();
                    @endphp
                    <div wire:key="{{ $assignment->id }}" class="assignment-list-item">
                        <!-- Phần thông tin chính -->
                        <div class="assignment-item-main">
                            <p class="assignment-course-badge">Khóa học: {{ $assignment->course->title ?? 'Khóa học không xác định' }}</p>
                            <h3 class="assignment-title">{{ $assignment->title }}</h3>
                            <div class="assignment-details">
                                <div class="assignment-detail-item">
                                    <x-heroicon-o-calendar-days class="icon due-date" />
                                    <span>Hạn nộp: {{ $assignment->due_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="assignment-detail-item">
                                    <x-heroicon-o-star class="icon points" />
                                    <span>{{ number_format($assignment->max_points, 1) }} điểm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Phần Meta & Hành động -->
                        <div class="assignment-item-meta">
                            <div class="assignment-status-info">
                                @if($isSubmitted)
                                    <span class="status-badge submitted"><x-heroicon-s-check-circle class="icon" /> Đã nộp</span>
                                @elseif($isOverdue)
                                    <span class="status-badge overdue"><x-heroicon-s-exclamation-circle class="icon" /> Quá hạn</span>
                                @else
                                    <span class="status-badge not-submitted"><x-heroicon-s-clock class="icon" /> Chưa nộp</span>
                                @endif
                            </div>

                            <div class="assignment-actions">
                                <button
                                    type="button"
                                    wire:click="openInstructionsModal('{{ $assignment->id }}')"
                                    class="action-secondary"
                                >
                                    <x-heroicon-o-document-text class="action-icon" />
                                    <span>Hướng dẫn</span>
                                </button>

                                @if($isSubmitted)
                                    <button disabled class="action-disabled">
                                        <x-heroicon-o-check class="action-icon" />
                                        <span>Đã nộp bài</span>
                                    </button>
                                @elseif($isOverdue)
                                     <button disabled class="action-disabled">
                                        <x-heroicon-o-x-circle class="action-icon" />
                                        <span>Đã quá hạn</span>
                                    </button>
                                @else
                                    <button
                                        type="button"
                                        wire:click="openSubmissionModal('{{ $assignment->id }}')"
                                        class="action-primary"
                                    >
                                        <x-heroicon-o-arrow-up-tray class="action-icon" />
                                        <span>Nộp bài</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon-wrapper">
                            <x-heroicon-o-document-magnifying-glass class="empty-state-icon" />
                        </div>
                        <h3 class="empty-state-title">Không tìm thấy bài tập</h3>
                        <p class="empty-state-text">Không có bài tập nào phù hợp với bộ lọc hiện tại.</p>
                    </div>
                @endforelse
            </div>

            @if ($this->assignments->hasPages())
                <div class="pt-8 pagination-container">
                    {{ $this->assignments->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- INSTRUCTIONS MODAL --}}
    @if ($showInstructionsModal && $selectedAssignment)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75" x-trap.noscroll="true">
            <div @click.away="$wire.closeInstructionsModal()" class="relative w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl shadow-xl transform transition-all m-4 max-h-[90vh] flex flex-col">
                {{-- Header --}}
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Hướng dẫn: {{ $selectedAssignment->title }}</h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Môn học: {{ $selectedAssignment->course->title ?? 'Không xác định' }}</p>
                        </div>
                        <button type="button" wire:click="closeInstructionsModal" class="p-2 -m-2 text-gray-400 hover:text-red-500 dark:hover:text-red-400 rounded-full transition-colors">
                            <span class="sr-only">Đóng</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
                {{-- Content --}}
                <div class="p-6 overflow-y-auto flex-1">
                    <div class="prose prose-lg dark:prose-invert max-w-none">
                        {!! $selectedAssignment->instructions ?? '<p>Không có hướng dẫn chi tiết.</p>' !!}
                    </div>
                </div>
                {{-- Footer --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                     <button type="button" wire:click="closeInstructionsModal" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- SUBMISSION MODAL --}}
    @if ($showSubmissionModal && $selectedAssignment)
        <div class="submission-modal-backdrop" 
             x-data="{ 
                submissionType: @entangle('submissionType'),
                fileName: ''
             }"
             x-trap.noscroll="true"
             @file-input.window="fileName = $event.detail.name"
        >
            <div @click.away="$wire.closeSubmissionModal()" class="submission-modal-container">
                <form wire:submit.prevent="submitAssignment" class="flex flex-col h-full">
                    <!-- Header -->
                    <div class="submission-modal-header">
                        <div>
                            <h2 class="submission-modal-title">{{ $selectedAssignment->title }}</h2>
                            <p class="submission-modal-subtitle">Môn học: {{ $selectedAssignment->course->title ?? 'Không xác định' }}</p>
                        </div>
                        <button type="button" wire:click="closeSubmissionModal" class="submission-modal-close-btn">
                            <span class="sr-only">Đóng</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="submission-modal-body">
                        <div class="submission-modal-grid">
                            <!-- Left Column: Form -->
                            <div class="submission-form-area">
                                <div class="form-group">
                                    <label class="form-label">Phương thức nộp bài</label>
                                    <div class="radio-group">
                                        <label class="radio-label">
                                            <input type="radio" x-model="submissionType" value="file" wire:model.live="submissionType">
                                            <span class="radio-custom"></span>
                                            <span>Tải lên tệp</span>
                                        </label>
                                        <label class="radio-label">
                                            <input type="radio" x-model="submissionType" value="link" wire:model.live="submissionType">
                                            <span class="radio-custom"></span>
                                            <span>Nộp liên kết</span>
                                        </label>
                                    </div>
                                </div>

                                <div x-show="submissionType === 'file'" class="form-group">
                                    <label for="file-upload" class="form-label">Tệp bài nộp</label>
                                    <div class="file-input-wrapper">
                                        <input type="file" wire:model="file" id="file-upload" x-on:change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''">
                                        <label for="file-upload" class="file-input-label">Choose File</label>
                                        <span x-text="fileName" class="file-name">No file chosen</span>
                                        @error('file') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div x-show="submissionType === 'link'" class="form-group">
                                    <label for="link-url" class="form-label">URL liên kết</label>
                                    <input type="url" wire:model.defer="link_url" id="link-url" placeholder="https://..." class="form-input">
                                    @error('link_url') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="notes" class="form-label">Ghi chú (tùy chọn)</label>
                                    <textarea wire:model.defer="notes" id="notes" rows="4" class="form-textarea"></textarea>
                                    @error('notes') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Right Column: Info -->
                            <div class="submission-info-area">
                                <div class="info-box">
                                    <h3 class="info-box-title">Thông tin</h3>
                                    <dl class="info-list">
                                        <div class="info-item">
                                            <dt>Hạn nộp bài</dt>
                                            <dd>{{ $selectedAssignment->due_at->format('H:i, d/m/Y') }}</dd>
                                        </div>
                                        <div class="info-item">
                                            <dt>Điểm tối đa</dt>
                                            <dd>{{ number_format($selectedAssignment->max_points, 1) }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="submission-modal-footer">
                        <button type="button" wire:click="closeSubmissionModal" class="modal-button modal-button-secondary">Quay lại</button>
                        <button type="submit" class="modal-button modal-button-primary" wire:loading.attr="disabled" wire:target="submitAssignment, file">
                            <span wire:loading.remove wire:target="submitAssignment, file">Nộp bài</span>
                            <span wire:loading wire:target="submitAssignment, file">Đang xử lý...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</x-filament-panels::page>
