<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301084152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorites_clusters ADD cluster_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favorites_clusters ADD CONSTRAINT FK_5D386E939133FDD FOREIGN KEY (cluster_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D386E939133FDD ON favorites_clusters (cluster_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorites_clusters DROP FOREIGN KEY FK_5D386E939133FDD');
        $this->addSql('DROP INDEX UNIQ_5D386E939133FDD ON favorites_clusters');
        $this->addSql('ALTER TABLE favorites_clusters DROP cluster_id_id');
    }
}
