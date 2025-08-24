<x-filament-panels::page>
    <!-- Document List Page -->
    <div class="min-h-screen bg-slate-100 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <!-- Statistics Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Tổng số tài liệu</div>
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $this->getTotalDocumentsCount() }}</div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Khóa học đã đăng ký</div>
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $this->getEnrolledCourses()->count() }}</div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Bộ lọc tài liệu</h2>
                <div class="flex flex-col sm:flex-row gap-4 items-center">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Khóa học</label>
                        <select wire:model.live="selectedCourseId"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                            <option value="">Tất cả khóa học</option>
                            @foreach($this->getEnrolledCourses() as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button wire:click="clearFilters"
                        class="px-4 py-2 bg-gray-400 text-white rounded-lg text-sm cursor-pointer transition-colors hover:bg-gray-500">
                        Xóa bộ lọc
                    </button>
                </div>
            </div>

            <!-- Document List Section -->
            <div
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="bg-slate-50 dark:bg-slate-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        <x-heroicon-o-document-text class="w-6 h-6 inline mr-2" />
                        Danh sách tài liệu
                    </h2>
                </div>

                <div class="p-0">
                    @forelse($this->getDocuments() as $document)
                        @php
                            $type = $this->getDocumentType($document);
                            $typeLabel = $this->getDocumentTypeLabel($type);
                            $course = $document->course; // The course attached to the media object
                        @endphp

                        <div
                            class="px-6 py-6 border-b border-gray-200 dark:border-gray-700 transition-colors hover:bg-slate-50 dark:hover:bg-slate-800 last:border-b-0">
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
                                <div class="flex-1">
                                    @if($course)
                                        <div
                                            class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                                            {{ $course->title }}
                                        </div>
                                    @endif

                                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 leading-tight mb-3">
                                        <x-dynamic-component :component="$this->getFileIcon($document)"
                                            class="w-5 h-5 inline mr-2 text-gray-500 dark:text-gray-400" />
                                        {{ $document->file_name }}
                                    </h3>

                                    <div
                                        class="inline-block bg-cyan-500 dark:bg-cyan-600 text-white text-xs font-medium px-2 py-1 rounded mb-3">
                                        {{ $typeLabel }}
                                    </div>

                                    <div class="flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-2">
                                            <x-heroicon-s-calendar class="w-4 h-4" />
                                            Tạo: {{ $document->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        @if($document->updated_at != $document->created_at)
                                            <div class="flex items-center gap-2">
                                                <x-heroicon-s-arrow-path class="w-4 h-4" />
                                                Cập nhật: {{ $document->updated_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                        <div class="flex items-center gap-2">
                                            <x-heroicon-s-paper-clip class="w-4 h-4" />
                                            Kích thước: {{ $this->getHumanReadableSize($document) }}
                                        </div>
                                    </div>

                                    <div
                                        class="mt-4 p-4 bg-slate-50 dark:bg-slate-700 rounded-xl border border-gray-200 dark:border-gray-600">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Tệp đính
                                            kèm:</h4>
                                        <div
                                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 px-3 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                            <div class="flex flex-col gap-1">
                                                <span
                                                    class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $document->file_name }}</span>
                                                <span
                                                    class="text-xs text-gray-500 dark:text-gray-400">{{ $this->getHumanReadableSize($document) }}</span>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ $this->getDownloadUrl($document) }}" target="_blank"
                                                    class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs font-medium transition-colors hover:bg-blue-600 flex items-center gap-1">
                                                    <x-heroicon-s-arrow-down-tray class="w-4 h-4" /> Tải xuống
                                                </a>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">URL: {{ $this->getDownloadUrl($document) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                                    {{-- No need for multiple download buttons as each document is now a single media file
                                    --}}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-600 dark:text-gray-400">
                            <x-heroicon-o-document-text class="w-16 h-16 mx-auto mb-6 text-gray-400 dark:text-gray-500" />
                            <h3 class="text-xl font-semibold mb-2">Không có tài liệu nào</h3>
                            <p class="text-sm">Hiện tại bạn chưa có tài liệu nào trong các khóa học đã đăng ký.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page>