<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Bus\Event\Serializer\JMS;

use JMS\Serializer\SerializerInterface as JMS;
use Shared\Domain\Bus\Event\Deserializer;
use Shared\Domain\Bus\Event\Event;
use Shared\Domain\Bus\Event\EventException;
use Shared\Domain\Bus\Event\Serializer;

class JMSSerializer implements Serializer, Deserializer
{
    public function __construct(
        private readonly JMS $serializer,
    ) {
    }

    public function deserialize(string $stringEvent): Event
    {
        $payload = $this->serializer->deserialize($stringEvent, 'array', 'json');

        if (false === is_array($payload)) {
            throw new EventException('Failed deserialize event: source event is bad');
        }

        /** @var class-string $classEvent */
        $classEvent = $payload['class'];

        if (false === class_exists($classEvent)) {
            throw new EventException(sprintf('Failed deserialize event: class %s not exist', $classEvent ?? ''));
        }

        if (!isset($payload['data'])) {
            throw new EventException('Failed deserialize event: key "data" is not set');
        }

        $jsonData = (string) $payload['data'];

        $event = $this->serializer->deserialize($jsonData, $classEvent, 'json');

        if (!is_object($event) || false === is_subclass_of($event, Event::class)) {
            throw new EventException(sprintf('Failed deserialize event: excepted children %s, got %s', Event::class, gettype($event)));
        }

        return $event;
    }

    public function serialize(Event $event): string
    {
        $payload = [
            'type' => $event::eventName(),
            'class' => $event::class,
            'data' => $this->serializer->serialize($event, 'json'),
        ];

        return $this->serializer->serialize($payload, 'json');
    }
}
