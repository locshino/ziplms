<x-filament-panels::page>

    {{-- Bộ lọc --}}
    <div
        class="flex flex-col md:flex-row md:flex-wrap items-end gap-4 bg-white p-6 rounded-2xl shadow-lg border border-gray-200">

        {{-- Lọc theo khóa học --}}
        <div class="w-full md:w-1/4">
            <label for="selectedCourseId" class="block text-sm font-semibold text-gray-700 mb-1">
                <x-heroicon-o-academic-cap class="w-4 h-4 inline-block mr-1 text-blue-600" />
                Khóa học
            </label>
            <select wire:model.defer="selectedCourseId" id="selectedCourseId"
                class="filament-input block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Tất cả các khóa học</option>
                @foreach($courses as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Từ ngày --}}
        <div class="w-full md:w-1/4">
            <label for="startDate" class="block text-sm font-semibold text-gray-700 mb-1">
                <x-heroicon-o-calendar class="w-4 h-4 inline-block mr-1 text-blue-600" />
                Từ ngày
            </label>
            <input type="date" id="startDate" wire:model.defer="startDate"
                class="filament-input block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
        </div>

        {{-- Đến ngày --}}
        <div class="w-full md:w-1/4">
            <label for="endDate" class="block text-sm font-semibold text-gray-700 mb-1">
                <x-heroicon-o-calendar-days class="w-4 h-4 inline-block mr-1 text-blue-600" />
                Đến ngày
            </label>
            <input type="date" id="endDate" wire:model.defer="endDate"
                class="filament-input block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
        </div>

        {{-- Nút hành động --}}
        <div class="flex items-center gap-2 w-full md:w-auto md:ml-auto">
            <button wire:click="applyFilters"
                class="mt-6 md:mt-0 inline-flex items-center justify-center px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-full shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                <x-heroicon-o-funnel class="w-4 h-4 mr-1" /> Lọc
            </button>
        </div>
    </div>


    {{-- Tabs --}}
    <div x-data="{ activeTab: @entangle('activeTab').live }" class="mt-8">
        <div class="flex w-full max-w-xl mx-auto rounded-full bg-gray-100 p-1 shadow-inner">
            <button @click="activeTab = 'quizzes'"
                class="flex-1 py-2 text-center font-semibold transition-all duration-200 rounded-full flex items-center justify-center"
                :class="{ 'bg-white text-blue-600 shadow-md': activeTab === 'quizzes', 'text-gray-600': activeTab !== 'quizzes' }">
                <x-heroicon-o-question-mark-circle class="w-5 h-5 mr-1" />
                Quizzes

            </button>

            <button @click="activeTab = 'assignments'"
                class="flex-1 py-2 text-center font-semibold transition-all duration-200 rounded-full flex items-center justify-center"
                :class="{ 'bg-white text-blue-600 shadow-md': activeTab === 'assignments', 'text-gray-600': activeTab !== 'assignments' }">
                <x-heroicon-o-document-text class="w-5 h-5 mr-1" />
                Assignments

            </button>
        </div>

        {{-- Nội dung Quizzes --}}
        <div x-show="activeTab === 'quizzes'" x-cloak
            class="bg-white mt-6 p-6 rounded-2xl shadow-lg border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                <x-heroicon-o-chart-bar class="w-6 h-6 mr-2 text-blue-600" />
                Thống kê tổng quan cho Quiz
            </h2>
            {{ $this->table }}
        </div>

        {{-- Nội dung Assignments --}}
        <div x-show="activeTab === 'assignments'" x-cloak
            class="bg-white mt-6 p-6 rounded-2xl shadow-lg border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                <x-heroicon-o-clipboard-document-check class="w-6 h-6 mr-2 text-blue-600" />
                Thống kê tổng quan cho Assignment
            </h2>
            {{ $this->table }}
        </div>
    </div>

</x-filament-panels::page>