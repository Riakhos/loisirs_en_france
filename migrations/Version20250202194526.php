<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202194526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exclusive ADD exclusives_id INT DEFAULT NULL, ADD discount_percentage DOUBLE PRECISION NOT NULL, DROP price, DROP tva');
        $this->addSql('ALTER TABLE exclusive ADD CONSTRAINT FK_12318FCD6E5D06C FOREIGN KEY (exclusives_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_12318FCD6E5D06C ON exclusive (exclusives_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exclusive DROP FOREIGN KEY FK_12318FCD6E5D06C');
        $this->addSql('DROP INDEX IDX_12318FCD6E5D06C ON exclusive');
        $this->addSql('ALTER TABLE exclusive ADD tva DOUBLE PRECISION NOT NULL, DROP exclusives_id, CHANGE discount_percentage price DOUBLE PRECISION NOT NULL');
    }
}
