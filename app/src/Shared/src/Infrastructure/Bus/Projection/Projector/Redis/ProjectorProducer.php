<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Bus\Projection\Projector\Redis;

use Shared\Domain\Bus\Event\EventStream;
use Shared\Domain\Bus\Event\Serializer;
use Shared\Domain\Bus\Projection\Projector;
use SymfonyBundles\RedisBundle\Redis\ClientInterface;

class ProjectorProducer implements Projector
{
    public function __construct(
        protected readonly ClientInterface $redis,
        protected readonly Serializer $serializer,
        protected readonly string $queueName = 'projection_event',
    ) {
    }

    public function project(EventStream $eventStream): void
    {
        foreach ($eventStream as $event) {
            $data = $this->serializer->serialize($event);
            $this->redis->lpush($this->queueName, [$data]);
        }
    }
}
