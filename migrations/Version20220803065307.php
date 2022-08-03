<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220803065307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures ADD initial_federal_funds NUMERIC(20, 3) DEFAULT NULL, ADD initial_funds_of_subject NUMERIC(20, 3) DEFAULT NULL, ADD initial_employers_funds NUMERIC(20, 3) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures DROP initial_federal_funds, DROP initial_funds_of_subject, DROP initial_employers_funds');
    }
}
