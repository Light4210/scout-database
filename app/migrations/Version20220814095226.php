<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220814095226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD ilustration_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CEFA110AD FOREIGN KEY (ilustration_id) REFERENCES attachments (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318CEFA110AD ON game (ilustration_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CEFA110AD');
        $this->addSql('DROP INDEX UNIQ_232B318CEFA110AD ON game');
        $this->addSql('ALTER TABLE game DROP ilustration_id');
    }
}
