<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416081507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE cofinancing_scenario ADD employers_funds NUMERIC(20, 2) DEFAULT NULL, ADD region_funds NUMERIC(20, 2) DEFAULT NULL, ADD education_funds NUMERIC(20, 2) DEFAULT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE cofinancing_scenario DROP employers_funds, DROP region_funds, DROP education_funds');

    }
}
