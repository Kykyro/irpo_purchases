<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220803072023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures ADD initial_educational_org_funds NUMERIC(20, 3) DEFAULT NULL, ADD supplier_name VARCHAR(255) DEFAULT NULL, ADD supplier_inn VARCHAR(255) DEFAULT NULL, ADD supplier_kpp VARCHAR(255) NOT NULL, ADD fin_federal_funds NUMERIC(20, 3) DEFAULT NULL, ADD fin_funds_of_subject NUMERIC(20, 3) DEFAULT NULL, ADD fin_employers_funds NUMERIC(20, 3) DEFAULT NULL, ADD fin_funds_of_educational_org NUMERIC(20, 3) DEFAULT NULL, ADD publication_date DATE DEFAULT NULL, ADD deadline_date DATE DEFAULT NULL, ADD date_of_summing_up DATE DEFAULT NULL, ADD postponement_date DATE DEFAULT NULL, ADD postonement_comment LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures DROP initial_educational_org_funds, DROP supplier_name, DROP supplier_inn, DROP supplier_kpp, DROP fin_federal_funds, DROP fin_funds_of_subject, DROP fin_employers_funds, DROP fin_funds_of_educational_org, DROP publication_date, DROP deadline_date, DROP date_of_summing_up, DROP postponement_date, DROP postonement_comment');
    }
}
