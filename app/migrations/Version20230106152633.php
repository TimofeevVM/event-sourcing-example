<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106152633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<SQL
        CREATE TABLE IF NOT EXISTS public.event_store_snapshot
        (
            aggregate_id character varying COLLATE pg_catalog."default" NOT NULL,
            version bigint NOT NULL,
            aggregate_serialized text NOT NULL,
            CONSTRAINT event_store_snapshot_pkey PRIMARY KEY (aggregate_id)
        )
SQL;
        $this->connection->executeStatement($sql);
    }

    public function down(Schema $schema): void
    {
        $this->connection->executeStatement('DROP TABLE IF EXISTS public.event_store');
    }
}
