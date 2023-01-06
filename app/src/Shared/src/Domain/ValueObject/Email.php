<?php

declare(strict_types=1);

namespace Shared\Domain\ValueObject;

class Email implements \Stringable, ValueObject
{
    final public function __construct(
        protected string $email
    ) {
        $this->ensureIsValidUuid($email);
    }

     public static function fromString(string $email): static
     {
         return new static($email);
     }

     public function value(): string
     {
         return $this->email;
     }

     public function equals(Email $other): bool
     {
         return $this->value() === $other->value();
     }

     public function __toString(): string
     {
         return $this->value();
     }

     private function ensureIsValidUuid(string $email): void
     {
         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
             throw new ValidateException(sprintf('Некорректный email %s', $email));
         }
     }
}
