<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Bus\Projection\Projector\Redis;

use Shared\Domain\Bus\Event\Deserializer;
use Shared\Domain\Bus\Projection\Projection;
use SymfonyBundles\RedisBundle\Redis\ClientInterface;

class ProjectorConsumer
{
    /**
     * @var array<string, Projection[]>
     */
    private array $projections = [];

    /**
     * @param Projection[] $projections
     */
    public function __construct(
        iterable $projections,
        protected readonly ClientInterface $client,
        protected readonly Deserializer $deserializer,
        protected readonly string $queueName = 'projection_event',
    ) {
        foreach ($projections as $projection) {
            foreach ($projection->listenTo() as $classEvent) {
                $this->projections[$classEvent][] = $projection;
            }
        }
    }

    public function consume(): void
    {
        while ($data = $this->client->lpop($this->queueName)) {
            try {
                $event = $this->deserializer->deserialize($data);
                if (false === isset($this->projections[get_class($event)])) {
                    continue;
                }

                $projections = $this->projections[get_class($event)];

                foreach ($projections as $projection) {
                    $projection->project($event);
                }
            } catch (\Exception) {
            }
        }
    }
}
