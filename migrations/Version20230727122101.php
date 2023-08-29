<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727122101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employers_contact (id INT AUTO_INCREMENT NOT NULL, employer_id INT DEFAULT NULL, contact_info_id INT DEFAULT NULL, fio VARCHAR(255) DEFAULT NULL, post VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, INDEX IDX_9A2BDA441CD9E7A (employer_id), INDEX IDX_9A2BDA4C7A4E079 (contact_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employers_contact ADD CONSTRAINT FK_9A2BDA441CD9E7A FOREIGN KEY (employer_id) REFERENCES employers (id)');
        $this->addSql('ALTER TABLE employers_contact ADD CONSTRAINT FK_9A2BDA4C7A4E079 FOREIGN KEY (contact_info_id) REFERENCES contact_info (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employers_contact DROP FOREIGN KEY FK_9A2BDA441CD9E7A');
        $this->addSql('ALTER TABLE employers_contact DROP FOREIGN KEY FK_9A2BDA4C7A4E079');
        $this->addSql('DROP TABLE employers_contact');
    }
}
