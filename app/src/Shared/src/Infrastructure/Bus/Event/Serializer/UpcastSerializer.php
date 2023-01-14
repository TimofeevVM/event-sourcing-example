<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Bus\Event\Serializer;

use Shared\Domain\Bus\Event\Deserializer;
use Shared\Domain\Bus\Event\Event;
use Shared\Domain\EventSourcing\Upcasting\Upcaster;

class UpcastSerializer extends DeserializerDecorator
{
    /**
     * @var Upcaster[]
     */
    protected array $upcasters = [];

    public function __construct(
        Deserializer $deserializer,
        iterable $upcasters
    ) {
        parent::__construct($deserializer);
        /**
         * @var Upcaster[] $upcasters
         */
        foreach ($upcasters as $upcaster) {
            $type = $this->extractType(get_class($upcaster));
            if (null === $type) {
                continue;
            }
            $this->upcasters[$type] = $upcaster;
        }
    }

    /**
     * @throws \Shared\Domain\Bus\Event\EventException
     *
     * @psalm-suppress InvalidFunctionCall
     */
    public function deserialize(string $stringEvent): Event
    {
        $event = $this->deserializer->deserialize($stringEvent);
        $eventClass = get_class($event);

        if (isset($this->upcasters[$eventClass])) {
            /** @var Event $event */
            $event = $this->upcasters[$eventClass]($event);
        }

        return $event;
    }

    /**
     * @psalm-param class-string $class
     */
    public function extractType(string $class): ?string
    {
        $reflector = new \ReflectionClass($class);
        $method = $reflector->getMethod('__invoke');

        $parameters = $method->getParameters();
        if (empty($parameters)) {
            return null;
        }

        /** @psalm-var \ReflectionNamedType|null $fistParameterType */
        $fistParameterType = $parameters[0]->getType();

        if (null === $fistParameterType) {
            return null;
        }

        return $fistParameterType->getName();
    }
}
