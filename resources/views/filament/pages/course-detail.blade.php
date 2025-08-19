<x-filament-panels::page>
    @if ($this->course)
        <x-tabs-nav :navs="[['key' => 'quizzes', 'label' => 'Quizzes'], ['key' => 'assignments', 'label' => 'Assignments']]" :initial="'quizzes'">
            <!-- Tab Content -->
            <div>
                <!-- Quizzes Table -->
                <div x-show="activeTab === 'quizzes'" id="quizzes" role="tabpanel">
                    @if ($this->course->quizzes->count() > 0)
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Title</th>
                                        <th scope="col" class="px-6 py-3">Description</th>
                                        <th scope="col" class="px-6 py-3">Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($this->course->quizzes as $quiz)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $quiz->title }}
                                            </th>
                                            <td class="px-6 py-4">{{ $quiz->description }}</td>
                                            <td class="px-6 py-4">{{ $quiz->duration }} minutes</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No quizzes found for this course.</p>
                    @endif
                </div>

                <!-- Assignments Table -->
                <div x-show="activeTab === 'assignments'" id="assignments" role="tabpanel">
                    @if ($this->course->assignments->count() > 0)
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Title</th>
                                        <th scope="col" class="px-6 py-3">Description</th>
                                        <th scope="col" class="px-6 py-3">End Submission At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($this->course->assignments as $assignment)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $assignment->title }}
                                            </th>
                                            <td class="px-6 py-4">{{ $assignment->description }}</td>
                                            <td class="px-6 py-4">
                                                {{ $assignment->pivot->end_at?->format('Y-m-d') ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No assignments found for this course.</p>
                    @endif
                </div>
            </div>
        </x-tabs-nav>
    @else
        <div>Loading course data...</div>
    @endif
</x-filament-panels::page>
