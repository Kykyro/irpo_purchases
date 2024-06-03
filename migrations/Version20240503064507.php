<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503064507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sheet_workzone DROP fgos');
        $this->addSql('ALTER TABLE sheet_workzone RENAME INDEX fk_22b23d45a76ed395 TO IDX_22B23D45A76ED395');
//        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');

        $this->addSql('ALTER TABLE workzone_equipment DROP FOREIGN KEY FK_B223BFEC8B1206A5');
        $this->addSql('DROP INDEX IDX_B223BFEC8B1206A5 ON workzone_equipment');
        $this->addSql('ALTER TABLE workzone_equipment DROP sheet_id, DROP zone_group');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE sheet_workzone ADD fgos VARCHAR(300) DEFAULT NULL');
        $this->addSql('ALTER TABLE sheet_workzone RENAME INDEX idx_22b23d45a76ed395 TO FK_22B23D45A76ED395');

        $this->addSql('ALTER TABLE workzone_equipment ADD sheet_id INT DEFAULT NULL, ADD zone_group VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE workzone_equipment ADD CONSTRAINT FK_B223BFEC8B1206A5 FOREIGN KEY (sheet_id) REFERENCES sheet_workzone (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B223BFEC8B1206A5 ON workzone_equipment (sheet_id)');

    }
}
