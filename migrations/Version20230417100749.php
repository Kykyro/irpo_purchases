<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230417100749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repair_dump ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repair_dump ADD CONSTRAINT FK_924AA5C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_924AA5C5A76ED395 ON repair_dump (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repair_dump DROP FOREIGN KEY FK_924AA5C5A76ED395');
        $this->addSql('DROP INDEX IDX_924AA5C5A76ED395 ON repair_dump');
        $this->addSql('ALTER TABLE repair_dump DROP user_id');
    }
}
