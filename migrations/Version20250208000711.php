<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208000711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD trend_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A65B0AAB2 FOREIGN KEY (trend_id) REFERENCES trend (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A65B0AAB2 ON activity (trend_id)');
        $this->addSql('ALTER TABLE trend DROP FOREIGN KEY FK_F4FB33A681C06096');
        $this->addSql('DROP INDEX IDX_F4FB33A681C06096 ON trend');
        $this->addSql('ALTER TABLE trend DROP activity_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A65B0AAB2');
        $this->addSql('DROP INDEX IDX_AC74095A65B0AAB2 ON activity');
        $this->addSql('ALTER TABLE activity DROP trend_id');
        $this->addSql('ALTER TABLE trend ADD activity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trend ADD CONSTRAINT FK_F4FB33A681C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_F4FB33A681C06096 ON trend (activity_id)');
    }
}
