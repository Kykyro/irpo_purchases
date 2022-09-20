<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220920115557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ugps (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE infrastructure_sheet_files ADD ugps_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE infrastructure_sheet_files ADD CONSTRAINT FK_7933BE312F95EC8F FOREIGN KEY (ugps_id) REFERENCES ugps (id)');
        $this->addSql('CREATE INDEX IDX_7933BE312F95EC8F ON infrastructure_sheet_files (ugps_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infrastructure_sheet_files DROP FOREIGN KEY FK_7933BE312F95EC8F');
        $this->addSql('DROP TABLE ugps');
        $this->addSql('DROP INDEX IDX_7933BE312F95EC8F ON infrastructure_sheet_files');
        $this->addSql('ALTER TABLE infrastructure_sheet_files DROP ugps_id');
    }
}
