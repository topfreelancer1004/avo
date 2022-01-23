<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211006221202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, procedur_id INT DEFAULT NULL, client_id INT DEFAULT NULL, aj_id INT DEFAULT NULL, created_at DATETIME NOT NULL, amount INT NOT NULL, paid TINYINT(1) NOT NULL, INDEX IDX_8B27C52BA76ED395 (user_id), INDEX IDX_8B27C52B99486A13 (procedur_id), INDEX IDX_8B27C52B19EB6921 (client_id), INDEX IDX_8B27C52BAFEBA2A5 (aj_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B99486A13 FOREIGN KEY (procedur_id) REFERENCES `procedure` (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BAFEBA2A5 FOREIGN KEY (aj_id) REFERENCES aj (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE devis');
    }
}
