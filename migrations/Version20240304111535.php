<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304111535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE add_contacts ADD contact_info_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE add_contacts ADD CONSTRAINT FK_BAAE305C7A4E079 FOREIGN KEY (contact_info_id) REFERENCES contact_info (id)');
        $this->addSql('CREATE INDEX IDX_BAAE305C7A4E079 ON add_contacts (contact_info_id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE add_contacts DROP FOREIGN KEY FK_BAAE305C7A4E079');
        $this->addSql('DROP INDEX IDX_BAAE305C7A4E079 ON add_contacts');
        $this->addSql('ALTER TABLE add_contacts DROP contact_info_id');

    }
}
