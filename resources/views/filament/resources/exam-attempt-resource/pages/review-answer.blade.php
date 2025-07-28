<x-filament-panels::page>
    @php
    // Lệnh "use" phải nằm bên trong khối @php
        use App\Enums\QuestionType;
        use Illuminate\Support\Arr;

        $question = $record->question;
        $questionTypeTag = $question?->tagsWithType(QuestionType::key())->first();
        $questionType = $questionTypeTag ? QuestionType::tryFrom($questionTypeTag->name) : null;
    @endphp

    <div class="space-y-6">
        {{-- Phần hiển thị nội dung câu hỏi --}}
        <x-filament::section>
            <x-slot name="heading">
                {{ __('review-answer-page.question_heading') }}
            </x-slot>
            <div class="prose dark:prose-invert max-w-none">
                {!! $question->question_text !!}
            </div>
        </x-filament::section>

        {{-- Phần hiển thị các lựa chọn và đáp án --}}
        <x-filament::section>
            <x-slot name="heading">
                {{ __('review-answer-page.answers_heading') }}
            </x-slot>
            <div class="space-y-4">
                @if ($questionType === QuestionType::SingleChoice || $questionType === QuestionType::TrueFalse || $questionType === QuestionType::MultipleChoice)
                    {{-- Hiển thị cho câu hỏi trắc nghiệm --}}
                    @foreach ($question->choices as $choice)
                        @php
                            $isCorrect = $choice->is_correct;
                            $isSelected = ($questionType === QuestionType::MultipleChoice)
                                ? in_array($choice->id, Arr::flatten($record->chosen_option_ids ?? []))
                                : $record->selected_choice_id == $choice->id;

                            $bgColor = 'bg-gray-50 dark:bg-gray-800'; // Mặc định
                            if ($isCorrect)
                                $bgColor = 'bg-green-100 dark:bg-green-800/50'; // Đáp án đúng
                            if ($isSelected && !$isCorrect)
                                $bgColor = 'bg-red-100 dark:bg-red-800/50'; // Chọn sai
                        @endphp
                        <div class="p-4 rounded-lg flex items-center gap-4 {{ $bgColor }}">
                            <div>
                                @if ($isSelected && $isCorrect)
                                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-600" />
                                @elseif ($isSelected && !$isCorrect)
                                    <x-heroicon-o-x-circle class="w-6 h-6 text-red-600" />
                                @elseif ($isCorrect)
                                    <x-heroicon-o-check class="w-6 h-6 text-green-600" />
                                @else
                                    <div class="w-6 h-6"></div> {{-- Placeholder --}}
                                @endif
                            </div>
                            <div class="prose dark:prose-invert max-w-none">
                                {!! $choice->choice_text !!}
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Hiển thị cho câu hỏi tự luận --}}
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('review-answer-page.student_answer') }}</h3>
                            <div class="prose dark:prose-invert max-w-none p-4 bg-gray-50 dark:bg-gray-800 rounded-lg mt-1">
                                {!! $record->answer_text ?: '—' !!}
                            </div>
                        </div>
                        @if($question->correct_answer)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('review-answer-page.correct_answer') }}</h3>
                                <div
                                    class="prose dark:prose-invert max-w-none p-4 bg-green-100 dark:bg-green-800/50 rounded-lg mt-1">
                                    {!! $question->correct_answer !!}
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </x-filament::section>

        {{-- Phần hiển thị giải thích đáp án --}}
        @if ($question->explanation)
            <x-filament::section>
                <x-slot name="heading">
                    {{ __('review-answer-page.explanation_heading') }}
                </x-slot>
                <div class="prose dark:prose-invert max-w-none">
                    {!! $question->explanation !!}
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>