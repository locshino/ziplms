<x-filament-panels::page>
    <style>
        /* CSS Variables for consistent theming */
        :root {
            --color-primary: #3b82f6;
            --color-primary-hover: #2563eb;
            --color-primary-light: #dbeafe;
            --color-primary-text-light: #1e40af;
            --color-primary-dark: #60a5fa;
            --color-primary-dark-bg: #1e3a8a;
            --color-primary-text-dark: #93c5fd;

            --color-success: #10b981;
            --color-success-hover: #059669;
            --color-success-dark: #34d399;

            --color-info: #06b6d4;
            --color-info-dark: #22d3ee;

            --color-text-light-primary: #111827;
            --color-text-light-secondary: #6b7280;
            --color-text-light-tertiary: #9ca3af;

            --color-text-dark-primary: #f9fafb;
            --color-text-dark-secondary: #d1d5db;
            --color-text-dark-tertiary: #9ca3af;

            --bg-light-page: #f1f5f9;
            --bg-light-card: #ffffff;
            --bg-light-section: #f8fafc;
            --bg-dark-page: #0f172a;
            --bg-dark-card: #1e293b;
            --bg-dark-section: #0f172a;

            --border-light: #e2e8f0;
            --border-dark: #334155;

            --spacing-xs: 0.5rem;
            --spacing-sm: 0.75rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;

            --border-radius-md: 0.75rem;
            --border-radius-lg: 1rem;
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        /* Base Page Layout */
        .document-list-page {
            min-height: 100vh;
            background-color: var(--bg-light-page);
        }

        .dark .document-list-page {
            background-color: var(--bg-dark-page);
        }

        .document-list-container {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding: var(--spacing-lg);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
        }

        .stat-card {
            background-color: var(--bg-light-card);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-lg);
            padding: var(--spacing-lg);
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        .dark .stat-card {
            background-color: var(--bg-dark-card);
            border-color: var(--border-dark);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-0.125rem);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--color-text-light-secondary);
            margin-bottom: var(--spacing-xs);
        }

        .dark .stat-label {
            color: var(--color-text-dark-secondary);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: var(--color-primary);
        }

        .dark .stat-value {
            color: var(--color-primary-dark);
        }

        /* Filter Bar */
        .filter-bar {
            background-color: var(--bg-light-card);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-lg);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
        }

        .dark .filter-bar {
            background-color: var(--bg-dark-card);
            border-color: var(--border-dark);
        }

        .filter-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--color-text-light-primary);
            margin-bottom: var(--spacing-md);
        }

        .dark .filter-title {
            color: var(--color-text-dark-primary);
        }

        .filter-controls {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-md);
            align-items: center;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-xs);
        }

        .filter-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-light-secondary);
        }

        .dark .filter-label {
            color: var(--color-text-dark-secondary);
        }

        .filter-select {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-light);
            border-radius: 0.5rem;
            background-color: var(--bg-light-card);
            color: var(--color-text-light-primary);
            font-size: 0.875rem;
        }

        .dark .filter-select {
            border-color: var(--border-dark);
            background-color: var(--bg-dark-card);
            color: var(--color-text-dark-primary);
        }

        .clear-filters-btn {
            padding: 0.5rem 1rem;
            background-color: var(--color-text-light-tertiary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .clear-filters-btn:hover {
            background-color: var(--color-text-light-secondary);
        }

        /* Document List */
        .document-list {
            background-color: var(--bg-light-card);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
        }

        .dark .document-list {
            background-color: var(--bg-dark-card);
            border-color: var(--border-dark);
        }

        /* Document Files */
        .document-files {
            margin-top: var(--spacing-md);
            padding: var(--spacing-md);
            background-color: var(--bg-light-section);
            border-radius: var(--border-radius-md);
            border: 1px solid var(--border-light);
        }

        .dark .document-files {
            background-color: var(--bg-dark-section);
            border-color: var(--border-dark);
        }

        .files-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text-light-primary);
            margin: 0 0 var(--spacing-sm) 0;
        }

        .dark .files-title {
            color: var(--color-text-dark-primary);
        }

        .files-list {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-xs);
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-xs) var(--spacing-sm);
            background-color: var(--bg-light-card);
            border-radius: 0.5rem;
            border: 1px solid var(--border-light);
        }

        .dark .file-item {
            background-color: var(--bg-dark-card);
            border-color: var(--border-dark);
        }

        .file-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .file-name {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-light-primary);
        }

        .dark .file-name {
            color: var(--color-text-dark-primary);
        }

        .file-size {
            font-size: 0.75rem;
            color: var(--color-text-light-tertiary);
        }

        .dark .file-size {
            color: var(--color-text-dark-tertiary);
        }

        .file-actions {
            display: flex;
            gap: var(--spacing-xs);
        }

        .document-list-header {
            background-color: var(--bg-light-section);
            padding: var(--spacing-lg);
            border-bottom: 1px solid var(--border-light);
        }

        .dark .document-list-header {
            background-color: var(--bg-dark-section);
            border-bottom-color: var(--border-dark);
        }

        .document-list-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-text-light-primary);
            margin: 0;
        }

        .dark .document-list-title {
            color: var(--color-text-dark-primary);
        }

        .document-list-content {
            padding: 0;
        }

        .document-list-item {
            padding: var(--spacing-lg);
            border-bottom: 1px solid var(--border-light);
            transition: background-color 0.2s;
        }

        .dark .document-list-item {
            border-bottom-color: var(--border-dark);
        }

        .document-list-item:hover {
            background-color: var(--bg-light-section);
        }

        .dark .document-list-item:hover {
            background-color: var(--bg-dark-section);
        }

        .document-list-item:last-child {
            border-bottom: none;
        }

        .document-item-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: var(--spacing-lg);
        }

        .document-item-content {
            flex: 1;
        }

        .document-course-badge {
            display: inline-block;
            background-color: var(--color-primary-light);
            color: var(--color-primary-text-light);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            margin-bottom: var(--spacing-sm);
        }

        .dark .document-course-badge {
            background-color: var(--color-primary-dark-bg);
            color: var(--color-primary-text-dark);
        }

        .document-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-text-light-primary);
            line-height: 1.4;
            margin-bottom: var(--spacing-sm);
        }

        .dark .document-title {
            color: var(--color-text-dark-primary);
        }

        .document-type-badge {
            display: inline-block;
            background-color: var(--color-info);
            color: white;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            margin-bottom: var(--spacing-sm);
        }

        .dark .document-type-badge {
            background-color: var(--color-info-dark);
        }

        .document-description {
            color: var(--color-text-light-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: var(--spacing-md);
            margin-top: var(--spacing-sm);
            padding: var(--spacing-sm);
            background-color: var(--bg-light-section);
            border-radius: var(--border-radius-md);
            border-left: 3px solid var(--color-primary);
        }

        .dark .document-description {
            color: var(--color-text-dark-secondary);
            background-color: var(--bg-dark-section);
            border-left-color: var(--color-primary-dark);
        }

        .document-meta {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-md);
            font-size: 0.75rem;
            color: var(--color-text-light-tertiary);
        }

        .dark .document-meta {
            color: var(--color-text-dark-tertiary);
        }

        .document-files-count {
            background-color: var(--color-primary-light);
            color: var(--color-primary-text-light);
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .dark .document-files-count {
            background-color: var(--color-primary-dark-bg);
            color: var(--color-primary-text-dark);
        }

        .document-meta-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .document-meta-item .icon {
            width: 1rem;
            height: 1rem;
        }

        .document-item-actions {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-sm);
            flex-shrink: 0;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            min-width: 120px;
            justify-content: center;
        }

        .action-primary {
            background-color: var(--color-primary);
            color: white;
        }

        .action-primary:hover {
            background-color: var(--color-primary-hover);
        }

        .action-success {
            background-color: var(--color-success);
            color: white;
        }

        .action-success:hover {
            background-color: var(--color-success-hover);
        }

        .action-icon {
            width: 1rem;
            height: 1rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: var(--spacing-xl);
            color: var(--color-text-light-secondary);
        }

        .dark .empty-state {
            color: var(--color-text-dark-secondary);
        }

        .empty-state .icon {
            width: 4rem;
            height: 4rem;
            margin: 0 auto var(--spacing-lg);
            color: var(--color-text-light-tertiary);
        }

        .dark .empty-state .icon {
            color: var(--color-text-dark-tertiary);
        }

        .empty-state h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: var(--spacing-sm);
        }

        .empty-state p {
            font-size: 0.875rem;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .document-list-container {
                padding: var(--spacing-md);
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filter-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .document-item-main {
                flex-direction: column;
                gap: var(--spacing-md);
            }

            .document-item-actions {
                flex-direction: row;
                justify-content: stretch;
            }

            .action-btn {
                flex: 1;
                min-width: auto;
            }

            .file-item {
                flex-direction: column;
                align-items: stretch;
                gap: var(--spacing-xs);
            }

            .file-actions {
                justify-content: stretch;
            }

            .file-actions .action-btn {
                flex: 1;
            }
        }
    </style>

    <div class="document-list-page">
        <div class="document-list-container">
            <!-- Statistics Section -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Tổng số tài liệu</div>
                    <div class="stat-value">{{ $this->getTotalDocumentsCount() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Khóa học đã đăng ký</div>
                    <div class="stat-value">{{ $this->getEnrolledCourses()->count() }}</div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-bar">
                <h2 class="filter-title">Bộ lọc tài liệu</h2>
                <div class="filter-controls">
                    <div class="filter-group">
                        <label class="filter-label">Khóa học</label>
                        <select wire:model.live="selectedCourseId" class="filter-select">
                            <option value="">Tất cả khóa học</option>
                            @foreach($this->getEnrolledCourses() as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button wire:click="clearFilters" class="clear-filters-btn">
                        Xóa bộ lọc
                    </button>
                </div>
            </div>

            <!-- Document List Section -->
            <div class="document-list">
                <div class="document-list-header">
                    <h2 class="document-list-title">
                        <x-heroicon-o-document-text class="w-6 h-6 inline mr-2" />
                        Danh sách tài liệu
                    </h2>
                </div>

                <div class="document-list-content">
                    @forelse($this->getDocuments() as $document)
                        @php
                            $type = $this->getDocumentType($document);
                            $typeLabel = $this->getDocumentTypeLabel($type);
                        @endphp

                        <div class="document-list-item">
                            <div class="document-item-main">
                                <div class="document-item-content">
                                    <div class="document-course-badge">
                                        {{ $document->course->title }}
                                    </div>

                                    <h3 class="document-title">{{ $document->title }}</h3>

                                    <div class="document-type-badge">
                                        {{ $typeLabel }}
                                    </div>

                                    @if($document->instructions)
                                        <div class="document-description">
                                            {{ Str::limit(strip_tags($document->instructions), 200) }}
                                        </div>
                                    @endif

                                    <div class="document-meta">
                                        <div class="document-meta-item">
                                            <x-heroicon-s-calendar class="icon" />
                                            Tạo: {{ $document->created_at->format('d/m/Y') }}
                                        </div>
                                        @if($document->updated_at != $document->created_at)
                                            <div class="document-meta-item">
                                                <x-heroicon-s-arrow-path class="icon" />
                                                Cập nhật: {{ $document->updated_at->format('d/m/Y') }}
                                            </div>
                                        @endif
                                        @if($document->media->count() > 0)
                                            <div class="document-meta-item">
                                                <x-heroicon-s-paper-clip class="icon" />
                                                {{ $document->media->count() }} tệp đính kèm
                                            </div>
                                        @endif
                                    </div>

                                    @if($document->media->count() > 0)
                                        <div class="document-files">
                                            <h4 class="files-title">Tệp đính kèm:</h4>
                                            <div class="files-list">
                                                @foreach($document->media as $media)
                                                    <div class="file-item">
                                                        <div class="file-info">
                                                            <span class="file-name">{{ $media->name }}</span>
                                                            <span class="file-size">{{ $media->human_readable_size }}</span>
                                                        </div>
                                                        <div class="file-actions">
                                                            {{ ($this->downloadDocumentAction)(['assignment_id' => $document->id, 'media_id' => $media->id]) }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="document-item-actions">
                                    @if($document->media->count() > 1)
                                        <!-- Download All Documents Button -->
                                        {{ ($this->downloadDocumentAction)(['assignment_id' => $document->id]) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <x-heroicon-o-document-text class="icon" />
                            <h3>Không có tài liệu nào</h3>
                            <p>Hiện tại bạn chưa có tài liệu nào có tệp đính kèm trong các khóa học đã đăng ký.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page>