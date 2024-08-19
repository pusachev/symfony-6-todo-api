<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240819143510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ALTER COLUMN created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING created_at::timestamp(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE task ALTER COLUMN updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING updated_at::timestamp(0) WITHOUT TIME ZONE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE task ALTER created_at TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE task ALTER updated_at TYPE VARCHAR(255)');
    }
}
