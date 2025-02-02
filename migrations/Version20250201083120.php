<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250201083120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offer_activity (offer_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_D509B93853C674EE (offer_id), INDEX IDX_D509B93881C06096 (activity_id), PRIMARY KEY(offer_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer_activity ADD CONSTRAINT FK_D509B93853C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_activity ADD CONSTRAINT FK_D509B93881C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer_activity DROP FOREIGN KEY FK_D509B93853C674EE');
        $this->addSql('ALTER TABLE offer_activity DROP FOREIGN KEY FK_D509B93881C06096');
        $this->addSql('DROP TABLE offer_activity');
    }
}
