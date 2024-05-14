<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503065320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE workzone_equipment ADD zone_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE workzone_equipment ADD CONSTRAINT FK_B223BFECD6E855DA FOREIGN KEY (zone_group_id) REFERENCES zone_group (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('DROP INDEX IDX_B223BFECD6E855DA ON workzone_equipment');
        $this->addSql('ALTER TABLE workzone_equipment DROP zone_group_id');

    }
}
