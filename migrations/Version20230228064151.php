<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228064151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE zone_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cluster_zone ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cluster_zone ADD CONSTRAINT FK_2B0BDA47C54C8C93 FOREIGN KEY (type_id) REFERENCES zone_type (id)');
        $this->addSql('CREATE INDEX IDX_2B0BDA47C54C8C93 ON cluster_zone (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cluster_zone DROP FOREIGN KEY FK_2B0BDA47C54C8C93');
        $this->addSql('DROP TABLE zone_type');
        $this->addSql('DROP INDEX IDX_2B0BDA47C54C8C93 ON cluster_zone');
        $this->addSql('ALTER TABLE cluster_zone DROP type_id');
    }
}
