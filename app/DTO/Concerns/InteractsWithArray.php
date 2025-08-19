<?php

namespace App\DTO\Concerns;

use Illuminate\Http\Request;

trait InteractsWithArray
{
    public static function fromArray(array $data): static
    {
        return new static(...$data);
    }

    public static function fromRequest(Request $request): static
    {
        return new static(...$request->only(array_keys(get_class_vars(static::class))));
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
