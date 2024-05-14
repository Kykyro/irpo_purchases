<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503065039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE zone_group (id INT AUTO_INCREMENT NOT NULL, sheet_workzone_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, workplaces INT DEFAULT NULL, INDEX IDX_CBEEEB635F971027 (sheet_workzone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE zone_group ADD CONSTRAINT FK_CBEEEB635F971027 FOREIGN KEY (sheet_workzone_id) REFERENCES sheet_workzone (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE zone_group DROP FOREIGN KEY FK_CBEEEB635F971027');
        $this->addSql('DROP TABLE zone_group');

    }
}
