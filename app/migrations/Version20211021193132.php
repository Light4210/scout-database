<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021193132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE struct (id INT AUTO_INCREMENT NOT NULL, sheaf_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, latitude INT DEFAULT NULL, longitude INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_BA92D72E84823F71 (sheaf_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, struct_id INT DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, middle_name VARCHAR(255) NOT NULL, date_of_birth DATE DEFAULT NULL, phone_number BIGINT DEFAULT NULL, status VARCHAR(255) NOT NULL, ministry VARCHAR(255) DEFAULT NULL, deal_scan VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, role VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64984EA0FD0 (struct_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE struct ADD CONSTRAINT FK_BA92D72E84823F71 FOREIGN KEY (sheaf_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64984EA0FD0 FOREIGN KEY (struct_id) REFERENCES struct (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64984EA0FD0');
        $this->addSql('ALTER TABLE struct DROP FOREIGN KEY FK_BA92D72E84823F71');
        $this->addSql('DROP TABLE struct');
        $this->addSql('DROP TABLE user');
    }
}
