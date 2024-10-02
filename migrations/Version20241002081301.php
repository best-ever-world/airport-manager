<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241002081301 extends AbstractMigration
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
            $schema->hasTable('user_token'),
            sprintf('Migration cannot be executed. Table "%s" already exists.', 'user_token')
        );

        $this->addSql('CREATE TABLE user_token (
                uuid BINARY(16) NOT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                identifier BINARY(16) NOT NULL,
                user BINARY(16) DEFAULT NULL,
                UNIQUE INDEX UNIQ_BDF55A63772E836A (identifier),
                INDEX IDX_BDF55A638D93D649 (user),
                INDEX IDX_75EA56E0E3BD61B0 (created_at),
                INDEX IDX_75EA56E0E3BD61B1 (updated_at),
                INDEX IDX_75EA56E0E3BD61b2 (identifier),
                PRIMARY KEY(uuid)
            ) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE=utf8mb4_general_ci'
        );
        $this->addSql('ALTER TABLE user_token ADD CONSTRAINT FK_BDF55A638D93D649 FOREIGN KEY (user) REFERENCES `user` (uuid)');
        $this->addSql('ALTER TABLE user_token_refresh RENAME INDEX uniq_f02938b8c74f2195 TO UNIQ_60001428C74F2195');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof MySQL80Platform,
            sprintf('Migration can only be executed safely on "%s".', MySQL80Platform::class)
        );

        $this->abortIf(
            !$schema->hasTable('user_token'),
            sprintf('Migration cannot be executed. Table "%s" is not exists.', 'user_token')
        );

        $this->addSql('ALTER TABLE user_token DROP FOREIGN KEY FK_BDF55A638D93D649');
        $this->addSql('DROP TABLE user_token');
        $this->addSql('ALTER TABLE user_token_refresh RENAME INDEX uniq_60001428c74f2195 TO UNIQ_F02938B8C74F2195');
    }
}
