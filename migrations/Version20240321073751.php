<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321073751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE readiness_map_check_status ADD readiness_map_check_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE readiness_map_check_status ADD CONSTRAINT FK_6DDEF0AE3A2AEB8 FOREIGN KEY (readiness_map_check_id) REFERENCES readiness_map_check (id)');
        $this->addSql('CREATE INDEX IDX_6DDEF0AE3A2AEB8 ON readiness_map_check_status (readiness_map_check_id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE readiness_map_check_status DROP FOREIGN KEY FK_6DDEF0AE3A2AEB8');
        $this->addSql('DROP INDEX IDX_6DDEF0AE3A2AEB8 ON readiness_map_check_status');
        $this->addSql('ALTER TABLE readiness_map_check_status DROP readiness_map_check_id');

    }
}
