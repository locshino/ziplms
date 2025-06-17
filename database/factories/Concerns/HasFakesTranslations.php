<?php

namespace Database\Factories\Concerns;

use Faker\Generator as Faker;

/**
 * Trait HasFakesTranslations
 *
 * Provides reusable methods for generating multilingual fake data.
 */
trait HasFakesTranslations
{
    protected array $translationLocales = ['en', 'vi'];

    /**
     * Set custom locales (e.g., ['en', 'vi', 'fr']).
     */
    public function setTranslationLocales(array $locales): static
    {
        $this->translationLocales = $locales;

        return $this;
    }

    /**
     * Generate translations using a callback.
     */
    protected function makeTranslations(callable $callback, ?array $locales = null): array
    {
        return collect($locales ?? $this->translationLocales)
            ->mapWithKeys(function ($locale) use ($callback) {
                return [$locale => $callback(fake($locale))];
            })
            ->toArray();
    }

    protected function fakeTranslations(string $fakerMethod, array $args = [], ?array $locales = null): array
    {
        $fakerMethod = match ($fakerMethod) {
            'word', 'words', 'sentence', 'sentences', 'paragraph', 'paragraphs', 'text' => $fakerMethod,
            default => throw new \InvalidArgumentException("Invalid faker method: {$fakerMethod}"),
        };

        return $this->makeTranslations(fn(Faker $faker) => $faker->{$fakerMethod}(...$args), $locales);
    }

    // CÁC PHƯƠNG THỨC CỤ THỂ DỄ GỌI HƠN

    protected function fakeWordTranslations(?array $locales = null): array
    {
        return $this->fakeTranslations('word', [], $locales);
    }

    protected function fakeWordsTranslations(int $count = 4, bool $asText = true, ?array $locales = null): array
    {
        return $this->fakeTranslations('words', [$count, $asText], $locales);
    }

    protected function fakeSentenceTranslations(int $nbWords = 6, ?array $locales = null): array
    {
        return $this->fakeTranslations('sentence', [$nbWords], $locales);
    }

    protected function fakeParagraphTranslations(int $nbSentences = 3, ?array $locales = null): array
    {
        return $this->fakeTranslations('paragraph', [$nbSentences], $locales);
    }

    protected function fakeTextTranslations(int $maxNbChars = 200, ?array $locales = null): array
    {
        return $this->fakeTranslations('text', [$maxNbChars], $locales);
    }

    /**
     * Fake prefixed strings (e.g. for labels).
     */
    protected function fakePrefixedTranslations(string $baseText, array $prefixesByLocale = [], ?array $locales = null): array
    {
        return collect($locales ?? $this->translationLocales)
            ->mapWithKeys(function ($locale) use ($prefixesByLocale, $baseText) {
                $prefix = $prefixesByLocale[$locale] ?? '';

                return [$locale => $prefix . $baseText];
            })
            ->toArray();
    }

    /**
     * Assign static translations (useful for predefined values).
     */
    protected function staticTranslations(array $translations, ?array $locales = null): array
    {
        $locales = $locales ?? $this->translationLocales;

        return collect($locales)
            ->mapWithKeys(fn($locale) => [$locale => $translations[$locale] ?? null])
            ->filter() // bỏ các bản dịch null nếu không đủ locale
            ->toArray();
    }
}
