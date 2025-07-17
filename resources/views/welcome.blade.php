<!DOCTYPE html>
{{-- Add data-theme attribute for DaisyUI theming --}}
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng đến với ZipLMS</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/guest.css'])

    {{-- Filament PWA --}}
    @filamentPWA
</head>

{{-- Use DaisyUI's bg-base-100 for automatic theme background --}}

<body class="bg-base-100">
    <div class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8">
        {{-- Main Header --}}
        <div class="text-center mb-10 md:mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-base-content">
                Chào mừng đến với <span class="text-primary">ZipLMS</span>
            </h1>
            <p class="mt-4 text-lg text-base-content/70 max-w-2xl mx-auto">
                Hệ thống Quản trị Học tập hiện đại. Vui lòng chọn vai trò của bạn để tiếp tục.
            </p>
        </div>

        {{-- Role Selection Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 w-full max-w-6xl">

            {{-- Admin Card --}}
            <x-role-card href="/admin" title="Quản trị viên" subtitle="System Administrator">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286Z" />
                    </svg>
                </x-slot>
            </x-role-card>

            {{-- Manager Card --}}
            <x-role-card href="/manager" title="Quản lý" subtitle="Course & Staff Manager">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 14.15v4.07a2.25 2.25 0 0 1-2.25 2.25H5.92a2.25 2.25 0 0 1-2.25-2.25v-4.07a2.25 2.25 0 0 1 .528-1.483c.097-.102.214-.19.34-.265l.652-.351v-3.69a2.25 2.25 0 0 1 2.25-2.25h5.362a2.25 2.25 0 0 1 2.25 2.25v3.69l.652.351c.126.075.243.163.34.265A2.25 2.25 0 0 1 20.25 14.15Z" />
                    </svg>
                </x-slot>
            </x-role-card>

            {{-- Teacher Card --}}
            <x-role-card href="/teacher" title="Giáo viên" subtitle="Instructor & Educator">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.26 10.147a60.437 60.437 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A3.375 3.375 0 0 0 2.25 12c0 1.02.388 1.954 1.024 2.688m15.482-5.376a3.375 3.375 0 0 1 1.024 2.688c0 1.02-.388 1.954-1.024 2.688" />
                    </svg>
                </x-slot>
            </x-role-card>

            {{-- Student Card --}}
            <x-role-card href="/student" title="Học sinh" subtitle="Learner & Participant">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </x-slot>
            </x-role-card>
        </div>
    </div>
</body>

</html>
