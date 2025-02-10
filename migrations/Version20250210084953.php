<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210084953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_activity (tag_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_9A457281BAD26311 (tag_id), INDEX IDX_9A45728181C06096 (activity_id), PRIMARY KEY(tag_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_event (tag_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_194213A1BAD26311 (tag_id), INDEX IDX_194213A171F7E88B (event_id), PRIMARY KEY(tag_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_offer (tag_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_B3A9E38BAD26311 (tag_id), INDEX IDX_B3A9E3853C674EE (offer_id), PRIMARY KEY(tag_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_partner (tag_id INT NOT NULL, partner_id INT NOT NULL, INDEX IDX_31A13BCBAD26311 (tag_id), INDEX IDX_31A13BC9393F8FE (partner_id), PRIMARY KEY(tag_id, partner_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_activity ADD CONSTRAINT FK_9A457281BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_activity ADD CONSTRAINT FK_9A45728181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_event ADD CONSTRAINT FK_194213A1BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_event ADD CONSTRAINT FK_194213A171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_offer ADD CONSTRAINT FK_B3A9E38BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_offer ADD CONSTRAINT FK_B3A9E3853C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_partner ADD CONSTRAINT FK_31A13BCBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_partner ADD CONSTRAINT FK_31A13BC9393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_activity DROP FOREIGN KEY FK_9A457281BAD26311');
        $this->addSql('ALTER TABLE tag_activity DROP FOREIGN KEY FK_9A45728181C06096');
        $this->addSql('ALTER TABLE tag_event DROP FOREIGN KEY FK_194213A1BAD26311');
        $this->addSql('ALTER TABLE tag_event DROP FOREIGN KEY FK_194213A171F7E88B');
        $this->addSql('ALTER TABLE tag_offer DROP FOREIGN KEY FK_B3A9E38BAD26311');
        $this->addSql('ALTER TABLE tag_offer DROP FOREIGN KEY FK_B3A9E3853C674EE');
        $this->addSql('ALTER TABLE tag_partner DROP FOREIGN KEY FK_31A13BCBAD26311');
        $this->addSql('ALTER TABLE tag_partner DROP FOREIGN KEY FK_31A13BC9393F8FE');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_activity');
        $this->addSql('DROP TABLE tag_event');
        $this->addSql('DROP TABLE tag_offer');
        $this->addSql('DROP TABLE tag_partner');
    }
}
