<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221125094818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures CHANGE initial_federal_funds initial_federal_funds NUMERIC(20, 2) DEFAULT NULL, CHANGE initial_funds_of_subject initial_funds_of_subject NUMERIC(20, 2) DEFAULT NULL, CHANGE initial_employers_funds initial_employers_funds NUMERIC(20, 2) DEFAULT NULL, CHANGE initial_educational_org_funds initial_educational_org_funds NUMERIC(20, 2) DEFAULT NULL, CHANGE fin_federal_funds fin_federal_funds NUMERIC(20, 2) DEFAULT NULL, CHANGE fin_funds_of_subject fin_funds_of_subject NUMERIC(20, 2) DEFAULT NULL, CHANGE fin_employers_funds fin_employers_funds NUMERIC(20, 2) DEFAULT NULL, CHANGE fin_funds_of_educational_org fin_funds_of_educational_org NUMERIC(20, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures CHANGE initial_federal_funds initial_federal_funds NUMERIC(20, 3) DEFAULT NULL, CHANGE initial_funds_of_subject initial_funds_of_subject NUMERIC(20, 3) DEFAULT NULL, CHANGE initial_employers_funds initial_employers_funds NUMERIC(20, 3) DEFAULT NULL, CHANGE initial_educational_org_funds initial_educational_org_funds NUMERIC(20, 3) DEFAULT NULL, CHANGE fin_federal_funds fin_federal_funds NUMERIC(20, 3) DEFAULT NULL, CHANGE fin_funds_of_subject fin_funds_of_subject NUMERIC(20, 3) DEFAULT NULL, CHANGE fin_employers_funds fin_employers_funds NUMERIC(20, 3) DEFAULT NULL, CHANGE fin_funds_of_educational_org fin_funds_of_educational_org NUMERIC(20, 3) DEFAULT NULL');
    }
}
