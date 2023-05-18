<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230405101059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE readiness_map_archive ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE readiness_map_archive ADD CONSTRAINT FK_ED289F11A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_ED289F11A76ED395 ON readiness_map_archive (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE readiness_map_archive DROP FOREIGN KEY FK_ED289F11A76ED395');
        $this->addSql('DROP INDEX IDX_ED289F11A76ED395 ON readiness_map_archive');
        $this->addSql('ALTER TABLE readiness_map_archive DROP user_id');
    }
}
