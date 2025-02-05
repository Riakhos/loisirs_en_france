<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203162805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD exclusive_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AFAE3A6AE FOREIGN KEY (exclusive_id) REFERENCES exclusive (id)');
        $this->addSql('CREATE INDEX IDX_AC74095AFAE3A6AE ON activity (exclusive_id)');
        $this->addSql('ALTER TABLE exclusive DROP FOREIGN KEY FK_12318FCD6E5D06C');
        $this->addSql('DROP INDEX IDX_12318FCD6E5D06C ON exclusive');
        $this->addSql('ALTER TABLE exclusive DROP exclusives_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AFAE3A6AE');
        $this->addSql('DROP INDEX IDX_AC74095AFAE3A6AE ON activity');
        $this->addSql('ALTER TABLE activity DROP exclusive_id');
        $this->addSql('ALTER TABLE exclusive ADD exclusives_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exclusive ADD CONSTRAINT FK_12318FCD6E5D06C FOREIGN KEY (exclusives_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_12318FCD6E5D06C ON exclusive (exclusives_id)');
    }
}
