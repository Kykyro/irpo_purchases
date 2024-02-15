<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214081936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE workzone_equipment ADD unit_id INT DEFAULT NULL');

        $this->addSql('ALTER TABLE workzone_equipment ADD CONSTRAINT FK_B223BFECF8BD700D FOREIGN KEY (unit_id) REFERENCES workzone_equpment_unit (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs


        $this->addSql('ALTER TABLE workzone_equipment DROP FOREIGN KEY FK_B223BFECF8BD700D');

        $this->addSql('ALTER TABLE workzone_equipment DROP unit_id');

    }
}
