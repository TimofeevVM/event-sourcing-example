<?php

declare(strict_types=1);

namespace Shared\Domain\ValueObject;

abstract class Boolean implements \Stringable, ValueObject
{
    final public function __construct(protected bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function equals(Boolean $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }
}
