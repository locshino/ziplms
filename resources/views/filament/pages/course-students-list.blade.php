<div>
    @if ($students->isNotEmpty())
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach ($students as $student)
                <li class="py-3 px-4">
                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                        {{ $student->name }}
                    </p>
                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                        {{ $student->email }}
                    </p>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-center text-gray-500 dark:text-gray-400 py-4">Không có học viên nào trong khóa học này.</p>
    @endif
</div>