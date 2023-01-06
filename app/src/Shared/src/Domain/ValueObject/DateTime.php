<?php

declare(strict_types=1);

namespace Shared\Domain\ValueObject;

abstract class DateTime implements \Stringable, ValueObject
{
    final public function __construct(protected \DateTime $value)
    {
    }

    public static function current(): static
    {
        return new static(new \DateTime());
    }

    public function value(): \DateTime
    {
        return $this->value;
    }

    public function equals(DateTime $other): bool
    {
        return $this->value()->getTimestamp() === $other->value()->getTimestamp();
    }

    public function __toString(): string
    {
        return $this->value()->format(\DATE_RFC3339);
    }
}
