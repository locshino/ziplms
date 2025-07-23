<x-filament-panels::page>

{{-- GIỮ NGUYÊN CSS HIỆN TẠI --}}
<style>
    :root {
        --body-bg: #f9fafb;
        --card-bg: #ffffff;
        --text-color: #1f2937;
        --text-secondary: #6b7280;
        --border-color: #e5e7eb;
        --primary-color: #4f46e5;
        --primary-color-light: #eef2ff;
        --primary-text: #ffffff;
        --success-color: #16a34a;
        --danger-color: #dc2626;
    }
    .dark {
        --body-bg: #111827;
        --card-bg: #1f2937;
        --text-color: #f3f4f6;
        --text-secondary: #9ca3af;
        --border-color: #4b5563;
        --primary-color-light: rgba(79, 70, 229, 0.15);
    }
    .exam-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        color: var(--text-color);
    }
    .card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.05);
        border-top: 4px solid var(--primary-color);
    }
    .text-center { text-align: center; }
    h2 { font-size: 1.75rem; font-weight: 700; margin-bottom: 0.75rem; letter-spacing: -0.02em; }
    h3 { font-size: 1.25rem; font-weight: 600; line-height: 1.6; }
    h4 { font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-secondary); }
    .btn {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600;
        border: 1px solid transparent; background-color: var(--primary-color);
        color: var(--primary-text); cursor: pointer; transition: all 0.2s ease-in-out;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn.btn-secondary {
        background-color: var(--card-bg); color: var(--text-color); border-color: var(--border-color);
    }
    .btn.btn-secondary:hover {
        background-color: var(--body-bg); color: var(--text-color); border-color: var(--border-color);
    }
    .btn.btn-success {
        background-color: var(--success-color); border-color: var(--success-color);
    }
    .btn:disabled {
        opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none;
    }
    .btn-palette {
        width: 40px; height: 40px; padding: 0;
        border: 1px solid var(--border-color); background-color: var(--card-bg);
        color: var(--text-color); border-radius: 8px; font-weight: 600;
        transition: all 0.2s ease; cursor: pointer;
    }
    .btn-palette:hover {
        transform: translateY(-2px); border-color: var(--primary-color);
    }
    .btn-palette.current {
        background-color: var(--primary-color); border-color: var(--primary-color);
        color: var(--primary-text); box-shadow: 0 0 10px rgba(79, 70, 229, 0.5);
    }
    .btn-palette.answered {
        background-color: #dcfce7; border-color: #86efac; color: #166534;
    }
    .dark .btn-palette.answered {
        background-color: #14532d; border-color: #22c55e; color: #a7f3d0;
    }
    .badge {
        flex-shrink: 0; display: inline-block;
        padding: 4px 12px; font-size: 0.75rem; font-weight: 600;
        border-radius: 9999px; background-color: #dbeafe; color: #1e40af;
    }
    .dark .badge {
        background-color: #1e3a8a; color: #bfdbfe;
    }
    .form-input, textarea.form-input {
        width: 100%; padding: 10px 14px;
        border: 1px solid var(--border-color); background-color: var(--body-bg);
        border-radius: 8px; color: var(--text-color);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-input:focus, textarea.form-input:focus {
        outline: none; border-color: var(--primary-color);
        box-shadow: 0 0 0 3px var(--primary-color-light);
    }
    .choice-label {
        display: block; padding: 16px; border: 2px solid var(--border-color);
        border-radius: 10px; cursor: pointer; transition: border-color 0.2s, background-color 0.2s;
        position: relative;
    }
    .choice-label:hover { border-color: var(--primary-color); }
    .choice-label input { display: none; }
    .choice-label:has(input:checked) {
        border-color: var(--primary-color); background-color: var(--primary-color-light);
    }
    .choice-label:has(input:checked)::after {
        content: '✔'; position: absolute; top: 50%; right: 16px;
        transform: translateY(-50%); color: var(--primary-color); font-size: 1.25rem;
    }
    .fill-blank-input {
        display: inline-block;
        width: 150px;
        border: none;
        border-bottom: 2px solid var(--text-secondary);
        background-color: transparent;
        text-align: center;
        margin: 0 8px;
        padding: 2px 4px;
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--text-color);
    }
    .fill-blank-input:focus {
        outline: none;
        border-bottom-color: var(--primary-color);
    }
    /* THÊM CSS CHO MODAL */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
    }
    .dark .modal-overlay {
        background-color: rgba(20, 20, 30, 0.7);
    }
    .modal-content {
        max-width: 450px;
        width: 95%;
        border-top: 4px solid var(--primary-color);
        margin: 0; /* Ghi đè margin-bottom từ class .card */
    }
</style>

{{-- BẮT ĐẦU: KHAI BÁO ALPINE.JS CHO MODAL --}}
<div class="exam-container" x-data="{
    isModalOpen: false,
    modalTitle: '',
    modalMessage: '',
    confirmAction: '',
    confirmButtonText: 'Confirm',
    confirmButtonClass: 'btn'
}">



    @if (!$examStarted)
        <div class="card">
            <h2>{{ $record->title }}</h2>
            <p style="color: var(--text-secondary); margin-bottom: 1rem;">{!! $record->description !!}</p>
            <p style="margin-bottom: 1.5rem; font-weight: 500;">Duration: {{ $record->duration_minutes }} minutes</p>

            <div class="text-center" style="padding-top: 1rem; border-top: 1px solid var(--border-color);">
                @if ($this->incompleteAttempt)
                    <button type="button" class="btn btn-secondary" wire:click="continueExam" style="margin-right: 1rem;">Continue unfinished attempt</button>
                @endif

                {{-- THAY ĐỔI: Nút Start Exam giờ sẽ mở modal --}}
                <button
                    type="button"
                    class="btn"
                    @click="
                        isModalOpen = true;
                        modalTitle = 'Start New Exam';
                        modalMessage = 'Starting a new attempt will delete the previous unfinished one (if any). Are you sure?';
                        confirmAction = 'startExam';
                        confirmButtonText = 'Yes, Start New';
                        confirmButtonClass = 'btn';
                    ">
                    Start new exam
                </button>
            </div>
        </div>
    @else
        {{-- Phần bài thi đang diễn ra --}}
        <div x-data="{ timeLeft: @entangle('timeLeft'), timer: null, init() { if (this.timeLeft > 0) { this.timer = setInterval(() => { this.timeLeft--; if (this.timeLeft <= 0) { clearInterval(this.timer); @this.submitExam(); } }, 1000); } }, formatTime() { if (this.timeLeft === null || this.timeLeft < 0) return '00:00'; const minutes = Math.floor(this.timeLeft / 60); const seconds = this.timeLeft % 60; return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`; } }">
            <div class="card">
                <div class="text-center" style="margin-bottom: 1.5rem;">
                    <h4>Time left</h4>
                    <p x-text="formatTime()" style="font-family: monospace; font-size: 2.5rem; font-weight: 700;" :style="timeLeft < 60 && { color: 'var(--danger-color)' }"></p>
                </div>
                <hr style="border-color: var(--border-color);" />
                <div style="padding-top: 1.5rem;">
                    <h4>Questions</h4>
                    <div style="display: flex; gap: 0.5rem; overflow-x: auto; padding-bottom: 8px;">
                        @foreach ($questions as $index => $question)
                            @php
                                $isAnswered = !is_null(
                                    ($singleChoiceAnswers[$question->id] ?? null) ??
                                    (!empty($multipleChoiceAnswers[$question->id]) ? true : null) ??
                                    (filled($shortAnswers[$question->id] ?? null) ? true : null) ??
                                    (filled($essayAnswers[$question->id] ?? null) ? true : null) ??
                                    (!empty(array_filter($fillBlankAnswers[$question->id] ?? [])) ? true : null)
                                );
                                $paletteBtnClass = 'btn-palette';
                                if ($currentQuestionIndex == $index) $paletteBtnClass .= ' current';
                                elseif ($isAnswered) $paletteBtnClass .= ' answered';
                            @endphp
                            <button type="button" wire:click="goToQuestion({{ $index }})" class="{{ $paletteBtnClass }}">{{ $index + 1 }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                @php
                    $currentQuestion = $questions[$currentQuestionIndex];
                    $questionType = $this->getQuestionType($currentQuestion);
                @endphp
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; gap: 1rem;">
                    @if($questionType?->value !== 'fill_blank')
                        <h3>{!! $currentQuestion->question_text !!}</h3>
                    @endif
                    <span class="badge">{{ $questionMeta[$currentQuestion->id]['points'] ?? 1 }} points</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @switch($questionType)
                        @case(App\Enums\QuestionType::SingleChoice)
                        @case(App\Enums\QuestionType::TrueFalse)
                            @foreach($currentQuestion->choices as $choice)
                                <label class="choice-label">
                                    <input type="radio" name="answer.{{ $currentQuestion->id }}" wire:model.live="singleChoiceAnswers.{{ $currentQuestion->id }}" value="{{ $choice->id }}">
                                    <span>{!! $choice->choice_text !!}</span>
                                </label>
                            @endforeach
                            @break
                        @case(App\Enums\QuestionType::MultipleChoice)
                            @foreach($currentQuestion->choices as $choice)
                                <label class="choice-label">
                                    <input type="checkbox" wire:model.live="multipleChoiceAnswers.{{ $currentQuestion->id }}" value="{{ $choice->id }}">
                                    <span>{!! $choice->choice_text !!}</span>
                                </label>
                            @endforeach
                            @break
                        @case(App\Enums\QuestionType::ShortAnswer)
                            <input type="text" class="form-input" wire:model.live.debounce.500ms="shortAnswers.{{ $currentQuestion->id }}" placeholder="Enter your answer">
                            @break
                        @case(App\Enums\QuestionType::Essay)
                            <textarea class="form-input" wire:model.live.debounce.500ms="essayAnswers.{{ $currentQuestion->id }}" placeholder="Enter your essay" rows="6"></textarea>
                            @break
                        @case(App\Enums\QuestionType::FillBlank)
                            @php
                                $parts = explode('[BLANK]', $currentQuestion->question_text);
                            @endphp
                            <div style="font-size: 1.25rem; line-height: 2.5;">
                                @foreach($parts as $index => $part)
                                    {!! $part !!}
                                    @if(!$loop->last)
                                        <input type="text"
                                               class="form-input fill-blank-input"
                                               wire:model.live.debounce.500ms="fillBlankAnswers.{{ $currentQuestion->id }}.{{ $index }}"
                                               autocomplete="off">
                                    @endif
                                @endforeach
                            </div>
                            @break
                    @endswitch
                </div>
            </div>

            <div class="card">
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <button type="button" class="btn btn-secondary" wire:click="previousQuestion" @disabled($currentQuestionIndex === 0)>Previous</button>
                    <button type="button" class="btn" wire:click="nextQuestion" @disabled(($questions?->count() ?? 0) > 0 && $currentQuestionIndex === $questions->count() - 1)>Next</button>
                </div>

                {{-- THAY ĐỔI: Nút Submit giờ sẽ mở modal --}}
                <button
                    type="button"
                    class="btn btn-success"
                    style="width: 100%;"
                    @click="
                        isModalOpen = true;
                        modalTitle = 'Submit Exam';
                        modalMessage = 'Are you sure you want to submit? You cannot change your answers after this.';
                        confirmAction = 'submitExam';
                        confirmButtonText = 'Yes, Submit';
                        confirmButtonClass = 'btn btn-success';
                    ">
                    Submit
                </button>
            </div>
        </div>
    @endif

    {{-- BẮT ĐẦU: CẤU TRÚC HTML CỦA MODAL --}}
    <div
        x-show="isModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="modal-overlay"
        @keydown.escape.window="isModalOpen = false"
        x-cloak>
        <div
            class="card modal-content"
            @click.outside="isModalOpen = false">
            <h3 x-text="modalTitle" style="margin-bottom: 1rem;"></h3>
            <p x-text="modalMessage" style="color: var(--text-secondary); margin-bottom: 1.5rem;"></p>
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="btn btn-secondary" @click="isModalOpen = false">
                    Cancel
                </button>
                <button
                    type="button"
                    :class="confirmButtonClass"
                    @click="$wire.call(confirmAction); isModalOpen = false">
                    <span x-text="confirmButtonText"></span>
                </button>
            </div>
        </div>
    </div>
    {{-- KẾT THÚC: CẤU TRÚC HTML CỦA MODAL --}}

</div>

</x-filament-panels::page>
