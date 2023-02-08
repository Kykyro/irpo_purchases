<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124091427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorites_clusters (id INT AUTO_INCREMENT NOT NULL, inspector_id_id INT DEFAULT NULL, cluster_id_id INT DEFAULT NULL, INDEX IDX_5D386E93733ADD1 (inspector_id_id), UNIQUE INDEX UNIQ_5D386E939133FDD (cluster_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorites_clusters ADD CONSTRAINT FK_5D386E93733ADD1 FOREIGN KEY (inspector_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorites_clusters ADD CONSTRAINT FK_5D386E939133FDD FOREIGN KEY (cluster_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE favorites_clusters');
    }
}
