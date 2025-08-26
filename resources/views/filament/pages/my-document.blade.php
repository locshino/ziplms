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

            <!-- Document List Section -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="bg-slate-50 dark:bg-slate-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Danh sách tài liệu
                    </h2>
                    <!-- Filter on Right -->
                    <div class="flex items-center gap-3">
                        <select wire:model.live="selectedCourseId"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                            <option value="">Tất cả khóa học</option>
                            @foreach($this->getEnrolledCourses() as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                        <button wire:click="clearFilters"
                            class="px-3 py-2 bg-gray-400 text-white rounded-lg text-sm cursor-pointer transition-colors hover:bg-gray-500">
                            Xóa bộ lọc
                        </button>
                    </div>
                </div>

                    <div class="p-0">
                        @forelse($this->getDocumentsByCourse() as $courseData)
                            @php
                                $course = $courseData['course'];
                                $documents = $courseData['documents'];
                            @endphp

                            <!-- Course Header -->
                            <div class="px-6 py-4 bg-blue-50 dark:bg-blue-900/20 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-blue-800 dark:text-blue-200 flex items-center gap-2">
                                    <x-heroicon-o-academic-cap class="w-5 h-5" />
                                    {{ $course->title }}
                                    <span class="text-sm font-normal text-blue-600 dark:text-blue-300">({{ $documents->count() }} tài liệu)</span>
                                </h3>
                            </div>

                            <!-- Documents in this course -->
                            @foreach($documents as $document)
                                @php
                                    $type = $this->getDocumentType($document);
                                    $typeLabel = $this->getDocumentTypeLabel($type);
                                @endphp

                                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 transition-colors hover:bg-slate-50 dark:hover:bg-slate-800 last:border-b-0">
                                    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <x-dynamic-component :component="$this->getFileIcon($document)"
                                                    class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $document->file_name }}</h4>
                                                <div class="inline-block bg-cyan-500 dark:bg-cyan-600 text-white text-xs font-medium px-2 py-1 rounded">
                                                    {{ $typeLabel }}
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400 mb-3">
                                                <div class="flex items-center gap-1">
                                                    <x-heroicon-s-calendar class="w-3 h-3" />
                                                    Tạo: {{ $document->created_at->format('d/m/Y H:i') }}
                                                </div>
                                                @if($document->updated_at != $document->created_at)
                                                    <div class="flex items-center gap-1">
                                                        <x-heroicon-s-arrow-path class="w-3 h-3" />
                                                        Cập nhật: {{ $document->updated_at->format('d/m/Y H:i') }}
                                                    </div>
                                                @endif
                                                <div class="flex items-center gap-1">
                                                    <x-heroicon-s-paper-clip class="w-3 h-3" />
                                                    {{ $this->getHumanReadableSize($document) }}
                                                </div>
                                            </div>

                                            <div class="flex gap-2">
                                                <a href="{{ $this->getDownloadUrl($document) }}" target="_blank"
                                                    class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs font-medium transition-colors hover:bg-blue-600 flex items-center gap-1">
                                                    <x-heroicon-s-arrow-down-tray class="w-3 h-3" /> Tải xuống
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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