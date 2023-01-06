<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230103140251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Event Store table';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<SQL
        CREATE TABLE IF NOT EXISTS public.event_store
        (
            id uuid NOT NULL,
            aggregate_id uuid NOT NULL,
            version bigint NOT NULL,
            occurred_on timestamp without time zone NOT NULL,
            payload jsonb NOT NULL,
            CONSTRAINT event_store_pkey PRIMARY KEY (id)
        )
SQL;
        $this->connection->executeStatement($sql);
    }

    public function down(Schema $schema): void
    {
        $this->connection->executeStatement('DROP TABLE IF EXISTS public.event_store');
    }
}
