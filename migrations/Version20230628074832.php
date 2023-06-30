<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230628074832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prof_edu_org (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, full_name VARCHAR(500) DEFAULT NULL, short_name VARCHAR(500) DEFAULT NULL, INDEX IDX_1FA1D7C798260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prof_edu_org ADD CONSTRAINT FK_1FA1D7C798260155 FOREIGN KEY (region_id) REFERENCES rf_subject (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prof_edu_org DROP FOREIGN KEY FK_1FA1D7C798260155');
        $this->addSql('DROP TABLE prof_edu_org');
    }
}
