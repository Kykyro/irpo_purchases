<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230829083747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE employers ADD inn VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD alt_name VARCHAR(255) DEFAULT NULL, ADD short_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE employers DROP inn, DROP city, DROP alt_name, DROP short_name');
    }
}
