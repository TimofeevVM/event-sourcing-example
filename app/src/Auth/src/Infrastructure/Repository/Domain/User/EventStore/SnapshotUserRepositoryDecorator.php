<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Repository\Domain\User\EventStore;

use Auth\Domain\User\Model\User;
use Auth\Domain\User\Model\UserRepositoryPersistence;
use Auth\Infrastructure\Repository\Domain\User\UserRepositoryDecorator;
use Shared\Domain\Aggregate\AggregateId;
use Shared\Domain\EventSourcing\EventStore\EventStore;
use Shared\Domain\EventSourcing\Snapshot\Snapshot;
use Shared\Domain\EventSourcing\Snapshot\SnapshotNotFoundException;
use Shared\Domain\EventSourcing\Snapshot\SnapshotRepository;

class SnapshotUserRepositoryDecorator extends UserRepositoryDecorator implements UserRepositoryPersistence
{
    public function __construct(
        UserRepositoryPersistence $repository,
        protected readonly SnapshotRepository $snapshotRepository,
        protected readonly EventStore $eventStore,
        protected readonly int $every = 100,
    ) {
        parent::__construct($repository);
    }

    public function ofId(AggregateId $userId): User
    {
        try {
            $snapshot = $this->snapshotRepository->get($userId->value());
            $user = unserialize($snapshot->aggregateSerialized);

            if (false === $user instanceof User) {
                throw new \RuntimeException('Bad snapshot');
            }

            $newEvents = $this->eventStore->getEventStreamFromVersion($user->id(), $snapshot->version);
            $user->replay($newEvents);

            return $user;
        } catch (\Exception) {
        }

        return $this->repository->ofId($userId);
    }

    public function persist(User $user): void
    {
        $this->repository->persist($user);

        $oldSnapshotVersion = 0;
        try {
            $snapshot = $this->snapshotRepository->get($user->id()->value());
            $oldSnapshotVersion = $snapshot->version;
        } catch (SnapshotNotFoundException) {
        }

        if ($user->version() - $oldSnapshotVersion >= $this->every) {
            $this->snapshotRepository->save(
                new Snapshot(
                    $user->id()->value(),
                    $user->version(),
                    serialize($user),
                )
            );
        }
    }
}
