<?php

declare(strict_types=1);

namespace Tests\Shared\Integration\Infrastructure\EventSourcing\Snapshot\Dbal;

use Doctrine\ORM\EntityManagerInterface;
use Shared\Domain\EventSourcing\Snapshot\Snapshot;
use Shared\Infrastructure\EventSourcing\Snapshot\Dbal\DbalSnapshotRepository;
use Shared\Infrastructure\Tests\PhpUnit\InfrastructureTestCase;

class DbalSnapshotRepositoryTest extends InfrastructureTestCase
{
    private \Doctrine\DBAL\Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->get(EntityManagerInterface::class);

        $this->connection = $entityManager->getConnection();
    }

    public function testSave(): void
    {
        $obj = new \stdClass();
        $obj->name = 'testSave';

        $snapshot = new Snapshot(
            'aggregate_id',
            12,
            serialize($obj)
        );

        $snapshotRepository = new DbalSnapshotRepository($this->connection);
        $snapshotRepository->save($snapshot);

        $dbSnapshot = $snapshotRepository->get('aggregate_id');

        $this->assertSame($snapshot->aggregateId, $dbSnapshot->aggregateId);
        $this->assertSame($snapshot->version, $dbSnapshot->version);
        $this->assertSame($snapshot->aggregateSerialized, $dbSnapshot->aggregateSerialized);
    }
}
