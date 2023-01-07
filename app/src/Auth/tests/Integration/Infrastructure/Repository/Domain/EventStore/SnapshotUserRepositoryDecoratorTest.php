<?php

declare(strict_types=1);

namespace Tests\Auth\Integration\Infrastructure\Repository\Domain\EventStore;

use Auth\Domain\User\Model\User;
use Auth\Domain\User\Model\UserEmail;
use Auth\Domain\User\Model\Username;
use Auth\Domain\User\Model\UserPasswordHash;
use Auth\Infrastructure\Projection\SecurityUser\Dbal\UserWasPasswordHashChangedProjection;
use Auth\Infrastructure\Projection\SecurityUser\Dbal\UserWasRegisteredProjection;
use Auth\Infrastructure\Repository\Domain\User\EventStore\EsUserRepositoryPersistence;
use Auth\Infrastructure\Repository\Domain\User\EventStore\SnapshotUserRepositoryDecorator;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerBuilder;
use Shared\Infrastructure\Bus\Event\Serializer\JMS\JMSSerializer;
use Shared\Infrastructure\Bus\Projection\Projector\InMemory\ProjectorInMemory;
use Shared\Infrastructure\EventSourcing\EventStore\Dbal\DbalEventStore;
use Shared\Infrastructure\EventSourcing\Snapshot\Dbal\DbalSnapshotRepository;
use Shared\Infrastructure\Tests\PhpUnit\InfrastructureTestCase;

class SnapshotUserRepositoryDecoratorTest extends InfrastructureTestCase
{
    private \Doctrine\DBAL\Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->get(EntityManagerInterface::class);

        $this->connection = $entityManager->getConnection();
    }

    public function testPersist(): void
    {
        $user = User::register(
            Username::fromString('somebody'),
            UserEmail::fromString('somebody@gmail.com'),
            UserPasswordHash::fromString('123456'),
        );

        $user->changePasswordHash(UserPasswordHash::fromString('qwerty'));
        $user->changePasswordHash(UserPasswordHash::fromString('qwerty123'));
        $user->changePasswordHash(UserPasswordHash::fromString('qwerty123!'));
        $user->changeEmail(UserEmail::fromString('newEmail@gmail.com'));
        $user->changeEmail(UserEmail::fromString('finished@gmail.com'));

        $serializer = new JMSSerializer(SerializerBuilder::create()->build());
        $eventStore = new DbalEventStore(
            $this->connection,
            $serializer,
            $serializer
        );

        $repository = new EsUserRepositoryPersistence(
            $eventStore,
            new ProjectorInMemory([
                new UserWasRegisteredProjection($this->connection),
                new UserWasPasswordHashChangedProjection($this->connection),
            ])
        );

        $snapshotRepository = new DbalSnapshotRepository($this->connection);
        $repository = new SnapshotUserRepositoryDecorator(
            $repository,
            $snapshotRepository,
            $eventStore,
            5
        );

        $repository->persist($user);

        $esUser = $repository->ofId($user->id());

        $snapshotUser = $snapshotRepository->get($esUser->id()->value());
        $this->assertSame($esUser->version(), $snapshotUser->version);
    }
}
