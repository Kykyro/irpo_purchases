<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319082222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE workzone_equipment_dump (id INT AUTO_INCREMENT NOT NULL, equipment_id INT DEFAULT NULL, changes JSON DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5E8441AF517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE workzone_equipment_dump ADD CONSTRAINT FK_5E8441AF517FE9FE FOREIGN KEY (equipment_id) REFERENCES workzone_equipment (id)');
        $this->addSql('ALTER TABLE workzone_equipment ADD deleted TINYINT(1) DEFAULT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE workzone_equipment_dump DROP FOREIGN KEY FK_5E8441AF517FE9FE');
        $this->addSql('DROP TABLE workzone_equipment_dump');
        $this->addSql('ALTER TABLE workzone_equipment DROP deleted');

    }
}
