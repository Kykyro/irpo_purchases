<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518120339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE readiness_map_saves (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(500) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(255) DEFAULT NULL, year INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contracting_tables ADD type VARCHAR(255) DEFAULT NULL, ADD year INT DEFAULT NULL, CHANGE name name VARCHAR(500) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE readiness_map_saves');
        $this->addSql('ALTER TABLE contracting_tables DROP type, DROP year, CHANGE name name VARCHAR(255) DEFAULT NULL');
    }
}
