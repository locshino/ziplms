<x-filament-panels::page>

    <div class="min-h-screen bg-slate-100 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 py-8">

            <div class="mb-8 bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 flex flex-col gap-6">
                <div class="flex items-center gap-4 flex-wrap">
                    {{-- Course Filter Dropdown --}}
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
                    {{-- Search Input --}}
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

                {{-- Filter Buttons --}}
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

                        <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t lg:border-t-0 lg:border-l border-slate-200 dark:border-slate-700 flex flex-col justify-between lg:w-80 lg:flex-shrink-0">
                            <div class="mb-4">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                    <x-heroicon-s-user-group class="w-4 h-4" />
                                    {{ $assignment->graded_submissions_count }} / {{ $assignment->submissions_count }} đã chấm
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row gap-2">
                                <button
                                    type="button"
                                    wire:click="openInstructionsModal('{{ $courseAssignment->id }}')"
                                    class="w-full lg:flex-1 font-semibold px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-800 dark:hover:text-slate-100 hover:-translate-y-0.5 hover:shadow-lg"
                                >
                                    <x-heroicon-o-document-text class="w-5 h-5" />
                                    <span>Hướng dẫn</span>
                                </button>
                                <button
                                    type="button"
                                    wire:click="openSubmissionsModal('{{ $courseAssignment->id }}')"
                                    class="w-full lg:flex-1 font-semibold px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 border border-transparent bg-blue-500 text-white hover:bg-blue-600 hover:-translate-y-0.5 hover:shadow-lg"
                                >
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    <span>Chấm bài</span>
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

    @if ($showSubmissionsModal && $selectedCourseAssignment)
        <x-filament::modal id="submissions-modal" width="5xl" :close-by-clicking-away="false" :close-on-escape="false" @close.stop="$wire.closeSubmissionsModal()">
            <x-slot name="heading">
                Chấm bài: {{ $selectedCourseAssignment->assignment->title }}
            </x-slot>
            <x-slot name="description">
                Điểm tối đa: {{ number_format($selectedCourseAssignment->assignment->max_points, 1) }}
            </x-slot>

            <div class="space-y-4">
                @forelse ($submissions as $submission)
                    <div wire:key="submission-{{ $submission->id }}" class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $submission->student->name ?? 'Không rõ' }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Nộp lúc: {{ $submission->submitted_at->format('H:i, d/m/Y') }}</p>

                                <div class="mt-3 p-3 bg-slate-100 dark:bg-slate-900/50 rounded-md text-sm">
                                    @php
                                        $media = $submission->getFirstMedia('submission_documents');
                                        $link = Str::of($submission->content)->match('/(https?:\/\/[^\s]+)/');
                                    @endphp

                                    @if($media)
                                        <p><strong>Bài nộp:</strong></p>
                                        <button wire:click="downloadSubmission('{{ $submission->id }}')" class="inline-flex items-center gap-2 text-blue-600 hover:underline dark:text-blue-400 font-medium">
                                            <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                                            {{ $media->name }} ({{ $media->human_readable_size }})
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
                                               class="fi-input mt-1 block w-full" placeholder="VD: 8.5">
                                    </div>
                                    <div class="sm:col-span-2 md:col-span-1">
                                        <label for="feedback-{{ $submission->id }}" class="text-sm font-medium">Phản hồi</label>
                                        <textarea id="feedback-{{ $submission->id }}"
                                                  wire:model="feedback.{{ $submission->id }}"
                                                  class="fi-input mt-1 block w-full" rows="2" placeholder="Nhập phản hồi..."></textarea>
                                    </div>
                                    <div class="sm:col-span-3 md:col-span-1">
                                        <button type="button" wire:click="saveGrade('{{ $submission->id }}')"
                                                wire:loading.attr="disabled" wire:target="saveGrade('{{ $submission->id }}')"
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
                        <p>Chưa có sinh viên nào nộp bài.</p>
                    </div>
                @endforelse
            </div>

            <x-slot name="footer">
                <x-filament::button color="gray" @click="$wire.closeSubmissionsModal()">
                    Đóng
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
    @endif

</x-filament-panels::page>
