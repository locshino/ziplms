<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Danh sách Quiz</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Tham gia các bài quiz để kiểm tra kiến thức của bạn</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Filter by Course -->
                    <div class="min-w-48">
                        <select wire:model.live="selectedCourse" 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Tất cả khóa học</option>
                            @foreach($this->getAvailableCourses() as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Filter by Status -->
                    <div class="min-w-40">
                        <select wire:model.live="selectedStatus" 
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Tất cả trạng thái</option>
                            <option value="available">Có thể làm</option>
                            <option value="completed">Đã hoàn thành</option>
                            <option value="in_progress">Đang làm</option>
                            <option value="upcoming">Sắp diễn ra</option>
                            <option value="expired">Đã hết hạn</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($this->getFilteredQuizzes() as $quiz)
                @php
                    $userAttempt = $this->getUserAttempt($quiz);
                    $status = $this->getQuizStatus($quiz);
                    $canTake = $this->canTakeQuiz($quiz);
                    $bestScore = $this->getBestScore($quiz);
                    $attemptCount = $this->getAttemptCount($quiz);
                @endphp
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Quiz Header -->
                    <div class="p-6 pb-4">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                                {{ $quiz->title }}
                            </h3>
                            
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $status === 'available' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                   ($status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                   ($status === 'in_progress' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                   ($status === 'upcoming' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 
                                   'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'))) }}">
                                {{ $this->getStatusLabel($status) }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ $quiz->course->title }}</p>
                        
                        @if($quiz->description)
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4">
                                {{ $quiz->description }}
                            </p>
                        @endif
                    </div>
                    
                    <!-- Quiz Info -->
                    <div class="px-6 pb-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $quiz->questions_count ?? 0 }} câu hỏi
                            </div>
                            
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $quiz->time_limit ? $quiz->time_limit . ' phút' : 'Không giới hạn' }}
                            </div>
                            
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                {{ $quiz->max_points }} điểm
                            </div>
                            
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                {{ $attemptCount }} lần thử
                            </div>
                        </div>
                        
                        @if($bestScore !== null)
                            <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Điểm cao nhất:</span>
                                    <span class="text-sm font-bold
                                        {{ $bestScore >= 80 ? 'text-green-600 dark:text-green-400' : 
                                           ($bestScore >= 60 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                        {{ round($bestScore, 1) }}%
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Quiz Timing -->
                    @if($quiz->start_at || $quiz->end_at)
                        <div class="px-6 pb-4">
                            <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                @if($quiz->start_at)
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Bắt đầu: {{ $quiz->start_at->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                                @if($quiz->end_at)
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Kết thúc: {{ $quiz->end_at->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Actions -->
                    <div class="px-6 pb-6">
                        <div class="flex space-x-2">
                            @if($status === 'in_progress')
                                <x-filament::button 
                                    wire:click="continueQuiz('{{ $quiz->id }}')"
                                    class="flex-1"
                                    color="warning"
                                    icon="heroicon-o-play">
                                    Tiếp tục
                                </x-filament::button>
                            @elseif($canTake)
                                <x-filament::button 
                                    wire:click="takeQuiz('{{ $quiz->id }}')"
                                    class="flex-1"
                                    icon="heroicon-o-play">
                                    {{ $attemptCount > 0 ? 'Làm lại' : 'Bắt đầu' }}
                                </x-filament::button>
                            @endif
                            
                            @if($bestScore !== null)
                                <x-filament::button 
                                    wire:click="viewResults('{{ $quiz->id }}')"
                                    color="info"
                                    icon="heroicon-o-eye">
                                    Kết quả
                                </x-filament::button>
                            @endif
                            
                            @if(!$canTake && $status === 'upcoming')
                                <div class="flex-1 text-center py-2 px-4 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-lg text-sm">
                                    Chưa đến thời gian
                                </div>
                            @elseif(!$canTake && $status === 'expired')
                                <div class="flex-1 text-center py-2 px-4 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 rounded-lg text-sm">
                                    Đã hết hạn
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                        <div class="text-gray-500 dark:text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Không có quiz nào</h3>
                            <p>{{ $this->selectedCourse || $this->selectedStatus ? 'Không tìm thấy quiz phù hợp với bộ lọc.' : 'Hiện tại chưa có quiz nào được tạo.' }}</p>
                            @if($this->selectedCourse || $this->selectedStatus)
                                <x-filament::button 
                                    wire:click="clearFilters"
                                    color="gray"
                                    class="mt-4">
                                    Xóa bộ lọc
                                </x-filament::button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($this->getFilteredQuizzes()->hasPages())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                {{ $this->getFilteredQuizzes()->links() }}
            </div>
        @endif
    </div>

    <!-- Loading State -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-3">
            <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-900 dark:text-white">Đang tải...</span>
        </div>
    </div>
</x-filament-panels::page>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>