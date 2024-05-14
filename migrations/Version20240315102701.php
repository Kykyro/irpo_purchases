<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315102701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_result ADD users_events_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event_result ADD CONSTRAINT FK_21F3B641FB176A84 FOREIGN KEY (users_events_id) REFERENCES users_events (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE event_result DROP FOREIGN KEY FK_21F3B641FB176A84');
        $this->addSql('DROP INDEX IDX_21F3B641FB176A84 ON event_result');
        $this->addSql('ALTER TABLE event_result DROP users_events_id');

    }
}
