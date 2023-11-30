<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016094048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE zone_remark (id INT AUTO_INCREMENT NOT NULL, zone_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_DED9F04B9F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE zone_remark ADD CONSTRAINT FK_DED9F04B9F2C3FAB FOREIGN KEY (zone_id) REFERENCES cluster_zone (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE zone_remark DROP FOREIGN KEY FK_DED9F04B9F2C3FAB');
        $this->addSql('DROP TABLE zone_remark');

    }
}
