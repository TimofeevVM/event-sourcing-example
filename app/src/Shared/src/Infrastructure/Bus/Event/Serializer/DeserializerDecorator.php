<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Bus\Event\Serializer;

use Shared\Domain\Bus\Event\Deserializer;
use Shared\Domain\Bus\Event\Event;

abstract class DeserializerDecorator implements Deserializer
{
    public function __construct(
        protected readonly Deserializer $deserializer,
    ) {
    }

    public function deserialize(string $stringEvent): Event
    {
        return $this->deserializer->deserialize($stringEvent);
    }
}
