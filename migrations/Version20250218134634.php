<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218134634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD time TIME NOT NULL, CHANGE partner_name partner_name VARCHAR(255) DEFAULT NULL, CHANGE activity_name activity_name VARCHAR(255) DEFAULT NULL, CHANGE offer_name offer_name VARCHAR(255) DEFAULT NULL, CHANGE exclusive_name exclusive_name VARCHAR(255) DEFAULT NULL, CHANGE event_name event_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE order_detail CHANGE activity_name activity_name VARCHAR(255) DEFAULT NULL, CHANGE event_name event_name VARCHAR(255) DEFAULT NULL, CHANGE offer_name offer_name VARCHAR(255) DEFAULT NULL, CHANGE activity_image activity_image VARCHAR(255) DEFAULT NULL, CHANGE event_image event_image VARCHAR(255) DEFAULT NULL, CHANGE offer_image offer_image VARCHAR(255) DEFAULT NULL, CHANGE activity_quantity activity_quantity INT DEFAULT NULL, CHANGE event_quantity event_quantity INT DEFAULT NULL, CHANGE offer_quantity offer_quantity INT DEFAULT NULL, CHANGE activity_price activity_price DOUBLE PRECISION DEFAULT NULL, CHANGE event_price event_price DOUBLE PRECISION DEFAULT NULL, CHANGE offer_price offer_price DOUBLE PRECISION DEFAULT NULL, CHANGE activity_tva activity_tva DOUBLE PRECISION DEFAULT NULL, CHANGE event_tva event_tva DOUBLE PRECISION DEFAULT NULL, CHANGE offer_tva offer_tva DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP time, CHANGE partner_name partner_name VARCHAR(255) NOT NULL, CHANGE activity_name activity_name VARCHAR(255) NOT NULL, CHANGE offer_name offer_name VARCHAR(255) NOT NULL, CHANGE exclusive_name exclusive_name VARCHAR(255) NOT NULL, CHANGE event_name event_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_detail CHANGE activity_name activity_name VARCHAR(255) NOT NULL, CHANGE event_name event_name VARCHAR(255) NOT NULL, CHANGE offer_name offer_name VARCHAR(255) NOT NULL, CHANGE activity_image activity_image VARCHAR(255) NOT NULL, CHANGE event_image event_image VARCHAR(255) NOT NULL, CHANGE offer_image offer_image VARCHAR(255) NOT NULL, CHANGE activity_quantity activity_quantity INT NOT NULL, CHANGE event_quantity event_quantity INT NOT NULL, CHANGE offer_quantity offer_quantity VARCHAR(255) NOT NULL, CHANGE activity_price activity_price DOUBLE PRECISION NOT NULL, CHANGE event_price event_price DOUBLE PRECISION NOT NULL, CHANGE offer_price offer_price DOUBLE PRECISION NOT NULL, CHANGE activity_tva activity_tva DOUBLE PRECISION NOT NULL, CHANGE event_tva event_tva DOUBLE PRECISION NOT NULL, CHANGE offer_tva offer_tva DOUBLE PRECISION NOT NULL');
    }
}
