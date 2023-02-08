<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230127064544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contract_status (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE procurement_procedures ADD conract_status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE procurement_procedures ADD CONSTRAINT FK_31045326FDC46870 FOREIGN KEY (conract_status_id) REFERENCES contract_status (id)');
        $this->addSql('CREATE INDEX IDX_31045326FDC46870 ON procurement_procedures (conract_status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures DROP FOREIGN KEY FK_31045326FDC46870');
        $this->addSql('DROP TABLE contract_status');
        $this->addSql('DROP INDEX IDX_31045326FDC46870 ON procurement_procedures');
        $this->addSql('ALTER TABLE procurement_procedures DROP conract_status_id');
    }
}
