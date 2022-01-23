<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211010232052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paiment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, client_id INT DEFAULT NULL, procedur_id INT DEFAULT NULL, devi_id INT DEFAULT NULL, INDEX IDX_DC8138FA76ED395 (user_id), INDEX IDX_DC8138F19EB6921 (client_id), INDEX IDX_DC8138F99486A13 (procedur_id), INDEX IDX_DC8138F131098A5 (devi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paiment ADD CONSTRAINT FK_DC8138FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE paiment ADD CONSTRAINT FK_DC8138F19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE paiment ADD CONSTRAINT FK_DC8138F99486A13 FOREIGN KEY (procedur_id) REFERENCES `procedure` (id)');
        $this->addSql('ALTER TABLE paiment ADD CONSTRAINT FK_DC8138F131098A5 FOREIGN KEY (devi_id) REFERENCES devis (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE paiment');
    }
}
