<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250131121906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA76E20173B');
        $this->addSql('ALTER TABLE exclusive DROP FOREIGN KEY FK_12318FC6E20173B');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E6E20173B');
        $this->addSql('ALTER TABLE trend DROP FOREIGN KEY FK_F4FB33A66E20173B');
        $this->addSql('ALTER TABLE subtrend DROP FOREIGN KEY FK_8E48DC7C479932D5');
        $this->addSql('DROP TABLE subtrend');
        $this->addSql('DROP INDEX IDX_3BAE0AA76E20173B ON event');
        $this->addSql('ALTER TABLE event DROP subtrend_id');
        $this->addSql('DROP INDEX IDX_12318FC6E20173B ON exclusive');
        $this->addSql('ALTER TABLE exclusive DROP subtrend_id');
        $this->addSql('DROP INDEX IDX_29D6873E6E20173B ON offer');
        $this->addSql('ALTER TABLE offer DROP subtrend_id');
        $this->addSql('DROP INDEX IDX_F4FB33A66E20173B ON trend');
        $this->addSql('ALTER TABLE trend DROP subtrend_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subtrend (id INT AUTO_INCREMENT NOT NULL, eventstrend_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_8E48DC7C479932D5 (eventstrend_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE subtrend ADD CONSTRAINT FK_8E48DC7C479932D5 FOREIGN KEY (eventstrend_id) REFERENCES eventstrend (id)');
        $this->addSql('ALTER TABLE event ADD subtrend_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA76E20173B FOREIGN KEY (subtrend_id) REFERENCES subtrend (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA76E20173B ON event (subtrend_id)');
        $this->addSql('ALTER TABLE exclusive ADD subtrend_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exclusive ADD CONSTRAINT FK_12318FC6E20173B FOREIGN KEY (subtrend_id) REFERENCES subtrend (id)');
        $this->addSql('CREATE INDEX IDX_12318FC6E20173B ON exclusive (subtrend_id)');
        $this->addSql('ALTER TABLE offer ADD subtrend_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E6E20173B FOREIGN KEY (subtrend_id) REFERENCES subtrend (id)');
        $this->addSql('CREATE INDEX IDX_29D6873E6E20173B ON offer (subtrend_id)');
        $this->addSql('ALTER TABLE trend ADD subtrend_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trend ADD CONSTRAINT FK_F4FB33A66E20173B FOREIGN KEY (subtrend_id) REFERENCES subtrend (id)');
        $this->addSql('CREATE INDEX IDX_F4FB33A66E20173B ON trend (subtrend_id)');
    }
}
