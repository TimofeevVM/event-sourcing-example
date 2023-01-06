<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230104105438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create security_user table';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<SQL
        CREATE TABLE IF NOT EXISTS public.security_user
        (
            id uuid NOT NULL,
            username character varying COLLATE pg_catalog."default" NOT NULL,
            password_hash character varying COLLATE pg_catalog."default" NOT NULL,
            CONSTRAINT security_user_pkey PRIMARY KEY (id)
        )
    SQL;
        $this->connection->executeStatement($sql);
    }

    public function down(Schema $schema): void
    {
        $this->connection->executeStatement('DROP TABLE IF EXISTS public.security_user');
    }
}
