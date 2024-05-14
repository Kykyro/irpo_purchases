<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415094850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE procurement_procedures_zone_infrastructure_sheet (procurement_procedures_id INT NOT NULL, zone_infrastructure_sheet_id INT NOT NULL, INDEX IDX_7CA3D96BCC7A35DD (procurement_procedures_id), INDEX IDX_7CA3D96B8AA5DBF5 (zone_infrastructure_sheet_id), PRIMARY KEY(procurement_procedures_id, zone_infrastructure_sheet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE procurement_procedures_zone_infrastructure_sheet ADD CONSTRAINT FK_7CA3D96BCC7A35DD FOREIGN KEY (procurement_procedures_id) REFERENCES procurement_procedures (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE procurement_procedures_zone_infrastructure_sheet ADD CONSTRAINT FK_7CA3D96B8AA5DBF5 FOREIGN KEY (zone_infrastructure_sheet_id) REFERENCES zone_infrastructure_sheet (id) ON DELETE CASCADE');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures_zone_infrastructure_sheet DROP FOREIGN KEY FK_7CA3D96BCC7A35DD');
        $this->addSql('ALTER TABLE procurement_procedures_zone_infrastructure_sheet DROP FOREIGN KEY FK_7CA3D96B8AA5DBF5');
        $this->addSql('DROP TABLE procurement_procedures_zone_infrastructure_sheet');

    }
}
