<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221080922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE zone_repair (id INT AUTO_INCREMENT NOT NULL, cluster_zone_id INT DEFAULT NULL, readiness INT DEFAULT NULL, end_date DATE DEFAULT NULL, comment LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_B1F71C53FF91AA43 (cluster_zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE zone_repair ADD CONSTRAINT FK_B1F71C53FF91AA43 FOREIGN KEY (cluster_zone_id) REFERENCES cluster_zone (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE zone_repair DROP FOREIGN KEY FK_B1F71C53FF91AA43');
        $this->addSql('DROP TABLE zone_repair');
    }
}
