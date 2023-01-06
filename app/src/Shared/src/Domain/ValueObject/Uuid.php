<?php

declare(strict_types=1);

namespace Shared\Domain\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid implements \Stringable, ValueObject
{
    final public function __construct(
        protected string $value
    ) {
        $this->ensureIsValidUuid($this->value);
    }

     public static function random(): static
     {
         return new static(RamseyUuid::uuid4()->toString());
     }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public function value(): string
    {
        return $this->value;
    }

     public function equals(Uuid $other): bool
     {
         return $this->value() === $other->value();
     }

     public function __toString(): string
     {
         return $this->value();
     }

    /**
     * @throws ValidateException
     */
    private function ensureIsValidUuid(string $id): void
    {
        if (false === RamseyUuid::isValid($id)) {
            throw new ValidateException(sprintf('Некорректный id %s', $id ?: ''));
        }
    }
}
