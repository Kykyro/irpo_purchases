<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317072705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE another_document (id INT AUTO_INCREMENT NOT NULL, purchases_id INT DEFAULT NULL, version INT DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, INDEX IDX_991A8989559939B3 (purchases_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE another_document ADD CONSTRAINT FK_991A8989559939B3 FOREIGN KEY (purchases_id) REFERENCES procurement_procedures (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE another_document DROP FOREIGN KEY FK_991A8989559939B3');
        $this->addSql('DROP TABLE another_document');
    }
}
