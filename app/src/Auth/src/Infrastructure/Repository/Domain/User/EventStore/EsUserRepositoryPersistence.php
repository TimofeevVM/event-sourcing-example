<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Repository\Domain\User\EventStore;

use Auth\Domain\User\Model\User;
use Auth\Domain\User\Model\UserRepositoryPersistence;
use Shared\Domain\Aggregate\AggregateId;
use Shared\Domain\Bus\Projection\Projector;
use Shared\Domain\EventSourcing\EventStore\EventStore;

class EsUserRepositoryPersistence implements UserRepositoryPersistence
{
    public function __construct(
        private readonly EventStore $eventStore,
        private readonly Projector $projector,
    ) {
    }

    public function ofId(AggregateId $userId): User
    {
        $events = $this->eventStore->getEventStream($userId);

        return User::reconstruct($events);
    }

    public function persist(User $user): void
    {
        $events = $user->pullEvents();
        $this->eventStore->append($events);
        $this->projector->project($events);
    }
}
