<x-filament-panels::page>

    @if ($this->course)
        @if ($this->course->description)
            <div class="prose dark:prose-invert mt-1">
                {!! $this->course->description !!}
            </div>
        @endif
        <x-tabs-nav :navs="[['key' => 'quizzes', 'label' => 'Quizzes'], ['key' => 'assignments', 'label' => 'Assignments'], ['key' => 'document', 'label' => 'Document']]" :initial="'quizzes'">

            <div>
                <!-- Quizzes -->
                <div x-show="activeTab === 'quizzes'" id="quizzes" role="tabpanel"
                    class="grid md:grid-cols-3 sm:grid-cols-2 gap-6 mt-6">
                    @foreach ($this->ongoingQuizzes as $i => $quiz)
                        @php
                            $colors = [
                                'from-pink-500 to-rose-500',
                                'from-blue-500 to-indigo-500',
                                'from-green-500 to-emerald-500',
                                'from-purple-500 to-fuchsia-500',
                                'from-orange-500 to-amber-500',
                            ];
                            $bg = $colors[$i % count($colors)];
                        @endphp

                        <div
                            class="group rounded-2xl shadow-xl overflow-hidden bg-white dark:bg-gray-800 transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl">
                            <!-- Nửa trên -->
                            <div class="h-32 flex items-center justify-center bg-gradient-to-r {{ $bg }} relative">
                                <x-heroicon-o-academic-cap
                                    class="w-12 h-12 text-white opacity-90 transform transition duration-300 group-hover:scale-125 group-hover:rotate-12" />
                            </div>

                            <!-- Nửa dưới -->
                            <div class="p-5">
                                <h3
                                    class="text-xl font-bold text-gray-900 dark:text-white mb-1 group-hover:text-indigo-500 transition">
                                    {{ $quiz->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">
                                    {{ $quiz->description }}
                                </p>
                                <span class="inline-flex items-center text-xs font-medium text-gray-500 dark:text-gray-400">
                                    <x-heroicon-s-clock class="w-4 h-4 mr-1 text-gray-400" />
                                    {{ $quiz->time_limit_minutes }} phút
                                </span>

                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Assignments -->
                <div x-show="activeTab === 'assignments'" id="assignments" role="tabpanel"
                    class="grid md:grid-cols-3 sm:grid-cols-2 gap-6 mt-6">
                    @foreach ($this->ongoingQuizzes as $assignment)
                        @php
                            $now = now();
                            $endAt = \Carbon\Carbon::parse($assignment->pivot->end_at);
                            $diff = $now->diff($endAt); // trả về DateInterval
                            $daysLeft = $diff->days;    // số ngày nguyên
                            $hoursLeft = $diff->h;      // số giờ còn lại

                        @endphp
                        <div
                            class="group rounded-2xl shadow-xl overflow-hidden bg-white dark:bg-gray-800 transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl">
                            <!-- Nửa trên: ảnh -->
                            <div class="relative h-32 w-full overflow-hidden">
                                <img src="{{ $assignment->thumbnail_url ?? 'https://picsum.photos/400/200?random=' . $assignment->id }}"
                                    class="w-full h-full object-cover transform transition duration-300 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition"></div>

                            </div>

                            <!-- Nửa dưới -->
                            <div class="p-5">
                                <h3
                                    class="text-xl font-bold text-gray-900 dark:text-white mb-1 group-hover:text-rose-500 transition">
                                    {{ $assignment->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                    {{ $assignment->description }}
                                </p>
                                @if ($now->lt($endAt))
                                    <span class="inline-flex items-center space-x-2 px-4 py-1 text-sm font-semibold text-white rounded-full shadow-lg
                                                bg-gradient-to-r from-pink-500 via-red-500 to-orange-400 animate-pulse transform transition-all
                                                hover:scale-105 hover:shadow-2xl">
                                        <x-heroicon-o-clock class="w-5 h-5" />
                                        <span>{{ $daysLeft }} ngày {{ $hoursLeft }} giờ còn lại</span>
                                    </span>

                                @endif
                            </div>
                        </div>

                    @endforeach
                </div>
                <!-- Documents -->
                <div x-show="activeTab === 'document'" id="document" role="tabpanel"
                    class="grid md:grid-cols-3 sm:grid-cols-2 gap-6 mt-6">
                    @forelse ($this->documents as $doc)
                        <div
                            class="group rounded-2xl shadow-xl overflow-hidden bg-white dark:bg-gray-800 transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl">
                            <!-- Nửa trên: icon/document -->
                            <div
                                class="h-32 flex items-center justify-center bg-gradient-to-r from-sky-500 to-cyan-500 relative">
                                <x-heroicon-o-document-text
                                    class="w-12 h-12 text-white opacity-90 transform transition duration-300 group-hover:scale-125 group-hover:rotate-6" />

                            </div>

                            <!-- Nửa dưới -->
                            <div class="p-5 flex flex-col justify-between h-40">
                                <div>
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-white mb-1 group-hover:text-sky-500 transition">
                                        {{ $doc->name }}
                                    </h3>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ $doc->getUrl() }}" download
                                        class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-lg text-white bg-sky-600 hover:bg-sky-700 transition">
                                        <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
                                        <span>Tải về</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full flex flex-col items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-12 text-center">
                            <div
                                class="flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-800 mb-6">
                                <svg class="w-10 h-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7h4l2 3h12v11H3V7z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                Chưa có tài liệu nào
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Giáo viên chưa đăng tài liệu cho lớp học này.
                            </p>
                        </div>

                    @endforelse
                </div>
            </div>
        </x-tabs-nav>
    @else
        <div>Loading course data...</div>
    @endif

</x-filament-panels::page>