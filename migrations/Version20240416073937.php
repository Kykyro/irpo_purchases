<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416073937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE cofinancing_scenario_file ADD cofinancing_scenario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cofinancing_scenario_file ADD CONSTRAINT FK_A24EFB70CF01DC08 FOREIGN KEY (cofinancing_scenario_id) REFERENCES cofinancing_scenario (id)');
        $this->addSql('CREATE INDEX IDX_A24EFB70CF01DC08 ON cofinancing_scenario_file (cofinancing_scenario_id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE cofinancing_scenario_file DROP FOREIGN KEY FK_A24EFB70CF01DC08');
        $this->addSql('DROP INDEX IDX_A24EFB70CF01DC08 ON cofinancing_scenario_file');
        $this->addSql('ALTER TABLE cofinancing_scenario_file DROP cofinancing_scenario_id');

    }
}
