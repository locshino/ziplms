<div>
    @if($showAlert && count($inProgressQuizzes) > 0)
        <div class="fixed top-4 right-4 z-50 max-w-sm">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg shadow-lg p-4 relative">
                <!-- Close button -->
                <button wire:click="dismissAlert"
                    class="absolute top-2 right-2 text-yellow-400 hover:text-yellow-600 transition-colors"
                    aria-label="Đóng thông báo">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- Alert icon -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>

                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Bạn đang làm dở quiz
                        </h3>

                        <div class="mt-2 text-sm text-yellow-700">
                            @foreach($inProgressQuizzes as $quiz)
                                <div class="mb-2 last:mb-0">
                                    <p class="font-medium">{{ $quiz['quiz_title'] }}</p>
                                    <p class="text-xs text-yellow-600">
                                        Bắt đầu: {{ \Carbon\Carbon::parse($quiz['started_at'])->format('d/m/Y H:i') }}
                                    </p>
                                    <a href="{{ route('filament.app.pages.quiz-taking', ['quiz' => $quiz['quiz_id']]) }}"
                                        onclick="@this.dismissAlert()"
                                        class="mt-1 inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                        Tiếp tục làm bài
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>