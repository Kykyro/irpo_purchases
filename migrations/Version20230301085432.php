<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301085432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorites_clusters ADD cluster_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favorites_clusters ADD CONSTRAINT FK_5D386E93C36A3328 FOREIGN KEY (cluster_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5D386E93C36A3328 ON favorites_clusters (cluster_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorites_clusters DROP FOREIGN KEY FK_5D386E93C36A3328');
        $this->addSql('DROP INDEX IDX_5D386E93C36A3328 ON favorites_clusters');
        $this->addSql('ALTER TABLE favorites_clusters DROP cluster_id');
    }
}
