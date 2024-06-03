<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603142647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE building DROP INDEX IDX_E16F61D48FBAA222');
        $this->addSql('ALTER TABLE building DROP INDEX IDX_E16F61D4D4872639');
        $this->addSql('ALTER TABLE building DROP INDEX IDX_E16F61D432C8A3DE');
        $this->addSql('ALTER TABLE building DROP INDEX IDX_E16F61D4F28401B9');

        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D4F28401B9 FOREIGN KEY (building_type_id) REFERENCES building_type (id)');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D48FBAA222 FOREIGN KEY (building_category_id) REFERENCES building_category (id)');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D4D4872639 FOREIGN KEY (building_priority_id) REFERENCES building_priority (id)');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D432C8A3DE FOREIGN KEY (organization_id) REFERENCES prof_edu_org (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D4F28401B9');
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D48FBAA222');
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D4D4872639');
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D432C8A3DE');
    }
}
