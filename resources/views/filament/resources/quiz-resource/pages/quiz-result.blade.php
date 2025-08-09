<x-filament-panels::page>
    <div class="max-w-4xl mx-auto space-y-6">
        @if($this->attempt)
            <!-- Result Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-center">
                    <div class="mb-4">
                        @php
                            $scorePercentage = $this->getScorePercentage();
                            $gradeColor = $scorePercentage >= 80 ? 'text-green-600 dark:text-green-400' : 
                                         ($scorePercentage >= 60 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400');
                            $gradeText = $scorePercentage >= 90 ? 'Xuất sắc' : 
                                        ($scorePercentage >= 80 ? 'Giỏi' : 
                                        ($scorePercentage >= 70 ? 'Khá' : 
                                        ($scorePercentage >= 60 ? 'Trung bình' : 'Yếu')));
                        @endphp
                        
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full {{ $scorePercentage >= 80 ? 'bg-green-100 dark:bg-green-900' : ($scorePercentage >= 60 ? 'bg-yellow-100 dark:bg-yellow-900' : 'bg-red-100 dark:bg-red-900') }} mb-4">
                            @if($scorePercentage >= 80)
                                <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @elseif($scorePercentage >= 60)
                                <svg class="w-12 h-12 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <svg class="w-12 h-12 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $this->quiz->title }}</h1>
                        <p class="text-gray-600 dark:text-gray-300">{{ $this->quiz->course->title }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold {{ $gradeColor }}">
                                {{ round($scorePercentage, 1) }}%
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Điểm số</div>
                        </div>
                        
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold {{ $gradeColor }}">
                                {{ $gradeText }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Xếp loại</div>
                        </div>
                        
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ $this->calculateStatistics()['correct_answers'] }}/{{ $this->calculateStatistics()['total_questions'] }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Đúng/Tổng</div>
                        </div>
                        
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ $this->getTimeTaken() }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Thời gian</div>
                        </div>
                    </div>
                    
                    <div class="flex justify-center space-x-4">
                        @if($this->canRetake())
                            <x-filament::button 
                                wire:click="retakeQuiz"
                                icon="heroicon-o-arrow-path">
                                Làm lại
                            </x-filament::button>
                        @endif
                        
                        <x-filament::button 
                            color="gray"
                            wire:click="goBackToQuizList"
                            icon="heroicon-o-arrow-left">
                            Quay lại danh sách
                        </x-filament::button>
                        
                        <x-filament::button 
                            color="info"
                            wire:click="toggleDetails"
                            icon="{{ $this->showDetails ? 'heroicon-o-eye-slash' : 'heroicon-o-eye' }}">
                            {{ $this->showDetails ? 'Ẩn chi tiết' : 'Xem chi tiết' }}
                        </x-filament::button>
                    </div>
                </div>
            </div>

            @if($this->showDetails)
                <!-- Detailed Statistics -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Thống kê chi tiết</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white mb-3">Kết quả</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-300">Tổng câu hỏi:</dt>
                                    <dd class="font-medium">{{ $this->calculateStatistics()['total_questions'] }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-300">Trả lời đúng:</dt>
                                    <dd class="font-medium text-green-600 dark:text-green-400">{{ $this->calculateStatistics()['correct_answers'] }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-300">Trả lời sai:</dt>
                                    <dd class="font-medium text-red-600 dark:text-red-400">{{ $this->calculateStatistics()['wrong_answers'] }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-300">Độ chính xác:</dt>
                                    <dd class="font-medium">{{ $this->calculateStatistics()['accuracy'] }}%</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white mb-3">Điểm số</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-300">Tổng điểm:</dt>
                                    <dd class="font-medium">{{ $this->calculateStatistics()['total_points'] }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-300">Điểm đạt được:</dt>
                                    <dd class="font-medium text-blue-600 dark:text-blue-400">{{ $this->calculateStatistics()['earned_points'] }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-300">Phần trăm:</dt>
                                    <dd class="font-medium {{ $gradeColor }}">{{ $this->calculateStatistics()['score_percentage'] }}%</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white mb-3">Thời gian</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-300">Bắt đầu:</dt>
                                    <dd class="font-medium">{{ $this->attempt->started_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-300">Hoàn thành:</dt>
                                    <dd class="font-medium">{{ $this->attempt->completed_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-300">Thời gian làm:</dt>
                                    <dd class="font-medium">{{ $this->calculateStatistics()['time_taken'] }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600 dark:text-gray-300">Lần thử:</dt>
                                    <dd class="font-medium">{{ $this->attempt->attempt_number }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Question Review -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Xem lại câu hỏi</h2>
                    
                    <div class="space-y-6">
                        @foreach($this->results as $index => $result)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900 dark:text-white mb-2">
                                            Câu {{ $index + 1 }}: {{ $result['question']->title }}
                                        </h3>
                                        <div class="flex items-center space-x-4 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $result['question']->points }} điểm
                                            </span>
                                            @if($result['question']->is_multiple_response)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    Nhiều lựa chọn
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        @if($result['is_correct'])
                                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                Đúng
                                            </div>
                                        @else
                                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                Sai
                                            </div>
                                        @endif
                                        <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                            {{ $result['points_earned'] }}/{{ $result['question']->points }} điểm
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    @foreach($result['question']->answerChoices as $choice)
                                        @php
                                            $isSelected = in_array($choice->id, $result['selected_choice'] ?? []);
                                            $isCorrect = $choice->is_correct;
                                        @endphp
                                        
                                        <div class="flex items-center p-3 rounded-lg border
                                            {{ $isSelected && $isCorrect ? 'bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-600' : 
                                               ($isSelected && !$isCorrect ? 'bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-600' : 
                                               ($isCorrect ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-600' : 
                                               'border-gray-200 dark:border-gray-600')) }}">
                                            
                                            <div class="flex items-center mr-3">
                                                @if($isSelected)
                                                    @if($isCorrect)
                                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @endif
                                                @elseif($isCorrect)
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @else
                                                    <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded-full"></div>
                                                @endif
                                            </div>
                                            
                                            <span class="flex-1 text-gray-900 dark:text-white">{{ $choice->title }}</span>
                                            
                                            <div class="flex items-center space-x-2 text-xs">
                                                @if($isSelected)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full font-medium
                                                        {{ $isCorrect ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                        Bạn chọn
                                                    </span>
                                                @endif
                                                @if($isCorrect)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        Đáp án đúng
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Attempt History -->
                @if($this->getAttemptHistory()->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Lịch sử làm bài</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lần thử</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Điểm số</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Xếp loại</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thời gian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ngày làm</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($this->getAttemptHistory() as $historyAttempt)
                                        @php
                                            $historyScore = $historyAttempt->score;
                                            $historyGrade = $historyScore >= 90 ? 'Xuất sắc' : 
                                                           ($historyScore >= 80 ? 'Giỏi' : 
                                                           ($historyScore >= 70 ? 'Khá' : 
                                                           ($historyScore >= 60 ? 'Trung bình' : 'Yếu')));
                                            $historyColor = $historyScore >= 80 ? 'text-green-600 dark:text-green-400' : 
                                                           ($historyScore >= 60 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400');
                                        @endphp
                                        <tr class="{{ $historyAttempt->id === $this->attempt->id ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                #{{ $historyAttempt->attempt_number }}
                                                @if($historyAttempt->id === $this->attempt->id)
                                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        Hiện tại
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $historyColor }}">
                                                {{ round($historyScore, 1) }}%
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $historyColor }}">
                                                {{ $historyGrade }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                @if($historyAttempt->started_at && $historyAttempt->completed_at)
                                                    @php
                                                        $minutes = $historyAttempt->started_at->diffInMinutes($historyAttempt->completed_at);
                                                        $hours = floor($minutes / 60);
                                                        $remainingMinutes = $minutes % 60;
                                                    @endphp
                                                    {{ $hours > 0 ? sprintf('%d giờ %d phút', $hours, $remainingMinutes) : sprintf('%d phút', $remainingMinutes) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $historyAttempt->completed_at ? $historyAttempt->completed_at->format('d/m/Y H:i') : 'Chưa hoàn thành' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                <div class="text-gray-500 dark:text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Không tìm thấy kết quả</h3>
                    <p>Không thể tải kết quả quiz. Vui lòng thử lại sau.</p>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>