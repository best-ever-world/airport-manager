<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240930072149 extends AbstractMigration
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
            $schema->hasTable('airport'),
            sprintf('Migration cannot be executed. Table "%s" already exists.', 'airport')
        );

        $this->addSql('CREATE TABLE airport (
                uuid BINARY(16) NOT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                name VARCHAR(128) NOT NULL,
                code VARCHAR(3) NOT NULL,
                city VARCHAR(128) NOT NULL,
                country BINARY(16) DEFAULT NULL,
                created_by BINARY(16) DEFAULT NULL,
                updated_by BINARY(16) DEFAULT NULL,
                UNIQUE INDEX UNIQ_7E91F7C277153098 (code),
                INDEX IDX_7E91F7C25373C966 (country),
                INDEX IDX_7E91F7C2DE12AB56 (created_by),
                INDEX IDX_7E91F7C216FE72E1 (updated_by),
                INDEX IDX_75EA56E0E3BD61A0 (created_at),
                INDEX IDX_75EA56E0E3BD61A1 (updated_at),
                INDEX IDX_75EA56E0E3BD61A2 (code),
                PRIMARY KEY(uuid)
            ) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE=utf8mb4_general_ci'
        );
        $this->addSql('ALTER TABLE airport ADD CONSTRAINT FK_7E91F7C25373C966 FOREIGN KEY (country) REFERENCES country (uuid)');
        $this->addSql('ALTER TABLE airport ADD CONSTRAINT FK_7E91F7C2DE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (uuid)');
        $this->addSql('ALTER TABLE airport ADD CONSTRAINT FK_7E91F7C216FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (uuid)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof MySQL80Platform,
            sprintf('Migration can only be executed safely on "%s".', MySQL80Platform::class)
        );

        $this->abortIf(
            !$schema->hasTable('airport'),
            sprintf('Migration cannot be executed. Table "%s" is not exists.', 'airport')
        );

        $this->addSql('ALTER TABLE airport DROP FOREIGN KEY FK_7E91F7C25373C966');
        $this->addSql('ALTER TABLE airport DROP FOREIGN KEY FK_7E91F7C2DE12AB56');
        $this->addSql('ALTER TABLE airport DROP FOREIGN KEY FK_7E91F7C216FE72E1');
        $this->addSql('DROP TABLE airport');
    }
}
