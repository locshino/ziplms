<x-filament-panels::page>

    <div class="min-h-screen bg-slate-100 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 py-8">

            <!-- Filter Bar -->
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
                <div class="flex gap-2 bg-slate-100 dark:bg-slate-900 p-2 rounded-lg">
                     <button wire:click="setFilter('all')" class="flex-grow text-center px-3 py-2 rounded-md text-sm font-medium cursor-pointer border-none transition-all duration-200 {{ $filter === 'all' ? 'bg-blue-500 text-white shadow-lg' : 'bg-transparent text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">Tất cả</button>
                     <button wire:click="setFilter('not_submitted')" class="flex-grow text-center px-3 py-2 rounded-md text-sm font-medium cursor-pointer border-none transition-all duration-200 {{ $filter === 'not_submitted' ? 'bg-blue-500 text-white shadow-lg' : 'bg-transparent text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">Chưa nộp</button>
                     <button wire:click="setFilter('overdue')" class="flex-grow text-center px-3 py-2 rounded-md text-sm font-medium cursor-pointer border-none transition-all duration-200 {{ $filter === 'overdue' ? 'bg-blue-500 text-white shadow-lg' : 'bg-transparent text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">Quá hạn</button>
                     <button wire:click="setFilter('submitted')" class="flex-grow text-center px-3 py-2 rounded-md text-sm font-medium cursor-pointer border-none transition-all duration-200 {{ $filter === 'submitted' ? 'bg-blue-500 text-white shadow-lg' : 'bg-transparent text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">Đã nộp</button>
                </div>
            </div>



    <div class="bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-8">

            <!-- Assignment List -->
            <div class="grid grid-cols-1 gap-6">
                @forelse ($this->assignments as $assignment)
                    @php
                        $submission = $assignment->submissions->where('student_id', auth()->id())->first();
                        $isSubmitted = (bool)$submission;
                        $isOverdue = !$isSubmitted && $assignment->due_at->isPast();
                    @endphp
                    <div wire:key="{{ $assignment->id }}" class="flex flex-col lg:flex-row lg:items-stretch bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <!-- Main Information Section -->
                        <div class="flex-grow p-6 lg:border-r lg:border-slate-200 lg:dark:border-slate-700">
                            <div class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full mb-3">{{ $assignment->course->title ?? 'Khóa học không xác định' }}</div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4 leading-tight">{{ $assignment->title }}</h3>
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                    <x-heroicon-o-calendar-days class="w-5 h-5 text-red-500" />
                                    <span class="font-medium">Hạn nộp: {{ $assignment->due_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                    <x-heroicon-o-star class="w-5 h-5 text-yellow-500" />
                                    <span class="font-medium">{{ number_format($assignment->max_points, 1) }} điểm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Meta & Actions Section -->
                        <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t lg:border-t-0 lg:border-l border-slate-200 dark:border-slate-700 flex flex-col justify-between lg:w-80 lg:flex-shrink-0">
                            <div class="mb-4">
                                @if($isSubmitted)
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
                            <div class="flex flex-col lg:flex-row gap-2">
                                <button
                                    type="button"
                                    wire:click="openInstructionsModal('{{ $assignment->id }}')"
                                    class="w-full lg:flex-1 font-semibold px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-800 dark:hover:text-slate-100 hover:-translate-y-0.5 hover:shadow-lg"
                                >
                                    <x-heroicon-o-document-text class="w-5 h-5" />
                                    <span>Hướng dẫn</span>
                                </button>

                                @if($isSubmitted)
                                    <button disabled class="w-full lg:flex-1 font-semibold px-4 py-3 rounded-lg bg-slate-200 dark:bg-slate-600 text-slate-400 dark:text-slate-500 cursor-not-allowed pointer-events-none flex items-center justify-center gap-2">
                                        <x-heroicon-o-check class="w-5 h-5" />
                                        <span>Đã nộp bài</span>
                                    </button>
                                @elseif($isOverdue)
                                     <button disabled class="w-full lg:flex-1 font-semibold px-4 py-3 rounded-lg bg-slate-200 dark:bg-slate-600 text-slate-400 dark:text-slate-500 cursor-not-allowed pointer-events-none flex items-center justify-center gap-2">
                                        <x-heroicon-o-x-circle class="w-5 h-5" />
                                        <span>Đã quá hạn</span>
                                    </button>
                                @else
                                    <button
                                        type="button"
                                        wire:click="openSubmissionModal('{{ $assignment->id }}')"
                                        class="w-full lg:flex-1 font-semibold px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 border border-transparent bg-blue-500 text-white hover:bg-blue-600 hover:-translate-y-0.5 hover:shadow-lg"
                                    >
                                        <x-heroicon-o-arrow-up-tray class="w-5 h-5" />
                                        <span>Nộp bài</span>
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

            @if ($this->assignments->hasPages())
                <div class="mt-8">
                    {{ $this->assignments->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Instructions Modal --}
    @if($showInstructionsModal && $selectedAssignment)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/75" wire:click="closeInstructionsModal">
            <div class="relative w-full max-w-4xl bg-white dark:bg-slate-800 rounded-xl shadow-xl transform scale-100 transition-all duration-300 mx-4 max-h-[90vh] flex flex-col" wire:click.stop>
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-start flex-shrink-0">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Hướng dẫn bài tập</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ $selectedAssignment->title }}</p>
                    </div>
                    <button wire:click="closeInstructionsModal" class="p-2 -m-2 text-slate-500 dark:text-slate-400 rounded-full transition-colors hover:text-red-500 dark:hover:text-red-400 bg-none border-none cursor-pointer">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>
                <div class="px-6 py-4 overflow-y-auto flex-grow">
                    <div class="prose max-w-none dark:prose-invert">
                        {!! $selectedAssignment->instructions !!}
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 flex-shrink-0">
                    <button wire:click="closeInstructionsModal" class="font-semibold px-5 py-2.5 rounded-lg transition-all duration-200 inline-flex items-center justify-center gap-2 border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- SUBMISSION MODAL --}}
    @if ($showSubmissionModal && $selectedAssignment)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/75" 
             x-data="{ 
                submissionType: @entangle('submissionType'),
                fileName: ''
             }"
             x-trap.noscroll="true"
             @file-input.window="fileName = $event.detail.name"
             wire:click="closeSubmissionModal"
        >
            <div @click.away="$wire.closeSubmissionModal()" class="relative w-full max-w-5xl bg-white dark:bg-slate-800 rounded-xl shadow-xl transform scale-100 transition-all duration-300 mx-4 max-h-[90vh] flex flex-col" wire:click.stop>
                <form wire:submit.prevent="submitAssignment" class="flex flex-col h-full">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-start flex-shrink-0">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $selectedAssignment->title }}</h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Môn học: {{ $selectedAssignment->course->title ?? 'Không xác định' }}</p>
                        </div>
                        <button type="button" wire:click="closeSubmissionModal" class="p-2 -m-2 text-slate-500 dark:text-slate-400 rounded-full transition-colors hover:text-red-500 dark:hover:text-red-400 bg-none border-none cursor-pointer">
                            <span class="sr-only">Đóng</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-4 overflow-y-auto flex-grow">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Left Column: Form -->
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

                                <div x-show="submissionType === 'file'" class="mb-6">
                                    <label for="file-upload" class="block mb-3 text-sm font-medium text-slate-800 dark:text-slate-100">Tệp bài nộp</label>
                                    <div class="relative">
                                        <input type="file" wire:model="file" id="file-upload" x-on:change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''" class="hidden">
                                        <label for="file-upload" class="inline-block px-4 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg cursor-pointer font-medium text-slate-800 dark:text-slate-100 transition-colors hover:bg-slate-200 dark:hover:bg-slate-600">Choose File</label>
                                        <span x-text="fileName || 'No file chosen'" class="ml-4 text-sm text-slate-600 dark:text-slate-400"></span>
                                        @error('file') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div x-show="submissionType === 'link'" class="mb-6">
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

                            <!-- Right Column: Info -->
                            <div class="md:col-span-1">
                                <div class="bg-slate-100 dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-700">
                                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 pb-4 border-b border-slate-200 dark:border-slate-700 mb-4">Thông tin</h3>
                                    <dl class="flex flex-col gap-3">
                                        <div class="flex justify-between text-sm">
                                            <dt class="text-slate-600 dark:text-slate-400">Hạn nộp bài</dt>
                                            <dd class="font-medium text-slate-800 dark:text-slate-100">{{ $selectedAssignment->due_at->format('H:i, d/m/Y') }}</dd>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <dt class="text-slate-600 dark:text-slate-400">Điểm tối đa</dt>
                                            <dd class="font-medium text-slate-800 dark:text-slate-100">{{ number_format($selectedAssignment->max_points, 1) }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 flex-shrink-0">
                        <button type="button" wire:click="closeSubmissionModal" class="font-semibold px-5 py-2.5 rounded-lg transition-all duration-200 inline-flex items-center justify-center gap-2 border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600">Quay lại</button>
                        <button type="submit" class="font-semibold px-5 py-2.5 rounded-lg transition-all duration-200 inline-flex items-center justify-center gap-2 border border-transparent bg-green-500 text-white hover:bg-green-600" wire:loading.attr="disabled" wire:target="submitAssignment, file">
                            <span wire:loading.remove wire:target="submitAssignment, file">Nộp bài</span>
                            <span wire:loading wire:target="submitAssignment, file">Đang xử lý...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</x-filament-panels::page>
