<?php

namespace App\Libs\Permissions;

use App\Enums\Permissions\PermissionContextEnum;
use App\Enums\Permissions\PermissionNounEnum;
use App\Enums\Permissions\PermissionVerbEnum;
use InvalidArgumentException;

/**
 * PermissionBuilder
 *
 * A builder class for creating permission strings in the format [Verb]_[Noun]_[Context].
 * Supports both relationship-based and attribute-based contexts with specific values.
 */
class PermissionBuilder
{
    protected ?PermissionVerbEnum $verb = null;

    protected ?PermissionNounEnum $noun = null;

    protected ?PermissionContextEnum $context = null;

    protected ?string $attributeValue = null;

    /**
     * Set the permission verb (action).
     *
     * @param  PermissionVerbEnum  $verb  The action to be performed
     */
    public function verb(PermissionVerbEnum $verb): self
    {
        $this->verb = $verb;

        return $this;
    }

    /**
     * Set the permission noun (resource).
     *
     * @param  PermissionNounEnum  $noun  The resource being acted upon
     */
    public function noun(PermissionNounEnum $noun): self
    {
        $this->noun = $noun;

        return $this;
    }

    /**
     * Set the permission context.
     *
     * @param  PermissionContextEnum  $context  The context of the permission
     */
    public function context(PermissionContextEnum $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Set the attribute value for attribute-based contexts.
     *
     * This method is used when the context requires a specific value,
     * such as ID or TAG contexts.
     *
     * @param  string  $value  The specific attribute value
     */
    public function withAttribute(string $value): self
    {
        $this->attributeValue = $value;

        return $this;
    }

    /**
     * Build the permission string.
     *
     * Creates a permission string in the format [Verb]_[Noun]_[Context]
     * or [Verb]_[Noun]_[Context]::[AttributeValue] for attribute-based contexts.
     *
     * @return string The formatted permission string
     *
     * @throws InvalidArgumentException If required components are missing
     *
     * @example
     * // Basic permission: "manage_course_all"
     * $builder->verb(PermissionVerbEnum::MANAGE)
     *         ->noun(PermissionNounEnum::COURSE)
     *         ->context(PermissionContextEnum::ALL)
     *         ->build();
     *
     * // Attribute-based permission: "manage_course_id::123-123-456"
     * $builder->verb(PermissionVerbEnum::MANAGE)
     *         ->noun(PermissionNounEnum::COURSE)
     *         ->context(PermissionContextEnum::ID)
     *         ->withAttribute('123-123-456')
     *         ->build();
     */
    public function build(): string
    {
        $this->validateComponents();

        $permission = sprintf(
            '%s_%s_%s',
            $this->verb->value,
            $this->noun->value,
            $this->context->value
        );

        // Add attribute value for attribute-based contexts
        if ($this->isAttributeBasedContext() && $this->attributeValue !== null) {
            $permission .= '::'.$this->attributeValue;
        }

        return $permission;
    }

    /**
     * Create a new instance of PermissionBuilder.
     */
    public static function make(): self
    {
        return new self;
    }

    /**
     * Reset the builder to its initial state.
     */
    public function reset(): self
    {
        $this->verb = null;
        $this->noun = null;
        $this->context = null;
        $this->attributeValue = null;

        return $this;
    }

    /**
     * Validate that all required components are set.
     *
     * @throws InvalidArgumentException If any required component is missing
     */
    private function validateComponents(): void
    {
        if ($this->verb === null) {
            throw new InvalidArgumentException('Permission verb is required');
        }

        if ($this->noun === null) {
            throw new InvalidArgumentException('Permission noun is required');
        }

        if ($this->context === null) {
            throw new InvalidArgumentException('Permission context is required');
        }

        // Validate attribute value for attribute-based contexts
        if ($this->isAttributeBasedContext() && $this->attributeValue === null) {
            throw new InvalidArgumentException(
                sprintf(
                    'Attribute value is required for context "%s"',
                    $this->context->value
                )
            );
        }
    }

    /**
     * Check if the current context is attribute-based.
     *
     * @return bool True if the context requires an attribute value
     */
    private function isAttributeBasedContext(): bool
    {
        return in_array($this->context, [
            PermissionContextEnum::ID,
            PermissionContextEnum::TAG,
        ], true);
    }
}
