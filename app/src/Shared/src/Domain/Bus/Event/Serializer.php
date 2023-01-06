<?php

declare(strict_types=1);

namespace Shared\Domain\Bus\Event;

interface Serializer
{
    public function serialize(Event $event): string;
}
