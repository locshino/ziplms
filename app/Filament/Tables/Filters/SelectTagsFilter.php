<?php

namespace App\Filament\Tables\Filters;

use App\Models\Tag;
use Filament\Tables\Filters\SelectFilter;

class SelectTagsFilter extends SelectFilter
{
    protected ?string $type = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Phân loại');
        $this->options(fn (): array => Tag::getWithType($this->getWithType())->pluck('name', 'name')->all());
        $this->query(function ($query, array $state) {
            // This is the custom query logic for the filter.
            // It will only run if at least one tag is selected.
            if (empty($state['values'])) {
                return $query;
            }

            // Use the scope provided by spatie/laravel-tags to filter assignments.
            return $query->withAnyTags($state['values'], $this->getWithType());
        });

        $this->searchable();
        $this->multiple();
        $this->preload();
    }

    public function getWithType(): ?string
    {
        return $this->type;
    }

    public function setWithType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function type(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
