<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211028211607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attachments (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(100) NOT NULL, content VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_6000B0D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE struct (id INT AUTO_INCREMENT NOT NULL, sheaf_id INT DEFAULT NULL, type VARCHAR(20) NOT NULL, name VARCHAR(50) NOT NULL, city VARCHAR(35) NOT NULL, address VARCHAR(95) NOT NULL, latitude NUMERIC(10, 0) DEFAULT NULL, longitude NUMERIC(10, 0) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_BA92D72E84823F71 (sheaf_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transfer_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, target_struct_id INT NOT NULL, current_struct_id INT NOT NULL, status VARCHAR(50) NOT NULL, INDEX IDX_B566FA2BA76ED395 (user_id), INDEX IDX_B566FA2BB05E7DFC (target_struct_id), INDEX IDX_B566FA2B5FCCBE7C (current_struct_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, deal_scan_id INT DEFAULT NULL, photo_id INT DEFAULT NULL, struct_id INT DEFAULT NULL, email VARCHAR(62) NOT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, name VARCHAR(50) NOT NULL, surname VARCHAR(50) NOT NULL, middle_name VARCHAR(50) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, phone_number VARCHAR(15) DEFAULT NULL, status VARCHAR(20) DEFAULT \'active\' NOT NULL, ministry VARCHAR(50) DEFAULT NULL, address VARCHAR(95) DEFAULT NULL, role VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649CC3D225B (deal_scan_id), UNIQUE INDEX UNIQ_8D93D6497E9E4C8C (photo_id), INDEX IDX_8D93D64984EA0FD0 (struct_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE struct ADD CONSTRAINT FK_BA92D72E84823F71 FOREIGN KEY (sheaf_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transfer_user ADD CONSTRAINT FK_B566FA2BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transfer_user ADD CONSTRAINT FK_B566FA2BB05E7DFC FOREIGN KEY (target_struct_id) REFERENCES struct (id)');
        $this->addSql('ALTER TABLE transfer_user ADD CONSTRAINT FK_B566FA2B5FCCBE7C FOREIGN KEY (current_struct_id) REFERENCES struct (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CC3D225B FOREIGN KEY (deal_scan_id) REFERENCES attachments (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497E9E4C8C FOREIGN KEY (photo_id) REFERENCES attachments (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64984EA0FD0 FOREIGN KEY (struct_id) REFERENCES struct (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CC3D225B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497E9E4C8C');
        $this->addSql('ALTER TABLE transfer_user DROP FOREIGN KEY FK_B566FA2BB05E7DFC');
        $this->addSql('ALTER TABLE transfer_user DROP FOREIGN KEY FK_B566FA2B5FCCBE7C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64984EA0FD0');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('ALTER TABLE struct DROP FOREIGN KEY FK_BA92D72E84823F71');
        $this->addSql('ALTER TABLE transfer_user DROP FOREIGN KEY FK_B566FA2BA76ED395');
        $this->addSql('DROP TABLE attachments');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE struct');
        $this->addSql('DROP TABLE transfer_user');
        $this->addSql('DROP TABLE user');
    }
}
