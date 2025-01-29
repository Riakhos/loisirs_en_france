<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129220644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trend (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, subcategory_id INT DEFAULT NULL, activity_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_F4FB33A612469DE2 (category_id), INDEX IDX_F4FB33A65DC6FE57 (subcategory_id), INDEX IDX_F4FB33A681C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trend ADD CONSTRAINT FK_F4FB33A612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE trend ADD CONSTRAINT FK_F4FB33A65DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id)');
        $this->addSql('ALTER TABLE trend ADD CONSTRAINT FK_F4FB33A681C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE activity CHANGE image1 image1 VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trend DROP FOREIGN KEY FK_F4FB33A612469DE2');
        $this->addSql('ALTER TABLE trend DROP FOREIGN KEY FK_F4FB33A65DC6FE57');
        $this->addSql('ALTER TABLE trend DROP FOREIGN KEY FK_F4FB33A681C06096');
        $this->addSql('DROP TABLE trend');
        $this->addSql('ALTER TABLE activity CHANGE image1 image1 VARCHAR(255) NOT NULL');
    }
}
