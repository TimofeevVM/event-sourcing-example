<?php

declare(strict_types=1);

namespace Shared\Domain\ValueObject;

abstract class Number implements \Stringable, ValueObject
{
    final public function __construct(protected int $value)
    {
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(Number $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }
}
