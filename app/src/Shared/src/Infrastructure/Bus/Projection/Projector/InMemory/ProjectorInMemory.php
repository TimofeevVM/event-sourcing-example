<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Bus\Projection\Projector\InMemory;

use Shared\Domain\Bus\Event\EventStream;
use Shared\Domain\Bus\Projection\Projection;
use Shared\Domain\Bus\Projection\Projector;

class ProjectorInMemory implements Projector
{
    /**
     * @var array<string, Projection[]>
     */
    private array $projections = [];

    /**
     * @param Projection[] $projections
     */
    public function __construct(iterable $projections)
    {
        foreach ($projections as $projection) {
            foreach ($projection->listenTo() as $classEvent) {
                $this->projections[$classEvent][] = $projection;
            }
        }
    }

    public function project(EventStream $eventStream): void
    {
        foreach ($eventStream as $event) {
            if (false === isset($this->projections[get_class($event)])) {
                continue;
            }

            $projections = $this->projections[get_class($event)];

            foreach ($projections as $projection) {
                $projection->project($event);
            }
        }
    }
}
