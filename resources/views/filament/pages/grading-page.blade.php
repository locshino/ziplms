<x-filament-panels::page>

    <div class="min-h-screen bg-slate-100 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Filter Bar -->
            <div class="mb-8 bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 flex flex-col gap-6">
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="flex items-center gap-3 flex-grow">
                        <label for="courseFilter" class="text-sm font-medium text-slate-800 dark:text-slate-100">Khóa học:</label>
                        <div class="flex-grow min-w-[200px]">
                            <select id="courseFilter" wire:model.live="courseId" class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-3 py-2">
                                <option value="">Tất cả các khóa học</option>
                                @foreach($this->courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-grow">
                        <label for="searchFilter" class="text-sm font-medium text-slate-800 dark:text-slate-100">Tìm kiếm:</label>
                        <div class="flex-grow min-w-[200px]">
                            <input id="searchFilter" wire:model.live.debounce.300ms="search" type="search" placeholder="Tìm tiêu đề bài tập..." class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-3 py-2">
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 bg-slate-100 dark:bg-slate-900 p-2 rounded-lg">
                    <button wire:click="setFilter('all')" class="flex-grow text-center px-3 py-2 rounded-lg text-sm font-medium cursor-pointer border-none transition-all duration-200 {{ $filter === 'all' ? 'bg-blue-500 text-white shadow-lg' : 'bg-transparent text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">Tất cả</button>
                    <button wire:click="setFilter('ungraded')" class="flex-grow text-center px-3 py-2 rounded-lg text-sm font-medium cursor-pointer border-none transition-all duration-200 {{ $filter === 'ungraded' ? 'bg-blue-500 text-white shadow-lg' : 'bg-transparent text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">Chưa chấm</button>
                    <button wire:click="setFilter('graded')" class="flex-grow text-center px-3 py-2 rounded-lg text-sm font-medium cursor-pointer border-none transition-all duration-200 {{ $filter === 'graded' ? 'bg-blue-500 text-white shadow-lg' : 'bg-transparent text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">Đã chấm</button>
                </div>
            </div>
            <!-- Assignment List -->
            <div class="grid grid-cols-1 gap-6">
                @forelse ($assignments as $assignment)
                    <div wire:key="{{ $assignment->id }}" class="flex flex-col bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="p-6 flex-grow">
                            <div class="flex items-center gap-4 flex-wrap mb-3">
                                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 leading-tight">{{ $assignment->title }}</h3>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">
                                    {{ $assignment->graded_submissions_count }} / {{ $assignment->submissions_count }} Đã chấm
                                </span>
                            </div>
                            <p class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full mb-3">{{ $assignment->course->title ?? 'Khóa học không xác định' }}</p>
                            <div class="flex flex-wrap gap-4 mt-3">
                                <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                                    <x-heroicon-o-calendar-days class="w-5 h-5 text-red-600 dark:text-red-400" />
                                    <span class="font-medium">Hạn: {{ $assignment->due_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                                    <x-heroicon-o-star class="w-5 h-5 text-amber-500 dark:text-amber-400" />
                                    <span class="font-medium">{{ rtrim(rtrim(number_format($assignment->max_points, 2), '0'), '.') }} điểm</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 px-6 pb-6">
                            <button type="button" wire:click="openInstructionsModal('{{ $assignment->id }}')" class="font-semibold px-5 py-2.5 rounded-lg transition-all duration-200 inline-flex items-center justify-center gap-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:-translate-y-0.5 hover:shadow-lg">
                                Xem hướng dẫn
                            </button>
                            <button type="button" wire:click="openSubmissionsModal('{{ $assignment->id }}')" class="font-semibold px-5 py-2.5 rounded-lg transition-all duration-200 inline-flex items-center justify-center gap-2 border border-transparent bg-blue-500 text-white hover:bg-blue-600 hover:-translate-y-0.5 hover:shadow-lg">
                                Xem bài nộp
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
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

    <!-- Grading Page -->
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Filter Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex flex-col">
                            <label for="courseFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Khóa học:</label>
                            <div class="relative">
                                <select id="courseFilter" wire:model.live="courseId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Tất cả các khóa học</option>
                                    @foreach($this->courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <label for="searchFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tìm kiếm:</label>
                            <div class="relative">
                                <input id="searchFilter" wire:model.live.debounce.300ms="search" type="search" placeholder="Tìm tiêu đề bài tập..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="setFilter('all')" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Tất cả</button>
                    <button wire:click="setFilter('ungraded')" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'ungraded' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Chưa chấm</button>
                    <button wire:click="setFilter('graded')" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'graded' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Đã chấm</button>
                </div>
            </div>

            <!-- Assignment List -->
            <div class="space-y-4">
                @forelse ($assignments as $assignment)
                    <div wire:key="{{ $assignment->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $assignment->title }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        {{ $assignment->graded_submissions_count }} / {{ $assignment->submissions_count }} Đã chấm
                                    </span>
                                </div>
                                <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mb-3">{{ $assignment->course->title ?? 'Khóa học không xác định' }}</p>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-calendar-days class="w-4 h-4 text-red-500" />
                                        <span>Hạn: {{ $assignment->due_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-star class="w-4 h-4 text-yellow-500" />
                                        <span>{{ rtrim(rtrim(number_format($assignment->max_points, 2), '0'), '.') }} điểm</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2">
                                <button type="button" wire:click="openInstructionsModal('{{ $assignment->id }}')" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    Xem hướng dẫn
                                </button>
                                <button type="button" wire:click="openSubmissionsModal('{{ $assignment->id }}')" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 transition-colors">
                                    Xem bài nộp
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Không tìm thấy bài tập</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Không có bài tập nào phù hợp với bộ lọc hiện tại.</p>
                    </div>
                @endforelse

                @if ($assignments->hasPages())
                    <div class="mt-8">
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

    {{-- GRADING MODAL --}}
    @if($showGradingModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:click="closeGradingModal">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75"></div>
                <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg" wire:click.stop>
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Chấm điểm bài tập</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedAssignment?->title }}</p>
                        </div>
                        <button wire:click="closeGradingModal" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <x-heroicon-o-x-mark class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="px-6 py-4 max-h-96 overflow-y-auto">
                        @if($submissions && $submissions->count() > 0)
                            <div class="space-y-6">
                                @foreach($submissions as $submission)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                        <div class="mb-4">
                                            <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $submission->student->name }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Nộp lúc: {{ $submission->submitted_at->format('d/m/Y H:i') }}</p>
                                        </div>

                                        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            @if($submission->submission_text)
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nội dung bài làm:</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $submission->submission_text }}</p>
                                            @endif

                                            @if($submission->file_path)
                                                <button wire:click="downloadSubmission('{{ $submission->id }}')" class="mt-2 inline-flex items-center gap-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                                    <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                                                    Tải xuống tệp đính kèm
                                                </button>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Điểm</label>
                                                <input type="number" 
                                                       wire:model="grades.{{ $submission->id }}.points" 
                                                       min="0" 
                                                       max="{{ $selectedAssignment?->max_points }}" 
                                                       step="0.1" 
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                       placeholder="0">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phản hồi</label>
                                                <textarea wire:model="grades.{{ $submission->id }}.feedback" 
                                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                          rows="2" 
                                                          placeholder="Nhập phản hồi cho sinh viên..."></textarea>
                                            </div>
                                            <button wire:click="saveGrade('{{ $submission->id }}')" 
                                                    class="px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors disabled:opacity-70 disabled:cursor-not-allowed"
                                                    wire:loading.attr="disabled">
                                                <span wire:loading.remove wire:target="saveGrade('{{ $submission->id }}')">Lưu điểm</span>
                                                <span wire:loading wire:target="saveGrade('{{ $submission->id }}')">Đang lưu...</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-600 dark:text-gray-400">Chưa có bài nộp nào cho bài tập này.</p>
                            </div>
                        @endif
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                        <button wire:click="closeGradingModal" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
