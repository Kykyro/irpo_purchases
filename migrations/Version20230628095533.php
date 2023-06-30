<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230628095533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE building ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D432C8A3DE FOREIGN KEY (organization_id) REFERENCES prof_edu_org (id)');
        $this->addSql('CREATE INDEX IDX_E16F61D432C8A3DE ON building (organization_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D432C8A3DE');
        $this->addSql('DROP INDEX IDX_E16F61D432C8A3DE ON building');
        $this->addSql('ALTER TABLE building DROP organization_id');
    }
}
