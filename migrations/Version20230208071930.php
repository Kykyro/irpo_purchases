<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208071930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures ADD fact_federal_funds NUMERIC(20, 2) DEFAULT NULL, ADD fact_funds_of_subject NUMERIC(20, 2) DEFAULT NULL, ADD fact_employers_funds NUMERIC(20, 2) DEFAULT NULL, ADD fact_funds_of_educational_org NUMERIC(20, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures DROP fact_federal_funds, DROP fact_funds_of_subject, DROP fact_employers_funds, DROP fact_funds_of_educational_org');
    }
}
