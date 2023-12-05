<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231024111713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur ADD matchid_id INT DEFAULT NULL, DROP matchid');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C5430E5F3E FOREIGN KEY (matchid_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_FD71A9C5430E5F3E ON joueur (matchid_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C5430E5F3E');
        $this->addSql('DROP INDEX IDX_FD71A9C5430E5F3E ON joueur');
        $this->addSql('ALTER TABLE joueur ADD matchid INT NOT NULL, DROP matchid_id');
    }
}
