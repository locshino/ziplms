<x-filament-panels::page>

    {{-- Header --}}
    @php
    $avatarUrl = $record->getFirstMediaUrl('avatars');
    @endphp
    <div class="mt-6 flex items-center gap-4">
      <img src="{{ $avatarUrl ?: 'https://jbagy.me/wp-content/uploads/2025/03/hinh-anh-cute-avatar-vo-tri-2.jpg'  }}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;" alt="Avatar" />
         <div>
        <h2 class="text-lg font-semibold">{{ $record->name }}</h2>
    
    <p class="text-gray-600 text-xs mt-1">Email: {{ $record->email }}</p>
<div x-data="{ editMode: false }" class="relative">

    <!-- Icon bút chì -->
    <svg
        xmlns="http://www.w3.org/2000/svg"
        class="w-4 h-4 text-gray-500 cursor-pointer"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="1.5"
        x-on:click="editMode = true"
        title="Chỉnh sửa"
    >
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4 12.5-12.5z" />
    </svg>

    <!-- Form sửa (ẩn/hiện) -->
  <div
    x-show="editMode"
    x-transition
    class="fixed top-0 right-0 w-96 h-full bg-white shadow-lg p-6 overflow-auto z-50"
    style="display: none;"
>
    <button
        class="mb-4 text-gray-600 hover:text-gray-900"
        x-on:click="editMode = false"
        aria-label="Đóng form"
    >
        ✕ Đóng
    </button>

    <h2 class="text-xl font-semibold mb-4">Chỉnh sửa thông tin</h2>

    <form wire:submit.prevent="updateUser">
        <label class="block mb-2 text-gray-700">Tên:</label>
        <input
            type="text"
            wire:model.defer="name"
            class="w-full mb-4 p-2 border rounded"
            placeholder="Nhập tên..."
        />

        <label class="block mb-2 text-gray-700">Email:</label>
        <input
            type="email"
            wire:model.defer="email"
            class="w-full mb-4 p-2 border rounded"
            placeholder="Nhập email..."
        />

        <label for="file-upload" class="block mb-2 font-semibold text-gray-700">Tải lên file:</label>
        <label
            for="file-upload"
            class="flex flex-col items-center justify-center rounded-lg cursor-pointer p-6 text-red-700"
            style="border: 2px solid #dc2626; background-color: #fee2e2;"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-10 h-10 mb-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="#dc2626"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3 15a4 4 0 014-4h1.26a4 4 0 107.48 0H17a4 4 0 010 8H7a4 4 0 01-4-4z"
                />
            </svg>

            <span class="text-lg font-semibold select-none">Nhấp để chọn file hoặc kéo thả vào đây</span>

            <span
                class="mt-2 text-sm text-red-700 select-text"
                x-text="fileName ? fileName : 'Chưa có file nào được chọn'"
            ></span>
        </label>

        <input
            id="file-upload"
            type="file"
            wire:model="avatarFile"
            class="hidden"
        />

        <button
            type="submit"
            style="background-color: #2563eb !important; color: white !important; margin: 10px 0px 10px 0px;"
            class="px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-300 w-full"
        >
            Lưu
        </button>
    </form>
</div>

</div>





    </div>
    </div>
    @if($record->hasRole('student'))
        
   
<div x-data="{ showCourseForm: @entangle('showCourseForm') }">
    <div class="flex justify-start mb-4">
        <x-filament::button
            x-on:click="showCourseForm = !showCourseForm"
            class="bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-indigo-500 hover:to-purple-500 
                   text-white text-xs px-3 py-1 rounded-full shadow-md hover:shadow-lg transform hover:scale-105 
                   transition-all duration-300 ease-in-out">
         {{__('user_resource.header.add_course_button')}}
        </x-filament::button>
    </div>

  <div class="flex items-center gap-2 mt-2" x-show="showCourseForm" x-cloak>
    <select 
        wire:model="selectedCourseId" 
        class="w-64 px-3 py-2 text-sm border border-gray-300 rounded-full shadow-sm 
               focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400
               hover:border-blue-300 transition-all duration-300 ease-in-out bg-white">
        <option value="">  {{__('user_resource.header.select_course_placeholder')}}</option>
        @foreach($enrolledCoursesAll as $enrolledCourse)
            <option value="{{ $enrolledCourse->id }}">{{ $enrolledCourse->title }}</option>
        @endforeach
    </select>

    <x-filament::button 
        wire:click="addUserToCourse" 
        size="xs" 
        color="success"
        class="px-3 py-1 text-xs rounded-full shadow hover:shadow-md transform hover:scale-105 transition">
          {{__('user_resource.header.save_button')}}
    </x-filament::button>
</div>
</div>
 @endif



    {{-- Tabs (thu nhỏ) --}}
    <div class="mt-4 w-full flex justify-start text-sm">
        <div class="w-fit">
            <x-filament::tabs class="text-sm">
               <x-filament::tabs.item 
    :active="$activeTab === 'course_number'"
    wire:click="$set('activeTab', 'course_number')" 
    icon="heroicon-o-book-open"
    class="px-2 py-1 text-sm gap-1"
>
    {{ __('user_resource.tabs.course_number') }}
<span class="ml-1 inline-block rounded-sm bg-orange-200 text-orange-600 text-xs font-semibold px-2 py-0.5 leading-none">
    {{ $courseCount }}
</span>
</x-filament::tabs.item>

                <x-filament::tabs.item :active="$activeTab === 'badge'" wire:click="$set('activeTab', 'badge')"
                    icon="heroicon-o-star" class="px-2 py-1 text-sm gap-1">
                      {{__('user_resource.tabs.badge')}}
                </x-filament::tabs.item>
            </x-filament::tabs>
        </div>
    </div>


    {{-- Tab: Course List --}}
    <div class="mt-6" x-show="$wire.activeTab === 'course_number'" x-cloak>
        <div class="overflow-y-auto pr-2" style="max-height: 200px;">
            <div class="space-y-4">
                
                @forelse ($enrolledCourses as $course)
                    <div
                        class="bg-white rounded-lg shadow p-4 flex items-center justify-between border border-gray-200 mb-3">
                        <div class="flex items-center gap-4">
                            @if (!empty($course['image']))
                                <img src="{{ $course['image'] }}" alt="Course image"
                                    class="w-12 h-12 rounded object-cover bg-gray-100">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0-6L3 9m9 5l9-5" />
                                    </svg>
                                </div>
                            @endif
<style>
    .dark .text-yellow-override {
    color: #facc15 !important; 

}
.dark .progress-text {
    color: #000000; 
}
</style>
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900 text-yellow-override transition-colors duration-300">{{ $course['title'] ?? 'Không có tiêu đề' }}</h3>
                                <p class="text-sm text-gray-600">{{ $course['description'] ?? 'Không có mô tả' }}</p>
                            </div>
                        </div>
                        <div class="relative w-10 h-10">
  @php
    $done = $course->assignments()
        ->whereHas('submissions', function ($query) use ($record) {
            $query->where('student_id', $record->id);
        })
        ->count() ?? 1 ;

    $total = $course->assignments()->count();

    $progress = $total > 0 ? round(($done / $total) * 100) : 0;

    $radius = 18;
    $circumference = 2 * pi() * $radius;
    $offset = $circumference - ($progress / 100) * $circumference;
@endphp


    <svg class="w-10 h-10 transform -rotate-90" viewBox="0 0 40 40">
        <circle cx="20" cy="20" r="{{ $radius }}" stroke="#e5e7eb" stroke-width="4" fill="none" />
        <circle cx="20" cy="20" r="{{ $radius }}" stroke="#3b82f6" stroke-width="4" fill="none"
            stroke-dasharray="{{ $circumference }}"
            stroke-dashoffset="{{ $offset }}" stroke-linecap="round" />
    </svg>

    <div class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-blue-600 progress-text">
        {{ $progress }}%
    </div>
</div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-gray-600 italic space-y-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-blue-400 animate-bounce" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M12 20h.01M6.5 9a6.5 6.5 0 1111 5.45" />
                        </svg>
                        <p class="text-2xl font-semibold text-blue-500">  {{__('user_resource.course_list.not_enrolled')}}</p>
                        <p class="max-w-xs text-center text-gray-400"> {{__('user_resource.course_list.not_enrolled_detail')}}</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>


    {{-- Tab: Badge --}}
    

    <div class="mt-6" x-show="$wire.activeTab === 'badge'" x-cloak>
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-300">
            @if ($badges->isEmpty())
                <div class="flex flex-col items-center justify-center text-gray-400 space-y-3 py-12">
                    <p class="text-lg italic font-semibold text-gray-600">  {{__('user_resource.badge_list.no_badges')}}</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($badges as $badge)
                        <div
                            class="relative flex flex-col items-center bg-gradient-to-tr from-indigo-500 via-purple-600 to-pink-500 rounded-3xl p-6 shadow-2xl transform transition duration-300 hover:scale-105 hover:shadow-3xl cursor-pointer group">

                            <div
                                class="w-20 h-20 rounded-l bg-white flex items-center justify-center mb-4 shadow-lg group-hover:animate-pulse overflow-hidden">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0BcDX-CHxhJiChxRwBTruhH73sR-WQlN2WWd9fjKoleHrGimyrbm-nNcRx3fEXbbU0HU&usqp=CAU" alt="{{ $badge->name }}"
                                    class="w-full h-full object-cover rounded-xl" />
                            </div>

                            <h4 class=" font-extrabold text-xl text-center text-gray-600">{{ $badge->title }}</h4>
                            <p class="text-indigo-200 mt-2 text-center text-sm text-gray-600">{{ $badge->description }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</x-filament-panels::page>