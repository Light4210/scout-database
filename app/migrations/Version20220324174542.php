<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220324174542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attachments (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invite (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, expiration_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', role VARCHAR(100) NOT NULL, ministry VARCHAR(100) DEFAULT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, target_user_id INT DEFAULT NULL, from_user_id INT NOT NULL, to_user_id INT NOT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(500) DEFAULT NULL, type VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, is_read TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_BF5476CA6C066AFE (target_user_id), INDEX IDX_BF5476CA2130303A (from_user_id), INDEX IDX_BF5476CA29F6EE60 (to_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE struct (id INT AUTO_INCREMENT NOT NULL, sheaf_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(35) NOT NULL, address VARCHAR(95) NOT NULL, latitude NUMERIC(10, 0) DEFAULT NULL, longitude NUMERIC(10, 0) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_BA92D72E84823F71 (sheaf_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, deal_scan_id INT DEFAULT NULL, photo_id INT DEFAULT NULL, struct_id INT DEFAULT NULL, email VARCHAR(62) NOT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, name VARCHAR(50) NOT NULL, surname VARCHAR(50) NOT NULL, middle_name VARCHAR(50) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, phone_number VARCHAR(15) DEFAULT NULL, status VARCHAR(255) NOT NULL, ministry VARCHAR(255) DEFAULT NULL, address VARCHAR(95) DEFAULT NULL, role VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649CC3D225B (deal_scan_id), UNIQUE INDEX UNIQ_8D93D6497E9E4C8C (photo_id), INDEX IDX_8D93D64984EA0FD0 (struct_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA6C066AFE FOREIGN KEY (target_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA2130303A FOREIGN KEY (from_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA29F6EE60 FOREIGN KEY (to_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE struct ADD CONSTRAINT FK_BA92D72E84823F71 FOREIGN KEY (sheaf_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CC3D225B FOREIGN KEY (deal_scan_id) REFERENCES attachments (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497E9E4C8C FOREIGN KEY (photo_id) REFERENCES attachments (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64984EA0FD0 FOREIGN KEY (struct_id) REFERENCES struct (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CC3D225B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497E9E4C8C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64984EA0FD0');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA6C066AFE');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA2130303A');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA29F6EE60');
        $this->addSql('ALTER TABLE struct DROP FOREIGN KEY FK_BA92D72E84823F71');
        $this->addSql('DROP TABLE attachments');
        $this->addSql('DROP TABLE invite');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE struct');
        $this->addSql('DROP TABLE user');
    }
}
