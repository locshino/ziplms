<x-filament-panels::page>
    <div class="min-h-screen bg-slate-100 dark:bg-slate-900">
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
                                placeholder="Tìm tiêu đề bài tập..."
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
                                ['key' => 'not_submitted', 'label' => 'Chưa nộp'],
                                ['key' => 'overdue', 'label' => 'Quá hạn'],
                                ['key' => 'submitted', 'label' => 'Đã nộp'],
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
                        $submissions = $assignment->submissions;
                        $submissionCount = $submissions->count();
                        $lastSubmission = $submissions->first();

                        $maxAttempts = $assignment->max_attempts;
                        $canSubmit = ($maxAttempts === null || $maxAttempts === 0) || ($submissionCount < $maxAttempts);

                        $isGraded = $lastSubmission && in_array($lastSubmission->status, [\App\Enums\Status\SubmissionStatus::GRADED, \App\Enums\Status\SubmissionStatus::RETURNED]);
                        $isSubmitted = $submissionCount > 0;
                        $isOverdue = !$isSubmitted && $courseAssignment->end_submission_at?->isPast();

                        $showSubmitButton = !$isGraded && !$isOverdue && $canSubmit;
                    @endphp
                    <div wire:key="{{ $courseAssignment->id }}" class="flex flex-col lg:flex-row lg:items-stretch bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="flex-grow p-6 lg:border-r lg:border-slate-200 lg:dark:border-slate-700">
                            @php
                                $courseId = $courseAssignment->course->id ?? null;
                                $colorClasses = $this->courseColors[$courseId] ?? ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-800 dark:text-gray-200'];
                            @endphp

                            <div class="inline-block {{ $colorClasses['bg'] }} {{ $colorClasses['text'] }} text-xs font-semibold px-3 py-1 rounded-full mb-3">
                                {{ $courseAssignment->course->title ?? 'Khóa học không xác định' }}
                            </div>

                            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4 leading-tight">{{ $assignment->title }}</h3>
                            <div class="flex flex-wrap gap-x-6 gap-y-4">
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                    <x-heroicon-o-calendar-days class="w-5 h-5 text-red-500" />
                                    <span class="font-medium">Hạn nộp: {{ $courseAssignment->end_submission_at?->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                    <x-heroicon-o-star class="w-5 h-5 text-yellow-500" />
                                    <span class="font-medium">{{ number_format($assignment->max_points, 1) }} điểm</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                    <x-heroicon-o-arrow-path class="w-5 h-5 text-blue-500" />
                                    <span class="font-medium">
                                        Đã nộp: {{ $submissionCount }}
                                        @if($maxAttempts > 0)
                                            / {{ $maxAttempts }} lần
                                        @else
                                            lần
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t lg:border-t-0 lg:border-l border-slate-200 dark:border-slate-700 flex flex-col justify-center gap-4 lg:w-80 lg:flex-shrink-0">
                            
                            <div class="flex justify-start w-full">
                                @if($isGraded)
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                        <x-heroicon-s-academic-cap class="w-4 h-4" /> Đã chấm
                                    </span>
                                @elseif($isSubmitted)
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        <x-heroicon-s-check-circle class="w-4 h-4" /> Đã nộp
                                    </span>
                                @elseif($isOverdue)
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                        <x-heroicon-s-exclamation-circle class="w-4 h-4" /> Quá hạn
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                        <x-heroicon-s-clock class="w-4 h-4" /> Chưa nộp
                                    </span>
                                @endif
                            </div>
                            <div class="flex flex-col gap-2 w-full">
                                <button
                                    type="button"
                                    wire:click="openInstructionsModal('{{ $courseAssignment->id }}')"
                                    class="w-full font-semibold px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-800 dark:hover:text-slate-100 hover:-translate-y-0.5 hover:shadow-lg"
                                >
                                    <x-heroicon-o-document-text class="w-5 h-5" />
                                    <span>Hướng dẫn</span>
                                </button>

                                @if($isSubmitted)
                                    <button
                                        type="button"
                                        wire:click="openSubmissionHistoryModal('{{ $courseAssignment->id }}')"
                                        class="w-full font-semibold px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-800 dark:hover:text-slate-100 hover:-translate-y-0.5 hover:shadow-lg"
                                    >
                                        <x-heroicon-o-clock class="w-5 h-5" />
                                        <span>Lịch sử</span>
                                    </button>
                                @endif

                                @if($isGraded)
                                    <button
                                        type="button"
                                        wire:click="openGradingResultModal('{{ $courseAssignment->id }}')"
                                        class="w-full font-semibold px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 border border-transparent bg-purple-500 text-white hover:bg-purple-600 hover:-translate-y-0.5 hover:shadow-lg"
                                    >
                                        <x-heroicon-o-eye class="w-5 h-5" />
                                        <span>Xem điểm</span>
                                    </button>
                                @elseif($showSubmitButton)
                                    <button
                                        type="button"
                                        wire:click="openSubmissionModal('{{ $courseAssignment->id }}')"
                                        class="w-full font-semibold px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 border border-transparent bg-blue-500 text-white hover:bg-blue-600 hover:-translate-y-0.5 hover:shadow-lg"
                                    >
                                        <x-heroicon-o-arrow-up-tray class="w-5 h-5" />
                                        <span>
                                            @if($submissionCount > 0)
                                                Nộp lại
                                            @else
                                                Nộp bài
                                            @endif
                                        </span>
                                    </button>
                                @elseif(!$canSubmit)
                                    <button disabled class="w-full font-semibold px-4 py-3 rounded-lg bg-slate-200 dark:bg-slate-600 text-slate-400 dark:text-slate-500 cursor-not-allowed pointer-events-none flex items-center justify-center gap-2">
                                        <x-heroicon-o-no-symbol class="w-5 h-5" />
                                        <span>Hết lượt nộp</span>
                                    </button>
                                @elseif($isOverdue)
                                     <button disabled class="w-full font-semibold px-4 py-3 rounded-lg bg-slate-200 dark:bg-slate-600 text-slate-400 dark:text-slate-500 cursor-not-allowed pointer-events-none flex items-center justify-center gap-2">
                                        <x-heroicon-o-x-circle class="w-5 h-5" />
                                        <span>Đã quá hạn</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
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
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Hướng dẫn bài tập</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ $selectedCourseAssignment->assignment->title }}</p>
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

    @if ($showSubmissionModal && $selectedCourseAssignment)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/75"
         x-data="{
            submissionType: @entangle('submissionType'),
            fileName: ''
         }"
         x-trap.noscroll="true"
         wire:click.self="closeSubmissionModal"
    >
        <div class="relative w-full max-w-5xl bg-white dark:bg-slate-800 rounded-xl shadow-xl transform scale-100 transition-all duration-300 mx-4 max-h-[90vh] flex flex-col" wire:click.stop>
            <form wire:submit.prevent="submitAssignment" class="flex flex-col h-full">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-start flex-shrink-0">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $selectedCourseAssignment->assignment->title }}</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Môn học: {{ $selectedCourseAssignment->course->title ?? 'Không xác định' }}</p>
                    </div>
                    <button type="button" wire:click="closeSubmissionModal" class="p-2 -m-2 text-slate-500 dark:text-slate-400 rounded-full transition-colors hover:text-red-500 dark:hover:text-red-400 bg-none border-none cursor-pointer">
                        <span class="sr-only">Đóng</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="px-6 py-4 overflow-y-auto flex-grow">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2">
                            <div class="mb-6">
                                <label class="block mb-3 text-sm font-medium text-slate-800 dark:text-slate-100">Phương thức nộp bài</label>
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-3 cursor-pointer text-sm text-slate-800 dark:text-slate-100">
                                        <input type="radio" x-model="submissionType" value="file" wire:model.live="submissionType" class="absolute opacity-0 w-0 h-0">
                                        <span class="w-5 h-5 border-2 border-slate-300 dark:border-slate-600 rounded-full flex items-center justify-center transition-all duration-200" :class="submissionType === 'file' ? 'border-blue-500' : ''">
                                            <span x-show="submissionType === 'file'" class="w-3 h-3 bg-blue-500 rounded-full transform scale-100 transition-transform duration-200"></span>
                                        </span>
                                        <span>Tải lên tệp</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer text-sm text-slate-800 dark:text-slate-100">
                                        <input type="radio" x-model="submissionType" value="link" wire:model.live="submissionType" class="absolute opacity-0 w-0 h-0">
                                        <span class="w-5 h-5 border-2 border-slate-300 dark:border-slate-600 rounded-full flex items-center justify-center transition-all duration-200" :class="submissionType === 'link' ? 'border-blue-500' : ''">
                                            <span x-show="submissionType === 'link'" class="w-3 h-3 bg-blue-500 rounded-full transform scale-100 transition-transform duration-200"></span>
                                        </span>
                                        <span>Nộp liên kết</span>
                                    </label>
                                </div>
                            </div>

                            <div x-show="submissionType === 'file'" x-transition class="mb-6">
                                <label for="file-upload" class="block mb-3 text-sm font-medium text-slate-800 dark:text-slate-100">Tệp bài nộp</label>
                                <div class="relative">
                                    <input type="file" wire:model="file" id="file-upload" x-on:change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''" class="hidden">
                                    <label for="file-upload" class="inline-block px-4 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg cursor-pointer font-medium text-slate-800 dark:text-slate-100 transition-colors hover:bg-slate-200 dark:hover:bg-slate-600">Choose File</label>
                                    <span x-text="fileName || 'No file chosen'" class="ml-4 text-sm text-slate-600 dark:text-slate-400"></span>
                                    @error('file') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div wire:loading wire:target="file" class="mt-2 text-sm text-slate-500">
                                    Đang tải lên...
                                </div>
                            </div>

                            <div x-show="submissionType === 'link'" x-transition class="mb-6">
                                <label for="link-url" class="block mb-3 text-sm font-medium text-slate-800 dark:text-slate-100">URL liên kết</label>
                                <input type="url" wire:model.defer="link_url" id="link-url" placeholder="https://..." class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                @error('link_url') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-6">
                                <label for="notes" class="block mb-3 text-sm font-medium text-slate-800 dark:text-slate-100">Ghi chú (tùy chọn)</label>
                                <textarea wire:model.defer="notes" id="notes" rows="4" class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-3 py-2 resize-vertical min-h-[6rem] focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10"></textarea>
                                @error('notes') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="md:col-span-1">
                            <div class="bg-slate-100 dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-700">
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 pb-4 border-b border-slate-200 dark:border-slate-700 mb-4">Thông tin</h3>
                                <dl class="flex flex-col gap-3">
                                    <div class="flex justify-between text-sm">
                                        <dt class="text-slate-600 dark:text-slate-400">Lần nộp</dt>
                                        <dd class="font-medium text-slate-800 dark:text-slate-100">
                                            {{ $selectedCourseAssignment->assignment->submissions->count() + 1 }}
                                            @if($selectedCourseAssignment->assignment->max_attempts > 0)
                                                / {{ $selectedCourseAssignment->assignment->max_attempts }}
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <dt class="text-slate-600 dark:text-slate-400">Hạn nộp bài</dt>
                                        <dd class="font-medium text-slate-800 dark:text-slate-100">{{ $selectedCourseAssignment->end_submission_at?->format('H:i, d/m/Y') }}</dd>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <dt class="text-slate-600 dark:text-slate-400">Điểm tối đa</dt>
                                        <dd class="font-medium text-slate-800 dark:text-slate-100">{{ number_format($selectedCourseAssignment->assignment->max_points, 1) }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 flex-shrink-0">
                    <x-filament::button color="gray" @click="$wire.closeSubmissionModal()">Quay lại</x-filament::button>
                    <x-filament::button type="submit" wire:loading.attr="disabled" wire:target="submitAssignment, file">
                        <span wire:loading.remove wire:target="submitAssignment, file">Nộp bài</span>
                        <span wire:loading wire:target="submitAssignment, file">Đang xử lý...</span>
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if($showGradingResultModal && $selectedCourseAssignment && $selectedSubmission)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/75" wire:click.self="closeGradingResultModal">
        <div class="relative w-full max-w-4xl bg-white dark:bg-slate-800 rounded-xl shadow-xl transform scale-100 transition-all duration-300 mx-4 max-h-[90vh] flex flex-col" wire:click.stop>
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-start flex-shrink-0">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Kết quả: {{ $selectedCourseAssignment->assignment->title }}</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Môn học: {{ $selectedCourseAssignment->course->title ?? 'Không xác định' }}</p>
                </div>
                <button wire:click="closeGradingResultModal" class="p-2 -m-2 text-slate-500 dark:text-slate-400 rounded-full transition-colors hover:text-red-500 dark:hover:text-red-400 bg-none border-none cursor-pointer">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>
            <div class="px-6 py-4 overflow-y-auto flex-grow space-y-6">
                <div class="bg-slate-100 dark:bg-slate-900/50 p-6 rounded-xl border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Điểm số</h3>
                    <div class="flex items-baseline justify-center text-center">
                        <span class="text-6xl font-bold text-blue-500">{{ number_format($selectedSubmission->points, 1) }}</span>
                        <span class="text-2xl font-medium text-slate-500 dark:text-slate-400">/ {{ number_format($selectedCourseAssignment->assignment->max_points, 1) }}</span>
                    </div>
                     <div class="text-center mt-3 text-sm text-slate-600 dark:text-slate-400">
                        <p>Người chấm: {{ $selectedSubmission->grader->name ?? 'N/A' }}</p>
                        <p>Ngày chấm: {{ $selectedSubmission->graded_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                    </div>
                </div>

                @if($selectedSubmission->feedback)
                <div class="bg-slate-100 dark:bg-slate-900/50 p-6 rounded-xl border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Phản hồi của giáo viên</h3>
                    <div class="prose max-w-none dark:prose-invert">
                        <p>{{ nl2br(e($selectedSubmission->feedback)) }}</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 flex-shrink-0">
                <x-filament::button color="gray" @click="$wire.closeGradingResultModal()">
                    Đóng
                </x-filament::button>
            </div>
        </div>
    </div>
    @endif

    @if($showSubmissionHistoryModal && $selectedCourseAssignment)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/75" wire:click.self="closeSubmissionHistoryModal">
        <div class="relative w-full max-w-4xl bg-white dark:bg-slate-800 rounded-xl shadow-xl transform scale-100 transition-all duration-300 mx-4 max-h-[90vh] flex flex-col" wire:click.stop>
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-start flex-shrink-0">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Lịch sử nộp bài</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ $selectedCourseAssignment->assignment->title }}</p>
                </div>
                <button wire:click="closeSubmissionHistoryModal" class="p-2 -m-2 text-slate-500 dark:text-slate-400 rounded-full transition-colors hover:text-red-500 dark:hover:text-red-400 bg-none border-none cursor-pointer">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>
            <div class="px-6 py-4 overflow-y-auto flex-grow">
                <div class="space-y-4">
                    @forelse ($submissionHistory as $historySubmission)
                        <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-slate-800 dark:text-slate-100">Lần nộp {{ $loop->remaining + 1 }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Nộp lúc: {{ $historySubmission->submitted_at->format('H:i, d/m/Y') }}</p>
                            </div>
                            <div>
                                @php
                                    $media = $historySubmission->getFirstMedia('submission_documents');
                                    $link = Str::of($historySubmission->content)->match('/(https?:\/\/[^\s]+)/');
                                @endphp
                                @if($media)
                                    <button wire:click="downloadSubmissionFile('{{ $historySubmission->id }}')" class="inline-flex items-center gap-2 text-blue-600 hover:underline dark:text-blue-400 font-medium">
                                        <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                                        Tải về
                                    </button>
                                @elseif($link)
                                    <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-blue-600 hover:underline dark:text-blue-400 font-medium">
                                        <x-heroicon-o-link class="h-4 w-4" />
                                        Mở liên kết
                                    </a>
                                @else
                                    <span class="text-sm text-slate-400">Không có tệp</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-500 dark:text-slate-400 py-8">Không có lịch sử nộp bài.</p>
                    @endforelse
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 flex-shrink-0">
                <x-filament::button color="gray" @click="$wire.closeSubmissionHistoryModal()">
                    Đóng
                </x-filament::button>
            </div>
        </div>
    </div>
    @endif

</x-filament-panels::page>
