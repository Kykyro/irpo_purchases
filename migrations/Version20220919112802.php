<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220919112802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE industry (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE infrastructure_sheet_files (id INT AUTO_INCREMENT NOT NULL, industry_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, INDEX IDX_7933BE312B19A734 (industry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE infrastructure_sheet_files ADD CONSTRAINT FK_7933BE312B19A734 FOREIGN KEY (industry_id) REFERENCES industry (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infrastructure_sheet_files DROP FOREIGN KEY FK_7933BE312B19A734');
        $this->addSql('DROP TABLE industry');
        $this->addSql('DROP TABLE infrastructure_sheet_files');
    }
}
