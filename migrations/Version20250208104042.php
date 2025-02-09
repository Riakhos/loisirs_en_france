<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208104042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(10) NOT NULL, address VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AFAE3A6AE');
        $this->addSql('ALTER TABLE activity ADD partners_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095ABDE7F1C6 FOREIGN KEY (partners_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AFAE3A6AE FOREIGN KEY (exclusive_id) REFERENCES exclusive (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_AC74095ABDE7F1C6 ON activity (partners_id)');
        $this->addSql('ALTER TABLE event ADD partners_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7BDE7F1C6 FOREIGN KEY (partners_id) REFERENCES partner (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7BDE7F1C6 ON event (partners_id)');
        $this->addSql('ALTER TABLE offer ADD partners_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EBDE7F1C6 FOREIGN KEY (partners_id) REFERENCES partner (id)');
        $this->addSql('CREATE INDEX IDX_29D6873EBDE7F1C6 ON offer (partners_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095ABDE7F1C6');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7BDE7F1C6');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EBDE7F1C6');
        $this->addSql('DROP TABLE partner');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AFAE3A6AE');
        $this->addSql('DROP INDEX IDX_AC74095ABDE7F1C6 ON activity');
        $this->addSql('ALTER TABLE activity DROP partners_id');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AFAE3A6AE FOREIGN KEY (exclusive_id) REFERENCES exclusive (id)');
        $this->addSql('DROP INDEX IDX_3BAE0AA7BDE7F1C6 ON event');
        $this->addSql('ALTER TABLE event DROP partners_id');
        $this->addSql('DROP INDEX IDX_29D6873EBDE7F1C6 ON offer');
        $this->addSql('ALTER TABLE offer DROP partners_id');
    }
}
