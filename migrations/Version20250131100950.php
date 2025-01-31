<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250131100950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subtrend (id INT AUTO_INCREMENT NOT NULL, eventstrend_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_8E48DC7C479932D5 (eventstrend_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subtrend ADD CONSTRAINT FK_8E48DC7C479932D5 FOREIGN KEY (eventstrend_id) REFERENCES eventstrend (id)');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA712469DE2');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA75DC6FE57');
        $this->addSql('DROP INDEX IDX_3BAE0AA712469DE2 ON event');
        $this->addSql('DROP INDEX IDX_3BAE0AA75DC6FE57 ON event');
        $this->addSql('ALTER TABLE event ADD subtrend_id INT DEFAULT NULL, DROP category_id, DROP subcategory_id');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA76E20173B FOREIGN KEY (subtrend_id) REFERENCES subtrend (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA76E20173B ON event (subtrend_id)');
        $this->addSql('ALTER TABLE exclusive DROP FOREIGN KEY FK_12318FC12469DE2');
        $this->addSql('ALTER TABLE exclusive DROP FOREIGN KEY FK_12318FC5DC6FE57');
        $this->addSql('DROP INDEX IDX_12318FC5DC6FE57 ON exclusive');
        $this->addSql('DROP INDEX IDX_12318FC12469DE2 ON exclusive');
        $this->addSql('ALTER TABLE exclusive ADD subtrend_id INT DEFAULT NULL, DROP category_id, DROP subcategory_id');
        $this->addSql('ALTER TABLE exclusive ADD CONSTRAINT FK_12318FC6E20173B FOREIGN KEY (subtrend_id) REFERENCES subtrend (id)');
        $this->addSql('CREATE INDEX IDX_12318FC6E20173B ON exclusive (subtrend_id)');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E12469DE2');
        $this->addSql('DROP INDEX IDX_29D6873E12469DE2 ON offer');
        $this->addSql('ALTER TABLE offer CHANGE category_id subtrend_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E6E20173B FOREIGN KEY (subtrend_id) REFERENCES subtrend (id)');
        $this->addSql('CREATE INDEX IDX_29D6873E6E20173B ON offer (subtrend_id)');
        $this->addSql('ALTER TABLE trend DROP FOREIGN KEY FK_F4FB33A65DC6FE57');
        $this->addSql('ALTER TABLE trend DROP FOREIGN KEY FK_F4FB33A612469DE2');
        $this->addSql('DROP INDEX IDX_F4FB33A612469DE2 ON trend');
        $this->addSql('DROP INDEX IDX_F4FB33A65DC6FE57 ON trend');
        $this->addSql('ALTER TABLE trend ADD subtrend_id INT DEFAULT NULL, DROP category_id, DROP subcategory_id');
        $this->addSql('ALTER TABLE trend ADD CONSTRAINT FK_F4FB33A66E20173B FOREIGN KEY (subtrend_id) REFERENCES subtrend (id)');
        $this->addSql('CREATE INDEX IDX_F4FB33A66E20173B ON trend (subtrend_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA76E20173B');
        $this->addSql('ALTER TABLE exclusive DROP FOREIGN KEY FK_12318FC6E20173B');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E6E20173B');
        $this->addSql('ALTER TABLE trend DROP FOREIGN KEY FK_F4FB33A66E20173B');
        $this->addSql('ALTER TABLE subtrend DROP FOREIGN KEY FK_8E48DC7C479932D5');
        $this->addSql('DROP TABLE subtrend');
        $this->addSql('DROP INDEX IDX_3BAE0AA76E20173B ON event');
        $this->addSql('ALTER TABLE event ADD subcategory_id INT DEFAULT NULL, CHANGE subtrend_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA75DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA712469DE2 ON event (category_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA75DC6FE57 ON event (subcategory_id)');
        $this->addSql('DROP INDEX IDX_12318FC6E20173B ON exclusive');
        $this->addSql('ALTER TABLE exclusive ADD subcategory_id INT DEFAULT NULL, CHANGE subtrend_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exclusive ADD CONSTRAINT FK_12318FC12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE exclusive ADD CONSTRAINT FK_12318FC5DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id)');
        $this->addSql('CREATE INDEX IDX_12318FC5DC6FE57 ON exclusive (subcategory_id)');
        $this->addSql('CREATE INDEX IDX_12318FC12469DE2 ON exclusive (category_id)');
        $this->addSql('DROP INDEX IDX_29D6873E6E20173B ON offer');
        $this->addSql('ALTER TABLE offer CHANGE subtrend_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_29D6873E12469DE2 ON offer (category_id)');
        $this->addSql('DROP INDEX IDX_F4FB33A66E20173B ON trend');
        $this->addSql('ALTER TABLE trend ADD subcategory_id INT DEFAULT NULL, CHANGE subtrend_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trend ADD CONSTRAINT FK_F4FB33A65DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id)');
        $this->addSql('ALTER TABLE trend ADD CONSTRAINT FK_F4FB33A612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_F4FB33A612469DE2 ON trend (category_id)');
        $this->addSql('CREATE INDEX IDX_F4FB33A65DC6FE57 ON trend (subcategory_id)');
    }
}
