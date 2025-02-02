<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250201232132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity CHANGE category_id category_id INT NOT NULL, CHANGE subcategory_id subcategory_id INT NOT NULL');
        $this->addSql('ALTER TABLE exclusive DROP FOREIGN KEY FK_12318FC81C06096');
        $this->addSql('DROP INDEX IDX_12318FC81C06096 ON exclusive');
        $this->addSql('ALTER TABLE exclusive DROP activity_id');
        $this->addSql('ALTER TABLE trend CHANGE activity_id activity_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity CHANGE category_id category_id INT DEFAULT NULL, CHANGE subcategory_id subcategory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exclusive ADD activity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exclusive ADD CONSTRAINT FK_12318FC81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_12318FC81C06096 ON exclusive (activity_id)');
        $this->addSql('ALTER TABLE trend CHANGE activity_id activity_id INT DEFAULT NULL');
    }
}
