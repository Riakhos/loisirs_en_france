<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217233947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, create_at DATETIME NOT NULL, state INT NOT NULL, partner_name VARCHAR(255) NOT NULL, activity_name VARCHAR(255) NOT NULL, offer_name VARCHAR(255) NOT NULL, exclusive_name VARCHAR(255) NOT NULL, event_name VARCHAR(255) NOT NULL, cart_price DOUBLE PRECISION NOT NULL, date_start DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_detail (id INT AUTO_INCREMENT NOT NULL, my_order_id INT DEFAULT NULL, activity_name VARCHAR(255) NOT NULL, event_name VARCHAR(255) NOT NULL, offer_name VARCHAR(255) NOT NULL, activity_image VARCHAR(255) NOT NULL, event_image VARCHAR(255) NOT NULL, offer_image VARCHAR(255) NOT NULL, activity_quantity INT NOT NULL, event_quantity INT NOT NULL, offer_quantity VARCHAR(255) NOT NULL, activity_price DOUBLE PRECISION NOT NULL, event_price DOUBLE PRECISION NOT NULL, offer_price DOUBLE PRECISION NOT NULL, activity_tva DOUBLE PRECISION NOT NULL, event_tva DOUBLE PRECISION NOT NULL, offer_tva DOUBLE PRECISION NOT NULL, INDEX IDX_ED896F46BFCDF877 (my_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46BFCDF877 FOREIGN KEY (my_order_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46BFCDF877');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_detail');
    }
}
