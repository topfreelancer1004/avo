<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210926214236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client RENAME INDEX idx_c74404559d86650f TO IDX_C7440455A76ED395');
        $this->addSql('ALTER TABLE user ADD status TINYINT(1) NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client RENAME INDEX idx_c7440455a76ed395 TO IDX_C74404559D86650F');
        $this->addSql('ALTER TABLE user DROP status');
    }
}
