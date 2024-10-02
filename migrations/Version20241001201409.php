<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241001201409 extends AbstractMigration
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
            $schema->hasTable('user_token_refresh'),
            sprintf('Migration cannot be executed. Table "%s" already exists.', 'user_token_refresh')
        );

        $this->addSql('CREATE TABLE user_token_refresh (
                id INT AUTO_INCREMENT NOT NULL,
                refresh_token VARCHAR(128) NOT NULL,
                username VARCHAR(255) NOT NULL,
                valid DATETIME NOT NULL,
                UNIQUE INDEX UNIQ_F02938B8C74F2195 (refresh_token),
                PRIMARY KEY(id)
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
            !$schema->hasTable('user_token_refresh'),
            sprintf('Migration cannot be executed. Table "%s" is not exists.', 'user_token_refresh')
        );

        $this->addSql('DROP TABLE user_token_refresh');
    }
}
