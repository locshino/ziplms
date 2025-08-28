<x-filament-panels::page>

    <div class="min-h-screen bg-slate-100 dark:bg-slate-900" 
         x-data="{}" 
         x-on:refresh-assignments.window="$wire.$refresh()"
    >
        <div class="max-w-7xl mx-auto px-4 py-8">

            <div class="mb-8 bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 flex flex-col gap-6">
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="flex items-center gap-3 flex-grow">
                        <label for="courseFilter" class="text-sm font-medium text-slate-800 dark:text-slate-100">Khóa học:</label>
                        <div class="flex-grow min-w-[200px]">
                            <select
                                id="courseFilter"
                                wire:model.live="courseId"
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-3 py-2"
                            >
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
                             <input
                                id="searchFilter"
                                wire:model.live.debounce.300ms="search"
                                type="search"
                                placeholder="Tìm kiếm bài tập..."
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-3 py-2"
                            >
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                    <div class="p-1.5 flex items-center bg-slate-100 dark:bg-slate-900/50 rounded-full border border-slate-200 dark:border-slate-700">
                        @php
                            $filterNavs = [
                                ['key' => 'all', 'label' => 'Tất cả'],
                                ['key' => 'ungraded', 'label' => 'Chưa chấm'],
                                ['key' => 'graded', 'label' => 'Đã chấm'],
                            ];
                        @endphp

                        @foreach ($filterNavs as $nav)
                            <button
                                type="button"
                                wire:click="setFilter('{{ $nav['key'] }}')"
                                class="flex-1 text-center py-2 text-sm font-semibold rounded-full transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-100 dark:focus:ring-offset-slate-900 focus:ring-orange-500
                                {{ $filter === $nav['key']
                                    ? 'bg-white dark:bg-slate-700 text-orange-500 shadow-sm'
                                    : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'
                                }}"
                            >
                                {{ $nav['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 gap-6">
                @forelse ($this->courseAssignments as $courseAssignment)
                    @php
                        $assignment = $courseAssignment->assignment;
                        $isGradingExpired = $courseAssignment->end_at && now()->isAfter($courseAssignment->end_at);
                        $hasDocuments = $assignment->hasMedia('assignment_documents');
                    @endphp
                    <div wire:key="{{ $courseAssignment->id }}" class="relative flex flex-col lg:flex-row lg:items-stretch bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        @if($isGradingExpired)
                            <div class="absolute top-3 right-3 bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full dark:bg-red-900 dark:text-red-200 z-10">
                                Đã quá hạn
                            </div>
                        @endif

                        <div class="flex-grow p-6 lg:border-r lg:border-slate-200 lg:dark:border-slate-700 @if($isGradingExpired) opacity-70 @endif">
                             @php
                                $courseId = $courseAssignment->course->id ?? null;
                                $colorClasses = $this->courseColors[$courseId] ?? ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-800 dark:text-gray-200'];
                            @endphp

                            <div class="inline-block {{ $colorClasses['bg'] }} {{ $colorClasses['text'] }} text-xs font-semibold px-3 py-1 rounded-full mb-3">
                                {{ $courseAssignment->course->title ?? 'Khóa học không xác định' }}
                            </div>

                            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4 leading-tight">{{ $assignment->title }}</h3>
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                    <x-heroicon-o-calendar-days class="w-5 h-5 text-red-500" />
                                    <span class="font-medium">Hạn nộp: {{ $courseAssignment->end_submission_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                    <x-heroicon-o-star class="w-5 h-5 text-yellow-500" />
                                    <span class="font-medium">{{ number_format($assignment->max_points, 1) }} điểm</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t lg:border-t-0 lg:border-l border-slate-200 dark:border-slate-700 flex flex-col justify-center gap-4 lg:w-80 lg:flex-shrink-0 @if($isGradingExpired) opacity-70 @endif">
                            <div class="flex justify-start w-full">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                    <x-heroicon-s-user-group class="w-4 h-4" />
                                    {{ $assignment->graded_students_count }} / {{ $assignment->submitted_students_count }} đã chấm
                                </span>
                            </div>
                            <div class="flex flex-col gap-2 w-full">
                                @if(!$isGradingExpired)
                                <button
                                    type="button"
                                    wire:click="openInstructionsModal('{{ $courseAssignment->id }}')"
                                    class="w-full font-semibold px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-800 dark:hover:text-slate-100 hover:-translate-y-0.5 hover:shadow-lg"
                                >
                                    <x-heroicon-o-document-text class="w-5 h-5" />
                                    <span>Hướng dẫn</span>
                                </button>
                                @if($hasDocuments)
                                    <button
                                        type="button"
                                        wire:click="openDocumentsModal('{{ $courseAssignment->id }}')"
                                        class="w-full font-semibold px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-800 dark:hover:text-slate-100 hover:-translate-y-0.5 hover:shadow-lg"
                                    >
                                        <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
                                        <span>Tài liệu</span>
                                    </button>
                                @endif
                                @endif
                                <button
                                    type="button"
                                    wire:click="openSubmissionsModal('{{ $courseAssignment->id }}')"
                                    class="w-full font-semibold px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2
                                           {{ $isGradingExpired
                                               ? 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600'
                                               : 'border-transparent bg-blue-500 text-white hover:bg-blue-600 hover:-translate-y-0.5 hover:shadow-lg' }}"
                                >
                                    @if($isGradingExpired)
                                        <x-heroicon-o-eye class="w-5 h-5" />
                                    @else
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    @endif
                                    <span>{{ $isGradingExpired ? 'Xem điểm' : 'Chấm bài' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 col-span-1">
                        <div class="mx-auto w-24 h-24 bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center mb-6">
                            <x-heroicon-o-document-magnifying-glass class="w-12 h-12 text-slate-400 dark:text-slate-500" />
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100 mb-2">Không tìm thấy bài tập</h3>
                        <p class="text-slate-600 dark:text-slate-400">Không có bài tập nào phù hợp với bộ lọc hiện tại.</p>
                    </div>
                @endforelse
            </div>

            @if ($this->courseAssignments->hasPages())
                <div class="mt-8">
                    {{ $this->courseAssignments->links() }}
                </div>
            @endif
        </div>
    </div>


    @if($showInstructionsModal && $selectedCourseAssignment)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/75" wire:click.self="closeInstructionsModal">
        <div class="relative w-full max-w-4xl bg-white dark:bg-slate-800 rounded-xl shadow-xl transform scale-100 transition-all duration-300 mx-4 max-h-[90vh] flex flex-col" wire:click.stop>
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-start flex-shrink-0">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Hướng dẫn: {{ $selectedCourseAssignment->assignment->title }}</h2>
                </div>
                <button wire:click="closeInstructionsModal" class="p-2 -m-2 text-slate-500 dark:text-slate-400 rounded-full transition-colors hover:text-red-500 dark:hover:text-red-400 bg-none border-none cursor-pointer">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>
            <div class="px-6 py-4 overflow-y-auto flex-grow">
                <div class="prose max-w-none dark:prose-invert">
                    {!! $selectedCourseAssignment->assignment->description !!}
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 flex-shrink-0">
                <x-filament::button color="gray" @click="$wire.closeInstructionsModal()">
                    Đóng
                </x-filament::button>
            </div>
        </div>
    </div>
    @endif

    @if ($selectedCourseAssignment)
        <x-filament::modal id="submissions-modal" width="5xl" :close-by-clicking-away="false" :close-on-escape="false" @close.stop="$wire.closeSubmissionsModal()">
            <x-slot name="heading">
                Chấm bài: {{ $selectedCourseAssignment->assignment->title }}
            </x-slot>
            <x-slot name="description">
                Điểm tối đa: {{ number_format($selectedCourseAssignment->assignment->max_points, 1) }}
            </x-slot>

            <div class="border-b border-slate-200 dark:border-slate-700 mb-4 flex justify-between items-center flex-wrap gap-4">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <button wire:click="setSubmissionView('submitted')" type="button"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                                   {{ $submissionView === 'submitted'
                                       ? 'border-blue-500 text-blue-600'
                                       : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-600' }}">
                        Đã nộp ({{ count($submissions) }})
                    </button>
                    <button wire:click="setSubmissionView('not_submitted')" type="button"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                                   {{ $submissionView === 'not_submitted'
                                       ? 'border-blue-500 text-blue-600'
                                       : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-600' }}">
                        Chưa nộp ({{ count($notSubmittedStudents) }})
                    </button>
                </nav>
                <div class="w-full sm:w-auto">
                    <input
                        wire:model.live.debounce.300ms="studentSearch"
                        type="search"
                        placeholder="Tìm sinh viên..."
                        class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-3 py-2 text-sm"
                    >
                </div>
            </div>

            @if ($isGradingExpired)
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-slate-700 dark:text-red-400" role="alert">
                    <span class="font-medium">Đã hết hạn chấm bài!</span> Bạn không thể thay đổi điểm sau ngày {{ $selectedCourseAssignment->end_at->format('d/m/Y H:i') }}.
                </div>
            @endif

            <div>
                @if ($submissionView === 'submitted')
                    <div class="space-y-4">
                        @forelse ($this->paginatedSubmissions as $submission)
                            <div wire:key="submission-{{ $submission->id }}"
                                 class="rounded-lg p-4 border-2 transition-colors duration-300 @if(is_null($submission->points)) border-orange-400 dark:border-orange-500 @else border-slate-200 dark:border-slate-700 @endif">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-3">
                                            @if($submission->student)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                     src="{{ $submission->student->getFirstMediaUrl('avatar') ?: 'https://ui-avatars.com/api/?name=' . urlencode($submission->student->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                                     alt="{{ $submission->student->name }}">
                                                <div>
                                                    <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $submission->student->name }}</p>
                                                    <p class="text-sm text-slate-500 dark:text-slate-400">Nộp lúc: {{ $submission->submitted_at->format('H:i, d/m/Y') }}</p>
                                                </div>
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                                    <x-heroicon-o-user class="w-6 h-6 text-slate-400"/>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-800 dark:text-slate-100">Không rõ</p>
                                                    <p class="text-sm text-slate-500 dark:text-slate-400">Nộp lúc: {{ $submission->submitted_at->format('H:i, d/m/Y') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-3 bg-slate-100 dark:bg-slate-900/50 rounded-md text-sm">
                                            @php
                                                $media = $submission->getFirstMedia('submission_documents');
                                                $link = Str::of($submission->content)->match('/(https?:\/\/[^\s]+)/');
                                            @endphp
                                            @if($media)
                                                <p><strong>Bài nộp:</strong></p>
                                                <button wire:click="downloadSubmission('{{ $submission->id }}')" class="inline-flex items-center gap-2 text-blue-600 hover:underline dark:text-blue-400 font-medium">
                                                    <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                                                    {{ $media->file_name }} ({{ $media->human_readable_size }})
                                                </button>
                                            @elseif($link)
                                                <p><strong>Bài nộp:</strong></p>
                                                <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-blue-600 hover:underline dark:text-blue-400 font-medium">
                                                    <x-heroicon-o-link class="h-4 w-4" />
                                                    Mở liên kết
                                                </a>
                                            @endif
                                            @if($submission->content)
                                                <p class="mt-2 pt-2 border-t border-slate-200 dark:border-slate-700"><strong>Ghi chú:</strong><br>{{ nl2br(e(Str::of($submission->content)->before('Submitted via link:')->trim())) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 md:w-80">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-1 gap-4">
                                            <div>
                                                <label for="points-{{ $submission->id }}" class="text-sm font-medium">Điểm</label>
                                                <input type="number" id="points-{{ $submission->id }}"
                                                       wire:model="points.{{ $submission->id }}"
                                                       step="0.1" min="0" max="{{ $selectedCourseAssignment->assignment->max_points }}"
                                                       @if($isGradingExpired) disabled @endif
                                                       class="fi-input mt-1 block w-full" placeholder="VD: 8.5">
                                            </div>
                                            <div class="sm:col-span-2 md:col-span-1">
                                                <label for="feedback-{{ $submission->id }}" class="text-sm font-medium">Phản hồi</label>
                                                <textarea id="feedback-{{ $submission->id }}"
                                                          wire:model="feedback.{{ $submission->id }}"
                                                          @if($isGradingExpired) disabled @endif
                                                          class="fi-input mt-1 block w-full" rows="2" placeholder="Nhập phản hồi..."></textarea>
                                            </div>
                                            <div class="sm:col-span-3 md:col-span-1">
                                                <button type="button" wire:click="saveGrade('{{ $submission->id }}')"
                                                        wire:loading.attr="disabled" wire:target="saveGrade('{{ $submission->id }}')"
                                                        @if($isGradingExpired) disabled @endif
                                                        class="fi-btn fi-btn-color-primary w-full">
                                                    Lưu điểm
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="mx-auto w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                                    <x-heroicon-o-user-group class="w-8 h-8 text-slate-400 dark:text-slate-500" />
                                </div>
                                <p>Không có sinh viên nào phù hợp.</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($this->paginatedSubmissions->hasPages())
                        <div class="mt-4">
                            {{ $this->paginatedSubmissions->links() }}
                        </div>
                    @endif

                @elseif ($submissionView === 'not_submitted')
                    <div class="space-y-3">
                        @forelse ($this->filteredNotSubmittedStudents as $student)
                            <div wire:key="not-submitted-{{ $student->id }}" class="border border-slate-200 dark:border-slate-700 rounded-lg p-3 flex items-center">
                                <img class="h-10 w-10 rounded-full object-cover"
                                     src="{{ $student->getFirstMediaUrl('avatar') ?: 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                     alt="{{ $student->name }}">
                                <div class="ml-3">
                                    <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $student->name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $student->email }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="mx-auto w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                                    <x-heroicon-o-check-circle class="w-8 h-8 text-green-500" />
                                </div>
                                <p>Không có sinh viên nào phù hợp.</p>
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>

            <x-slot name="footer">
                <x-filament::button color="gray" @click="$wire.closeSubmissionsModal()">
                    Đóng
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
    @endif
    
    @if($showDocumentsModal && $selectedCourseAssignment)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/75" 
         wire:click.self="closeDocumentsModal"
    >
        <div class="relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-xl shadow-xl transform scale-100 transition-all duration-300 mx-4 max-h-[90vh] flex flex-col" wire:click.stop>
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-start flex-shrink-0">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Tài liệu bài tập</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ $selectedCourseAssignment->assignment->title }}</p>
                </div>
                <button type="button" wire:click="closeDocumentsModal" class="p-2 -m-2 text-slate-500 dark:text-slate-400 rounded-full transition-colors hover:text-red-500 dark:hover:text-red-400 bg-none border-none cursor-pointer">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>
            <div class="px-6 py-4 overflow-y-auto flex-grow">
                <div class="space-y-3">
                    @forelse ($assignmentDocuments as $document)
                        <div wire:key="doc-{{ $document->id }}" class="border border-slate-200 dark:border-slate-700 rounded-lg p-4 flex justify-between items-center">
                            <div class="flex items-center gap-3 min-w-0">
                                <x-heroicon-o-document class="h-6 w-6 text-slate-500 dark:text-slate-400 flex-shrink-0" />
                                <div class="truncate">
                                    <p class="font-medium text-slate-800 dark:text-slate-100 truncate" title="{{ $document->file_name }}">{{ $document->file_name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $document->human_readable_size }}</p>
                                </div>
                            </div>
                            <a
                                href="{{ $document->getUrl() }}"
                                download="{{ $document->file_name }}"
                                class="inline-flex items-center gap-2 text-blue-600 hover:underline dark:text-blue-400 font-medium flex-shrink-0 ml-4"
                            >
                                <x-heroicon-o-arrow-down-tray class="h-5 w-5" />
                                <span>Tải về</span>
                            </a>
                        </div>
                    @empty
                        <p class="text-center text-slate-500 dark:text-slate-400 py-8">Không có tài liệu nào được đính kèm.</p>
                    @endforelse
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 flex-shrink-0">
                <x-filament::button type="button" color="gray" wire:click="closeDocumentsModal">
                    Đóng
                </x-filament::button>
            </div>
        </div>
    </div>
    @endif

</x-filament-panels::page>
