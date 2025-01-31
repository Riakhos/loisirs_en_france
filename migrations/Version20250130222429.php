<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130222429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA781C06096');
        $this->addSql('DROP INDEX IDX_3BAE0AA781C06096 ON event');
        $this->addSql('ALTER TABLE event ADD image1 VARCHAR(255) NOT NULL, ADD image2 VARCHAR(255) NOT NULL, ADD image3 VARCHAR(255) NOT NULL, DROP activity_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD activity_id INT DEFAULT NULL, DROP image1, DROP image2, DROP image3');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA781C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA781C06096 ON event (activity_id)');
    }
}
