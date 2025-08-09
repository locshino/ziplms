<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $quiz->title }} - Quiz Taking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .quiz-timer {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ef4444;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Quiz Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $quiz->title }}</h1>
                @if($quiz->description)
                    <p class="text-gray-600 mb-4">{{ $quiz->description }}</p>
                @endif
                <div class="flex items-center space-x-6 text-sm text-gray-500">
                    <span>Tổng số câu: {{ $questions->count() }}</span>
                    @if($quiz->time_limit)
                        <span>Thời gian: {{ $quiz->time_limit }} phút</span>
                    @endif
                    <span>Điểm tối đa: {{ $quiz->total_marks }}</span>
                </div>
            </div>

            <!-- Timer -->
            @if($quiz->time_limit && $attempt)
                <div id="timer" class="quiz-timer">
                    <span id="time-remaining">{{ $quiz->time_limit }}:00</span>
                </div>
            @endif

            <!-- Quiz Form -->
            <form id="quiz-form" method="POST" action="{{ route('quiz.submit') }}">
                @csrf
                <input type="hidden" name="quiz" value="{{ $quiz->id }}">
                @if($attempt)
                    <input type="hidden" name="attempt" value="{{ $attempt->id }}">
                @endif

                <!-- Questions -->
                @foreach($questions as $index => $question)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                Câu {{ $index + 1 }}: {{ $question->title }}
                                @if($question->marks)
                                    <span class="text-sm text-gray-500">({{ $question->marks }} điểm)</span>
                                @endif
                            </h3>
                            @if($question->question_image)
                                <img src="{{ asset('storage/' . $question->question_image) }}" 
                                     alt="Question image" 
                                     class="max-w-full h-auto rounded-lg mb-4">
                            @endif
                        </div>

                        <!-- Answer Choices -->
                        <div class="space-y-3">
                            @foreach($question->answerChoices as $choice)
                                <label class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" 
                                           name="answers[{{ $question->id }}]" 
                                           value="{{ $choice->id }}"
                                           class="mt-1 text-blue-600 focus:ring-blue-500"
                                           {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] == $choice->id ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <span class="text-gray-900">{{ $choice->title }}</span>
                                        @if($choice->choice_image)
                                            <img src="{{ asset('storage/' . $choice->choice_image) }}" 
                                                 alt="Choice image" 
                                                 class="max-w-xs h-auto rounded mt-2">
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Submit Button -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('filament.app.pages.my-quiz') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            Quay lại
                        </a>
                        <button type="submit" 
                                class="px-8 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-semibold"
                                onclick="return confirm('Bạn có chắc chắn muốn nộp bài? Bạn sẽ không thể thay đổi câu trả lời sau khi nộp.')">
                            Nộp bài
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Timer Script -->
    @if($quiz->time_limit && $attempt)
    <script>
        let timeLimit = {{ $quiz->time_limit * 60 }}; // Convert to seconds
        let startTime = new Date('{{ $attempt->started_at }}').getTime();
        let currentTime = new Date().getTime();
        let elapsedSeconds = Math.floor((currentTime - startTime) / 1000);
        let remainingSeconds = Math.max(0, timeLimit - elapsedSeconds);

        function updateTimer() {
            if (remainingSeconds <= 0) {
                alert('Hết thời gian! Bài thi sẽ được nộp tự động.');
                document.getElementById('quiz-form').submit();
                return;
            }

            let minutes = Math.floor(remainingSeconds / 60);
            let seconds = remainingSeconds % 60;
            
            document.getElementById('time-remaining').textContent = 
                minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
            
            remainingSeconds--;
        }

        // Update timer immediately and then every second
        updateTimer();
        setInterval(updateTimer, 1000);

        // Auto-save answers every 30 seconds
        setInterval(function() {
            // You can implement auto-save functionality here
            console.log('Auto-saving answers...');
        }, 30000);
    </script>
    @endif

    <!-- Alert Messages -->
    @if(session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif

    @if(session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
</body>
</html>