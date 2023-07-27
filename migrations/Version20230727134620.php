<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727134620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE responsible_contact_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsible_contact_type_responsible_contact (responsible_contact_type_id INT NOT NULL, responsible_contact_id INT NOT NULL, INDEX IDX_EBE2804B11279B3F (responsible_contact_type_id), INDEX IDX_EBE2804BDC011ACD (responsible_contact_id), PRIMARY KEY(responsible_contact_type_id, responsible_contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE responsible_contact_type_responsible_contact ADD CONSTRAINT FK_EBE2804B11279B3F FOREIGN KEY (responsible_contact_type_id) REFERENCES responsible_contact_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE responsible_contact_type_responsible_contact ADD CONSTRAINT FK_EBE2804BDC011ACD FOREIGN KEY (responsible_contact_id) REFERENCES responsible_contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employers_contact ADD email VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE responsible_contact_type_responsible_contact DROP FOREIGN KEY FK_EBE2804B11279B3F');
        $this->addSql('ALTER TABLE responsible_contact_type_responsible_contact DROP FOREIGN KEY FK_EBE2804BDC011ACD');
        $this->addSql('DROP TABLE responsible_contact_type');
        $this->addSql('DROP TABLE responsible_contact_type_responsible_contact');
        $this->addSql('ALTER TABLE employers_contact DROP email');
    }
}
