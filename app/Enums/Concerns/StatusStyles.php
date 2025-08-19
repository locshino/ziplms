<?php

namespace App\Enums\Concerns;

/**
 * Trait providing common styling methods for status enums.
 * This trait implements the basic structure for getLabel, getDescription, getIcon, and getColor methods.
 */
trait StatusStyles
{
    /**
     * Get the human-readable label for the status.
     */
    public function getLabel(): ?string
    {
        return __('enums_status.' . $this->value, [], null) ?? $this->name;
    }

    /**
     * Get the icon for the status.
     */
    public function getIcon(): ?string
    {
        return match ($this->value) {
            'draft' => 'heroicon-m-pencil-square',
            'published' => 'heroicon-m-eye',
            'archived' => 'heroicon-m-archive-box',
            'suspended' => 'heroicon-m-pause-circle',
            'closed' => 'heroicon-m-lock-closed',
            'review' => 'heroicon-m-eye',
            'active' => 'heroicon-m-check-circle',
            'inactive' => 'heroicon-m-x-circle',
            'pending' => 'heroicon-m-clock',
            'started' => 'heroicon-m-play',
            'in_progress' => 'heroicon-m-clock',
            'completed' => 'heroicon-m-check',
            'abandoned' => 'heroicon-m-x-mark',
            'graded' => 'heroicon-m-academic-cap',
            'submitted' => 'heroicon-m-paper-airplane',
            'returned' => 'heroicon-m-arrow-uturn-left',
            'late' => 'heroicon-m-exclamation-triangle',
            default => null,
        };
    }

    /**
     * Get the color for the status.
     */
    public function getColor(): string|array|null
    {
        return match ($this->value) {
            'draft' => 'gray',
            'published' => 'success',
            'archived' => 'warning',
            'suspended' => 'danger',
            'closed' => 'warning',
            'review' => 'warning',
            'active' => 'success',
            'inactive' => 'gray',
            'pending' => 'warning',
            'started' => 'info',
            'in_progress' => 'warning',
            'completed' => 'success',
            'abandoned' => 'danger',
            'graded' => 'primary',
            'submitted' => 'info',
            'returned' => 'warning',
            'late' => 'danger',
            default => 'gray',
        };
    }
}
