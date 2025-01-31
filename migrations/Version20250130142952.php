<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130142952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE exclusive CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE offer CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE trend ADD description LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE exclusive CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE offer CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE trend DROP description');
    }
}
