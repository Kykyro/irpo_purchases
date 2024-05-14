<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122073623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE zone_requirements (id INT AUTO_INCREMENT NOT NULL, zone_id INT DEFAULT NULL, area DOUBLE PRECISION DEFAULT NULL, lighting VARCHAR(255) DEFAULT NULL, internet VARCHAR(255) DEFAULT NULL, electricity VARCHAR(255) DEFAULT NULL, ground_loop VARCHAR(255) DEFAULT NULL, floor VARCHAR(255) DEFAULT NULL, water VARCHAR(255) DEFAULT NULL, compressed_air VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_AA7E33799F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE zone_requirements ADD CONSTRAINT FK_AA7E33799F2C3FAB FOREIGN KEY (zone_id) REFERENCES sheet_workzone (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE zone_requirements DROP FOREIGN KEY FK_AA7E33799F2C3FAB');
        $this->addSql('DROP TABLE zone_requirements');

    }
}
