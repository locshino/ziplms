<div class="p-6 text-center">
    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 mb-4">
        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
    </div>
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Không thể làm bài</h3>
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">{{ $message }}</p>
    <x-filament::button color="gray" size="sm" x-on:click="close()">
        Đóng
    </x-filament::button>
</div>