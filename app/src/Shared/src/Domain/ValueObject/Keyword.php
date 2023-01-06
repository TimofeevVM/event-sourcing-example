<?php

declare(strict_types=1);

namespace Shared\Domain\ValueObject;

abstract class Keyword implements \Stringable, ValueObject
{
    final public function __construct(protected string $value)
    {
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Keyword $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
