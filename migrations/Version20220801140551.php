<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801140551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE procurement_procedures ADD CONSTRAINT FK_31045326A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_31045326A76ED395 ON procurement_procedures (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procurement_procedures DROP FOREIGN KEY FK_31045326A76ED395');
        $this->addSql('DROP INDEX IDX_31045326A76ED395 ON procurement_procedures');
        $this->addSql('ALTER TABLE procurement_procedures DROP user_id');
    }
}
