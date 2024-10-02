<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240929201155 extends AbstractMigration
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
            $schema->hasTable('country'),
            sprintf('Migration cannot be executed. Table "%s" already exists.', 'country')
        );

        $this->addSql('CREATE TABLE country (
                uuid BINARY(16) NOT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                name VARCHAR(64) NOT NULL,
                alpha_2_code VARCHAR(2) NOT NULL,
                alpha_3_code VARCHAR(3) NOT NULL,
                numeric_code VARCHAR(3) NOT NULL,
                iso_3166_code VARCHAR(16) NOT NULL,
                region VARCHAR(64) DEFAULT NULL,
                sub_region VARCHAR(128) DEFAULT NULL,
                intermediate_region VARCHAR(64) DEFAULT NULL,
                region_code VARCHAR(3) DEFAULT NULL,
                sub_region_code VARCHAR(3) DEFAULT NULL,
                intermediate_region_code VARCHAR(3) DEFAULT NULL,
                UNIQUE INDEX UNIQ_5373C9665E237E06 (name),
                UNIQUE INDEX UNIQ_5373C966EF626C90 (alpha_2_code),
                UNIQUE INDEX UNIQ_5373C966243EBF35 (alpha_3_code),
                UNIQUE INDEX UNIQ_5373C96695079952 (numeric_code),
                UNIQUE INDEX UNIQ_5373C966F5AC16B9 (iso_3166_code),
                INDEX IDX_75EA56E0E3BD61A0 (created_at),
                INDEX IDX_75EA56E0E3BD61A1 (updated_at),
                INDEX IDX_75EA56E0E3BD61A2 (alpha_2_code),
                INDEX IDX_75EA56E0E3BD61A3 (alpha_3_code),
                INDEX IDX_75EA56E0E3BD61A4 (numeric_code),
                INDEX IDX_75EA56E0E3BD61A5 (iso_3166_code),
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
            !$schema->hasTable('country'),
            sprintf('Migration cannot be executed. Table "%s" is not exists.', 'country')
        );

        $this->addSql('DROP TABLE country');
    }
}
