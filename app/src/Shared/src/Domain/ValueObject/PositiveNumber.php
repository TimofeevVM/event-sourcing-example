<?php

declare(strict_types=1);

namespace Shared\Domain\ValueObject;

abstract class PositiveNumber extends Number
{
    private function ensureIsValidUuid(int $value): void
    {
        if ($value < 0) {
            throw new ValidateException(sprintf('Значение должно быть положительным, but got %s', $value));
        }
    }
}
