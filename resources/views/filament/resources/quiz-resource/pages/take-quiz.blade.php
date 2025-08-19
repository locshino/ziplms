<x-filament-panels::page>
    <div class="max-w-4xl mx-auto">
        @if($this->quiz && $this->currentAttempt)
            <!-- Quiz Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->quiz->title }}</h1>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $this->quiz->course->title }}</p>
                    </div>
                    
                    @if($this->quiz->time_limit)
                        <div class="text-right">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Thời gian còn lại</div>
                            <div class="text-2xl font-bold" 
                                 x-data="{ timeRemaining: {{ $this->timeRemaining }} }"
                                 x-init="
                                     setInterval(() => {
                                         if (timeRemaining > 0) {
                                             timeRemaining--;
                                             const hours = Math.floor(timeRemaining / 3600);
                                             const minutes = Math.floor((timeRemaining % 3600) / 60);
                                             const seconds = timeRemaining % 60;
                                             $el.textContent = hours > 0 
                                                 ? `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
                                                 : `${minutes}:${seconds.toString().padStart(2, '0')}`;
                                             
                                             if (timeRemaining <= 300) {
                                                 $el.classList.add('text-red-600', 'dark:text-red-400');
                                             } else if (timeRemaining <= 600) {
                                                 $el.classList.add('text-yellow-600', 'dark:text-yellow-400');
                                             } else {
                                                 $el.classList.add('text-green-600', 'dark:text-green-400');
                                             }
                                         } else {
                                             $wire.submitQuiz();
                                         }
                                     }, 1000)
                                 "
                                 class="font-mono">
                                {{ $this->getTimeRemainingFormatted() }}
                            </div>
                        </div>
                    @endif
                </div>
                
                @if($this->quiz->description)
                    <div class="text-gray-600 dark:text-gray-300 mb-4">
                        {{ $this->quiz->description }}
                    </div>
                @endif
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ $this->questions->isNotEmpty() ? (($this->currentQuestionIndex + 1) / $this->questions->count()) * 100 : 0 }}%"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400 mt-2">
                    <span>Câu hỏi {{ $this->currentQuestionIndex + 1 }} / {{ $this->questions->count() }}</span>
                    <span>{{ $this->questions->isNotEmpty() ? round((($this->currentQuestionIndex + 1) / $this->questions->count()) * 100) : 0 }}% hoàn thành</span>
                </div>
            </div>

            @if($this->questions->isNotEmpty() && isset($this->questions[$this->currentQuestionIndex]))
                @php
                    $currentQuestion = $this->questions[$this->currentQuestionIndex];
                @endphp
                
                <!-- Question Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <div class="mb-6">
                        <div class="flex items-start justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex-1">
                                {{ $currentQuestion->title }}
                            </h2>
                            <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $currentQuestion->points }} điểm
                            </span>
                        </div>
                        
                        @if($currentQuestion->is_multiple_response)
                            <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                        Câu hỏi nhiều lựa chọn - Có thể chọn nhiều đáp án
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Answer Choices -->
                    <div class="space-y-3">
                        @foreach($currentQuestion->answerChoices as $choice)
                            <label class="flex items-start p-4 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors
                                {{ in_array($choice->id, $this->selectedAnswers) ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-600' : '' }}">
                                
                                @if($currentQuestion->is_multiple_response)
                                    <input type="checkbox" 
                                           wire:model.live="selectedAnswers" 
                                           value="{{ $choice->id }}"
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                @else
                                    <input type="radio" 
                                           wire:model.live="selectedAnswers" 
                                           value="{{ $choice->id }}"
                                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                @endif
                                
                                <span class="ml-3 text-gray-900 dark:text-white flex-1">
                                    {{ $choice->title }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            @if($this->currentQuestionIndex > 0)
                                <x-filament::button 
                                    color="gray" 
                                    wire:click="previousQuestion"
                                    icon="heroicon-o-chevron-left">
                                    Câu trước
                                </x-filament::button>
                            @endif
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <x-filament::button 
                                color="info" 
                                wire:click="saveAnswer"
                                icon="heroicon-o-bookmark">
                                Lưu đáp án
                            </x-filament::button>
                            
                            @if($this->currentQuestionIndex < $this->questions->count() - 1)
                                <x-filament::button 
                                    wire:click="nextQuestion"
                                    icon="heroicon-o-chevron-right"
                                    icon-position="after">
                                    Câu tiếp theo
                                </x-filament::button>
                            @else
                                <x-filament::button 
                                    color="success" 
                                    wire:click="submitQuiz"
                                    icon="heroicon-o-check-circle"
                                    wire:confirm="Bạn có chắc chắn muốn nộp bài? Sau khi nộp bài, bạn không thể thay đổi đáp án.">
                                    Nộp bài
                                </x-filament::button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Question Navigation -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mt-6">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Điều hướng câu hỏi</h3>
                    <div class="grid grid-cols-10 gap-2">
                        @foreach($this->questions as $index => $question)
                            <button wire:click="goToQuestion({{ $index }})"
                                    class="w-10 h-10 rounded-lg text-sm font-medium transition-colors
                                        {{ $index === $this->currentQuestionIndex 
                                            ? 'bg-blue-600 text-white' 
                                            : (isset($this->answeredQuestions[$question->id]) 
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' 
                                                : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600') }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-center space-x-6 mt-4 text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-600 rounded mr-2"></div>
                            <span>Hiện tại</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-100 dark:bg-green-900 rounded mr-2"></div>
                            <span>Đã trả lời</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-100 dark:bg-gray-700 rounded mr-2"></div>
                            <span>Chưa trả lời</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                    <div class="text-gray-500 dark:text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Không có câu hỏi</h3>
                        <p>Quiz này chưa có câu hỏi nào. Vui lòng liên hệ giảng viên.</p>
                    </div>
                </div>
            @endif
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                <div class="text-gray-500 dark:text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Không thể tải quiz</h3>
                    <p>Có lỗi xảy ra khi tải quiz. Vui lòng thử lại sau.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Auto-save notification -->
    <div x-data="{ show: false }" 
         x-on:answer-saved.window="show = true; setTimeout(() => show = false, 3000)"
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50"
         style="display: none;">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
            Đã lưu đáp án
        </div>
    </div>
</x-filament-panels::page>