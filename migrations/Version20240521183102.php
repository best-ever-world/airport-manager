<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240521183102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof MySQL80Platform,
            sprintf('Migration can only be executed safely on "%s".', MySQL80Platform::class)
        );

        $this->abortIf(
            $schema->hasTable('user'),
            sprintf('Migration cannot be executed. Table "%s" already exists.', 'user')
        );

        $this->addSql('CREATE TABLE `user` (
                uuid BINARY(16) NOT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                first_name VARCHAR(64) NOT NULL,
                last_name VARCHAR(64) NOT NULL,
                username VARCHAR(128) NOT NULL,
                password VARCHAR(128) NOT NULL,
                roles JSON NOT NULL,
                approved TINYINT(1) NOT NULL,
                disabled TINYINT(1) NOT NULL,
                UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
                INDEX IDX_75EA56E0E3BD61A0 (created_at),
                INDEX IDX_75EA56E0E3BD61A1 (updated_at),
                INDEX IDX_75EA56E0E3BD61A2 (username),
                PRIMARY KEY(uuid)
            ) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE=utf8mb4_general_ci'
        );
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof MySQL80Platform,
            sprintf('Migration can only be executed safely on "%s".', MySQL80Platform::class)
        );

        $this->abortIf(
            !$schema->hasTable('user'),
            sprintf('Migration cannot be executed. Table "%s" is not exists.', 'user')
        );

        $this->addSql('DROP TABLE user');
    }
}
