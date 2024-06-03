<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603143625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE building RENAME INDEX fk_e16f61d4f28401b9 TO IDX_E16F61D4F28401B9');
        $this->addSql('ALTER TABLE building RENAME INDEX fk_e16f61d48fbaa222 TO IDX_E16F61D48FBAA222');
        $this->addSql('ALTER TABLE building RENAME INDEX fk_e16f61d4d4872639 TO IDX_E16F61D4D4872639');
        $this->addSql('ALTER TABLE building RENAME INDEX fk_e16f61d432c8a3de TO IDX_E16F61D432C8A3DE');


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE building RENAME INDEX idx_e16f61d4f28401b9 TO FK_E16F61D4F28401B9');
        $this->addSql('ALTER TABLE building RENAME INDEX idx_e16f61d48fbaa222 TO FK_E16F61D48FBAA222');
        $this->addSql('ALTER TABLE building RENAME INDEX idx_e16f61d4d4872639 TO FK_E16F61D4D4872639');
        $this->addSql('ALTER TABLE building RENAME INDEX idx_e16f61d432c8a3de TO FK_E16F61D432C8A3DE');
    }
}
