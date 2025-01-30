<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130001240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, subcategory_id INT DEFAULT NULL, activity_id INT DEFAULT NULL, eventstrend_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, date_start DATE NOT NULL, date_stop DATE NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA712469DE2 (category_id), INDEX IDX_3BAE0AA75DC6FE57 (subcategory_id), INDEX IDX_3BAE0AA781C06096 (activity_id), INDEX IDX_3BAE0AA7479932D5 (eventstrend_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eventstrend (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exclusive (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, subcategory_id INT DEFAULT NULL, activity_id INT DEFAULT NULL, eventstrend_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_start DATE NOT NULL, date_stop DATE NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_12318FC12469DE2 (category_id), INDEX IDX_12318FC5DC6FE57 (subcategory_id), INDEX IDX_12318FC81C06096 (activity_id), INDEX IDX_12318FC479932D5 (eventstrend_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, eventstrend_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_29D6873E12469DE2 (category_id), INDEX IDX_29D6873E479932D5 (eventstrend_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA75DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA781C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7479932D5 FOREIGN KEY (eventstrend_id) REFERENCES eventstrend (id)');
        $this->addSql('ALTER TABLE exclusive ADD CONSTRAINT FK_12318FC12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE exclusive ADD CONSTRAINT FK_12318FC5DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id)');
        $this->addSql('ALTER TABLE exclusive ADD CONSTRAINT FK_12318FC81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE exclusive ADD CONSTRAINT FK_12318FC479932D5 FOREIGN KEY (eventstrend_id) REFERENCES eventstrend (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E479932D5 FOREIGN KEY (eventstrend_id) REFERENCES eventstrend (id)');
        $this->addSql('ALTER TABLE trend ADD eventstrend_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trend ADD CONSTRAINT FK_F4FB33A6479932D5 FOREIGN KEY (eventstrend_id) REFERENCES eventstrend (id)');
        $this->addSql('CREATE INDEX IDX_F4FB33A6479932D5 ON trend (eventstrend_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trend DROP FOREIGN KEY FK_F4FB33A6479932D5');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA712469DE2');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA75DC6FE57');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA781C06096');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7479932D5');
        $this->addSql('ALTER TABLE exclusive DROP FOREIGN KEY FK_12318FC12469DE2');
        $this->addSql('ALTER TABLE exclusive DROP FOREIGN KEY FK_12318FC5DC6FE57');
        $this->addSql('ALTER TABLE exclusive DROP FOREIGN KEY FK_12318FC81C06096');
        $this->addSql('ALTER TABLE exclusive DROP FOREIGN KEY FK_12318FC479932D5');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E12469DE2');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E479932D5');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE eventstrend');
        $this->addSql('DROP TABLE exclusive');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP INDEX IDX_F4FB33A6479932D5 ON trend');
        $this->addSql('ALTER TABLE trend DROP eventstrend_id');
    }
}
