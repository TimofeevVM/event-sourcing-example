<?php

declare(strict_types=1);

namespace Shared\Domain\Bus\Event;

interface Deserializer
{
    /**
     * @throws EventException
     */
    public function deserialize(string $stringEvent): Event;
}
