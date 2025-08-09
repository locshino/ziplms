<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Quiz Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $this->record->title }}
                </h2>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ $this->record->course->title }}
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $this->getQuizStatistics()['total_attempts'] }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Tổng lượt thử</div>
                </div>
                
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ $this->getQuizStatistics()['average_score'] }}%
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Điểm trung bình</div>
                </div>
                
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $this->getQuizStatistics()['pass_rate'] }}%
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Tỷ lệ đậu</div>
                </div>
                
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                        {{ $this->getQuizStatistics()['average_time'] }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Thời gian TB</div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            {{ $this->table }}
        </div>
    </div>

    <!-- Statistics Modal -->
    <x-filament::modal id="quiz-statistics" width="2xl">
        <x-slot name="heading">
            Thống kê chi tiết - {{ $this->record->title }}
        </x-slot>

        <div class="space-y-6">
            <!-- Score Distribution Chart -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Phân bố điểm số</h3>
                <div class="grid grid-cols-5 gap-2">
                    @php
                        $attempts = $this->getTableQuery()->get();
                        $scoreRanges = [
                            'Xuất sắc (90-100%)' => $attempts->where('score', '>=', 90)->count(),
                            'Giỏi (80-89%)' => $attempts->where('score', '>=', 80)->where('score', '<', 90)->count(),
                            'Khá (70-79%)' => $attempts->where('score', '>=', 70)->where('score', '<', 80)->count(),
                            'TB (60-69%)' => $attempts->where('score', '>=', 60)->where('score', '<', 70)->count(),
                            'Yếu (<60%)' => $attempts->where('score', '<', 60)->count(),
                        ];
                        $maxCount = max(array_values($scoreRanges)) ?: 1;
                    @endphp
                    
                    @foreach($scoreRanges as $label => $count)
                        <div class="text-center">
                            <div class="bg-gray-200 dark:bg-gray-600 rounded-lg overflow-hidden" style="height: 120px;">
                                <div class="bg-gradient-to-t from-blue-500 to-blue-400 w-full rounded-lg flex items-end justify-center text-white text-sm font-medium" 
                                     style="height: {{ $count > 0 ? ($count / $maxCount) * 100 : 0 }}%;">
                                    @if($count > 0)
                                        {{ $count }}
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-300 mt-2">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Detailed Statistics -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Thống kê điểm số</h4>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600 dark:text-gray-300">Điểm cao nhất:</dt>
                            <dd class="font-medium text-green-600 dark:text-green-400">{{ $this->getQuizStatistics()['highest_score'] }}%</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600 dark:text-gray-300">Điểm thấp nhất:</dt>
                            <dd class="font-medium text-red-600 dark:text-red-400">{{ $this->getQuizStatistics()['lowest_score'] }}%</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600 dark:text-gray-300">Điểm trung bình:</dt>
                            <dd class="font-medium text-blue-600 dark:text-blue-400">{{ $this->getQuizStatistics()['average_score'] }}%</dd>
                        </div>
                    </dl>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Thống kê chung</h4>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600 dark:text-gray-300">Tổng lượt thử:</dt>
                            <dd class="font-medium">{{ $this->getQuizStatistics()['total_attempts'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600 dark:text-gray-300">Tỷ lệ đậu:</dt>
                            <dd class="font-medium text-green-600 dark:text-green-400">{{ $this->getQuizStatistics()['pass_rate'] }}%</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600 dark:text-gray-300">Thời gian TB:</dt>
                            <dd class="font-medium">{{ $this->getQuizStatistics()['average_time'] }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <x-slot name="footerActions">
            <x-filament::button color="gray" x-on:click="close">
                Đóng
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</x-filament-panels::page>