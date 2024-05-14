<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502104042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE workzone_equipment DROP FOREIGN KEY FK_B223BFECF8BD700D');
        $this->addSql('DROP INDEX FK_B223BFECF8BD700D ON workzone_equipment');
        $this->addSql('ALTER TABLE workzone_equipment DROP unit_id, DROP type_id');
        $this->addSql('ALTER TABLE workzone_equipment ADD CONSTRAINT FK_B223BFEC8B1206A5 FOREIGN KEY (sheet_id) REFERENCES sheet_workzone (id)');
        $this->addSql('CREATE INDEX IDX_B223BFEC8B1206A5 ON workzone_equipment (sheet_id)');
        $this->addSql('ALTER TABLE zone_infrastructure_sheet ADD CONSTRAINT FK_4EDC9A8C9F2C3FAB FOREIGN KEY (zone_id) REFERENCES cluster_zone (id)');
        $this->addSql('CREATE INDEX IDX_4EDC9A8C9F2C3FAB ON zone_infrastructure_sheet (zone_id)');


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE workzone_equipment DROP FOREIGN KEY FK_B223BFEC8B1206A5');
        $this->addSql('DROP INDEX IDX_B223BFEC8B1206A5 ON workzone_equipment');
        $this->addSql('ALTER TABLE workzone_equipment ADD unit_id INT DEFAULT NULL, ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE workzone_equipment ADD CONSTRAINT FK_B223BFECF8BD700D FOREIGN KEY (unit_id) REFERENCES workzone_equpment_unit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_B223BFECF8BD700D ON workzone_equipment (unit_id)');
        $this->addSql('ALTER TABLE zone_infrastructure_sheet DROP FOREIGN KEY FK_4EDC9A8C9F2C3FAB');
        $this->addSql('DROP INDEX IDX_4EDC9A8C9F2C3FAB ON zone_infrastructure_sheet');
        $this->addSql('ALTER TABLE zone_repair DROP FOREIGN KEY FK_B1F71C53FF91AA43');

    }
}
